<?php

namespace App\Console\Commands;

use App\Models\Nomenclature;
use App\Services\LegacyWarehouseStockService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class AuditLegacyWarehouse extends Command
{
    protected $signature = 'warehouse:audit-legacy {--csv : Also write a CSV snapshot}';
    protected $description = 'Создать снимок legacy-остатков по текущему алгоритму страницы остатков';

    public function handle(LegacyWarehouseStockService $stockService): int
    {
        $balances = $stockService->getBalances();
        $rows = Nomenclature::query()->orderBy('id')->get()->map(fn (Nomenclature $nomenclature) => [
            'nomenclature_id' => $nomenclature->id,
            'name' => $nomenclature->name,
            'old_balance' => $balances->get($nomenclature->id, '0.000000'),
            'unit' => $nomenclature->unit,
        ]);
        $timestamp = now()->format('Ymd_His');
        $jsonPath = "warehouse/legacy-audit-{$timestamp}.json";
        Storage::disk('local')->put($jsonPath, $rows->toJson(JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        $this->table(['ID', 'Номенклатура', 'Остаток', 'Ед.'], $rows->all());
        $negative = $rows->filter(fn (array $row) => bccomp($row['old_balance'], '0', 6) === -1);
        $this->warn("Отрицательные остатки: {$negative->count()}");
        $this->info('JSON-снимок: storage/app/'.$jsonPath);

        if ($this->option('csv')) {
            $csvPath = "warehouse/legacy-audit-{$timestamp}.csv";
            $handle = fopen('php://temp', 'r+');
            fputcsv($handle, ['nomenclature_id', 'name', 'old_balance', 'unit']);
            foreach ($rows as $row) {
                fputcsv($handle, $row);
            }
            rewind($handle);
            Storage::disk('local')->put($csvPath, stream_get_contents($handle));
            fclose($handle);
            $this->info('CSV-снимок: storage/app/'.$csvPath);
        }

        return self::SUCCESS;
    }
}
