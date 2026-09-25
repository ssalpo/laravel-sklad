<?php

namespace App\Services;

use App\Enums\WarehouseMovementDirection;
use App\Models\Nomenclature;
use App\Models\WarehouseMovement;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;

class WarehouseStockService
{
    public function getBalance(Nomenclature|int $nomenclature): string
    {
        $id = $nomenclature instanceof Nomenclature ? $nomenclature->id : $nomenclature;

        return $this->getBalances(collect([$id]))->get($id, '0.000000');
    }

    public function getBalances(Collection|array|null $nomenclatureIds = null): Collection
    {
        $query = WarehouseMovement::query()
            ->select('nomenclature_id')
            ->selectRaw('COALESCE(SUM(CASE WHEN direction = ? THEN quantity ELSE -quantity END), 0) AS balance', [WarehouseMovementDirection::IN])
            ->groupBy('nomenclature_id');

        if ($nomenclatureIds !== null) {
            $query->whereIn('nomenclature_id', collect($nomenclatureIds)->all());
        }

        return $query->pluck('balance', 'nomenclature_id')->map(
            fn ($balance) => $this->decimal($balance)
        );
    }

    public function getTotals(Collection|array|null $nomenclatureIds = null): Collection
    {
        $query = WarehouseMovement::query()
            ->select('nomenclature_id')
            ->selectRaw("COALESCE(SUM(CASE WHEN direction = 'in' THEN quantity ELSE 0 END), 0) AS incoming")
            ->selectRaw("COALESCE(SUM(CASE WHEN direction = 'out' THEN quantity ELSE 0 END), 0) AS outgoing")
            ->groupBy('nomenclature_id');

        if ($nomenclatureIds !== null) {
            $query->whereIn('nomenclature_id', collect($nomenclatureIds)->all());
        }

        return $query->get()->keyBy('nomenclature_id')->map(fn ($row) => [
            'incoming' => $this->decimal($row->incoming),
            'outgoing' => $this->decimal($row->outgoing),
            'balance' => bcsub($this->decimal($row->incoming), $this->decimal($row->outgoing), 6),
        ]);
    }

    public function ensureAvailable(Nomenclature|int $nomenclature, string|int|float $quantity): void
    {
        $available = $this->getBalance($nomenclature);
        $requested = $this->decimal($quantity);

        if (bccomp($requested, $available, 6) === 1) {
            throw ValidationException::withMessages([
                'quantity' => "Недостаточно товара на складе. Доступно: {$available}.",
            ]);
        }
    }

    private function decimal(string|int|float|null $value): string
    {
        return bcadd((string) ($value ?? 0), '0', 6);
    }
}
