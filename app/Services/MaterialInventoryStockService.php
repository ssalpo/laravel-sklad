<?php

namespace App\Services;

use App\Enums\MaterialInventoryMovementDirection;
use App\Models\MaterialInventoryMovement;
use App\Models\Nomenclature;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;

class MaterialInventoryStockService
{
    public function getBalance(Nomenclature|int $nomenclature): string
    {
        $id = $nomenclature instanceof Nomenclature ? $nomenclature->id : $nomenclature;

        return $this->getBalances([$id])->get($id, '0.000000');
    }

    public function getBalances(Collection|array|null $ids = null): Collection
    {
        $query = MaterialInventoryMovement::query()->select('nomenclature_id')
            ->selectRaw('COALESCE(SUM(CASE WHEN direction = ? THEN quantity ELSE -quantity END), 0) AS balance', [MaterialInventoryMovementDirection::IN])
            ->groupBy('nomenclature_id');
        if ($ids !== null) {
            $query->whereIn('nomenclature_id', collect($ids)->all());
        }

        return $query->pluck('balance', 'nomenclature_id')->map(fn ($value) => $this->decimal($value));
    }

    public function getTotals(Collection|array|null $ids = null): Collection
    {
        $query = MaterialInventoryMovement::query()->select('nomenclature_id')
            ->selectRaw("COALESCE(SUM(CASE WHEN direction = 'in' THEN quantity ELSE 0 END), 0) AS incoming")
            ->selectRaw("COALESCE(SUM(CASE WHEN direction = 'out' THEN quantity ELSE 0 END), 0) AS outgoing")
            ->groupBy('nomenclature_id');
        if ($ids !== null) {
            $query->whereIn('nomenclature_id', collect($ids)->all());
        }

        return $query->get()->keyBy('nomenclature_id')->map(fn ($row) => [
            'incoming' => $this->decimal($row->incoming),
            'outgoing' => $this->decimal($row->outgoing),
            'balance' => bcsub($this->decimal($row->incoming), $this->decimal($row->outgoing), 6),
        ]);
    }

    public function ensureAvailable(int $nomenclatureId, string|int|float $quantity): void
    {
        $available = $this->getBalance($nomenclatureId);
        $requested = $this->decimal($quantity);
        if (bccomp($requested, $available, 6) === 1) {
            throw ValidationException::withMessages(['quantity' => "Недостаточно материала на складе. Доступно: {$available}."]);
        }
    }

    private function decimal(string|int|float|null $value): string
    {
        return bcadd((string) ($value ?? 0), '0', 6);
    }
}
