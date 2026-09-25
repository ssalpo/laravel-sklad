<?php

namespace App\Console\Commands;

use App\Enums\WarehouseMovementDirection;
use App\Enums\WarehouseMovementType;
use App\Models\NomenclatureArrival;
use App\Models\NomenclatureOperation;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\WarehouseMovementService;
use Illuminate\Console\Command;
use Throwable;

class MigrateLegacyWarehouse extends Command
{
    protected $signature = 'warehouse:migrate-legacy {--chunk=500 : Number of records processed at once}';
    protected $description = 'Перенести активные legacy-приходы, списания, возвраты и отправленные заказы в движения склада';

    private array $stats = ['arrivals' => 0, 'operations' => 0, 'returns' => 0, 'sales' => 0, 'created' => 0, 'skipped' => 0, 'errors' => 0, 'missing_send_at' => 0];

    public function handle(WarehouseMovementService $movements): int
    {
        $chunk = (int) $this->option('chunk');
        $this->migrateArrivals($movements, $chunk);
        $this->migrateWithdrawals($movements, $chunk);
        $this->migrateReturns($movements, $chunk);
        $this->migrateSales($movements, $chunk);

        $this->table(['Приходы', 'Списания', 'Возвраты', 'Продажи', 'Создано', 'Пропущено', 'Ошибки', 'Нет даты отгрузки'], [[
            $this->stats['arrivals'], $this->stats['operations'], $this->stats['returns'], $this->stats['sales'], $this->stats['created'],
            $this->stats['skipped'], $this->stats['errors'], $this->stats['missing_send_at'],
        ]]);

        return $this->stats['errors'] === 0 ? self::SUCCESS : self::FAILURE;
    }

    private function migrateArrivals(WarehouseMovementService $movements, int $chunk): void
    {
        NomenclatureArrival::query()->orderBy('id')->chunkById($chunk, function ($arrivals) use ($movements): void {
            foreach ($arrivals as $arrival) {
                $this->stats['arrivals']++;
                $this->import($movements, [
                    'nomenclature_id' => $arrival->nomenclature_id,
                    'type' => WarehouseMovementType::PURCHASE,
                    'quantity' => $arrival->quantity,
                    'price' => $arrival->price,
                    'price_for_sale' => $arrival->price_for_sale,
                    'comment' => $arrival->comment,
                    'occurred_at' => $arrival->arrival_at,
                    'legacy_source_type' => 'nomenclature_arrival',
                    'legacy_source_id' => $arrival->id,
                ], WarehouseMovementDirection::IN);
            }
        });
    }

    private function migrateWithdrawals(WarehouseMovementService $movements, int $chunk): void
    {
        NomenclatureOperation::query()->typeWithdraw()->orderBy('id')->chunkById($chunk, function ($operations) use ($movements): void {
            foreach ($operations as $operation) {
                $this->stats['operations']++;
                $this->import($movements, [
                    'nomenclature_id' => $operation->nomenclature_id,
                    'type' => WarehouseMovementType::WRITE_OFF,
                    'quantity' => $operation->quantity,
                    'price' => $operation->price,
                    'price_for_sale' => $operation->price_for_sale,
                    'comment' => $operation->comment,
                    'occurred_at' => $operation->created_at,
                    'legacy_source_type' => 'nomenclature_operation',
                    'legacy_source_id' => $operation->id,
                ], WarehouseMovementDirection::OUT);
            }
        });
    }

    private function migrateSales(WarehouseMovementService $movements, int $chunk): void
    {
        OrderItem::query()->with('order')->whereHas('order', fn ($query) => $query->where('status', Order::STATUS_SEND))
            ->orderBy('id')->chunkById($chunk, function ($items) use ($movements): void {
                foreach ($items as $item) {
                    $this->stats['sales']++;
                    $order = $item->order;
                    if ($order->send_at === null) {
                        $this->stats['missing_send_at']++;
                    }
                    $this->import($movements, [
                        'nomenclature_id' => $item->nomenclature_id,
                        'type' => WarehouseMovementType::SALE,
                        'quantity' => $item->quantity,
                        'price' => $item->price,
                        'price_for_sale' => $item->price_for_sale,
                        'source_type' => Order::class,
                        'source_id' => $order->id,
                        'order_id' => $order->id,
                        'order_item_id' => $item->id,
                        'occurred_at' => $order->send_at ?? $order->created_at,
                        'created_by' => $order->user_id,
                        'legacy_source_type' => 'order_item_sale',
                        'legacy_source_id' => $item->id,
                    ], WarehouseMovementDirection::OUT);
                }
            });
    }

    private function migrateReturns(WarehouseMovementService $movements, int $chunk): void
    {
        NomenclatureOperation::query()->typeRefund()->orderBy('id')->chunkById($chunk, function ($operations) use ($movements): void {
            foreach ($operations as $operation) {
                $this->stats['returns']++;
                $this->import($movements, [
                    'nomenclature_id' => $operation->nomenclature_id,
                    'type' => WarehouseMovementType::CUSTOMER_RETURN,
                    'quantity' => $operation->quantity,
                    'price' => $operation->price,
                    'price_for_sale' => $operation->price_for_sale,
                    'source_type' => $operation->order_id ? Order::class : null,
                    'source_id' => $operation->order_id,
                    'order_id' => $operation->order_id,
                    'order_item_id' => $operation->order_item_id,
                    'comment' => $operation->comment,
                    'occurred_at' => $operation->created_at,
                    'legacy_source_type' => 'nomenclature_operation',
                    'legacy_source_id' => $operation->id,
                ], WarehouseMovementDirection::IN);
            }
        });
    }

    private function import(WarehouseMovementService $movements, array $attributes, string $direction): void
    {
        try {
            $movement = $movements->importLegacy($attributes, $direction);
            $movement === null ? $this->stats['skipped']++ : $this->stats['created']++;
        } catch (Throwable $exception) {
            $this->stats['errors']++;
            $this->error($exception->getMessage());
        }
    }
}
