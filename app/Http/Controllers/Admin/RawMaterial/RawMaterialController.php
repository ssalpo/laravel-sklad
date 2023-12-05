<?php

namespace App\Http\Controllers\Admin\RawMaterial;

use App\Http\Controllers\Controller;
use App\Http\Requests\RawMaterial\RawMaterialRequest;
use App\Models\RawMaterial;
use App\Models\RawMaterialPayment;
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
        $totalPaid = RawMaterial::sum('total_amount');
        $totalDebits = $totalPaid - RawMaterialPayment::sum('amount');

        $rawMaterials = RawMaterial::with('nomenclature', 'client')
            ->withSum('payments', 'amount')
            ->orderBy('created_at', 'DESC')
            ->paginate()
            ->onEachSide(0)
            ->through(fn($m) => [
                'id' => $m->id,
                'client' => [
                    'id' => $m->client->id,
                    'name' => $m->client->name,
                ],
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

        return inertia('RawMaterials/Index', compact('rawMaterials', 'totalPaid', 'totalDebits'));
    }

    public function create()
    {
        return inertia('RawMaterials/Edit');
    }

    public function store(RawMaterialRequest $request): RedirectResponse
    {
        $this->rawMaterialService->store($request->validated());

        Toast::success('Успешно добавлено.');

        return to_route('raw-materials.index');
    }

    public function edit(RawMaterial $rawMaterial)
    {
        return inertia('RawMaterials/Edit', compact('rawMaterial'));
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
