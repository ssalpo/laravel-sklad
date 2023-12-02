<?php

namespace App\Http\Controllers\Admin\RawMaterial;

use App\Http\Controllers\Controller;
use App\Http\Requests\RawMaterial\RawMaterialRequest;
use App\Models\Nomenclature;
use App\Models\RawMaterial;
use App\Services\RawMaterialService;
use App\Services\Toast;
use Illuminate\Http\RedirectResponse;

class RawMaterialController extends Controller
{
    public function __construct(
        protected RawMaterialService $rawMaterialService
    )
    {
    }

    public function index()
    {
        $rawMaterials = RawMaterial::with('nomenclature')
            ->withSum('payments', 'amount')
            ->orderBy('created_at', 'DESC')
            ->paginate()
            ->onEachSide(0)
            ->through(fn($m) => [
                'id' => $m->id,
                'nomenclature' => [
                    'id' => $m->nomenclature->id,
                    'name' => $m->nomenclature->name,
                ],
                'quantity' => $m->quantity,
                'price' => $m->price,
                'total_amount' => $m->total_amount,
                'payments_sum_amount' => $m->payments_sum_amount,
                'created_at' => $m->created_at->format('d-m-Y H:i'),
            ]);

        return inertia('RawMaterials/Index', compact('rawMaterials'));
    }

    public function create()
    {
        $nomenclatures = Nomenclature::saleType()
            ->get()
            ->transform(fn($model) => [
                'id' => $model->id,
                'name' => $model->name,
                'unit' => $model->unit,
            ]);

        return inertia('RawMaterials/Edit', compact('nomenclatures'));
    }

    public function store(RawMaterialRequest $request): RedirectResponse
    {
        $this->rawMaterialService->store($request->validated());

        Toast::success('Успешно добавлено.');

        return to_route('raw-materials.index');
    }

    public function edit(RawMaterial $rawMaterial)
    {
        $nomenclatures = Nomenclature::saleType()
            ->get()
            ->transform(fn($model) => [
                'id' => $model->id,
                'name' => $model->name,
                'unit' => $model->unit,
            ]);

        return inertia('RawMaterials/Edit', compact('rawMaterial', 'nomenclatures'));
    }

    public function update(int $id, RawMaterialRequest $request): RedirectResponse
    {
        $this->rawMaterialService->update($id, $request->validated());

        Toast::success('Данные успешно изменены.');

        return to_route('raw-materials.index');
    }

    public function destroy(int $id): RedirectResponse
    {
        $this->rawMaterialService->delete($id);

        Toast::success('Успешно удалено.');

        return to_route('raw-materials.index');
    }
}
