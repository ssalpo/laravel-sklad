<?php

namespace App\Console\Commands;

use App\Models\Nomenclature;
use App\Services\LegacyWarehouseStockService;
use App\Services\WarehouseStockService;
use Illuminate\Console\Command;

class VerifyWarehouseMigration extends Command
{
    protected $signature = 'warehouse:verify-migration';
    protected $description = 'Сравнить legacy-остатки с остатками по движениям склада';

    public function handle(LegacyWarehouseStockService $legacy, WarehouseStockService $warehouse): int
    {
        $old = $legacy->getBalances();
        $new = $warehouse->getBalances();
        $rows = Nomenclature::query()->orderBy('id')->get()->map(function (Nomenclature $nomenclature) use ($old, $new) {
            $oldBalance = $old->get($nomenclature->id, '0.000000');
            $newBalance = $new->get($nomenclature->id, '0.000000');

            return [
                'id' => $nomenclature->id,
                'name' => $nomenclature->name,
                'old' => $oldBalance,
                'new' => $newBalance,
                'difference' => bcsub($newBalance, $oldBalance, 6),
            ];
        });
        $mismatches = $rows->filter(fn (array $row) => bccomp($row['difference'], '0', 6) !== 0);

        $this->table(['ID', 'Номенклатура', 'Старый остаток', 'Новый остаток', 'Разница'], $mismatches->isEmpty() ? $rows->all() : $mismatches->all());
        $this->line('Расхождений по остаткам: '.$mismatches->count());

        return $mismatches->isEmpty() ? self::SUCCESS : self::FAILURE;
    }
}
