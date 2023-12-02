<?php

namespace App\Http\Controllers\Admin\RawMaterial;

use App\Http\Controllers\Controller;
use App\Http\Requests\RawMaterial\RawMaterialPaymentRequest;
use App\Models\Nomenclature;
use App\Models\RawMaterial;
use App\Models\RawMaterialPayment;
use App\Services\RawMaterialPaymentService;
use App\Services\Toast;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class RawMaterialPaymentController extends Controller
{
    public function __construct(
        protected RawMaterialPaymentService $rawMaterialPaymentService
    )
    {
    }

    public function index(RawMaterial $rawMaterial)
    {
        $totalDebits = $rawMaterial->total_amount - $rawMaterial->payments()->sum('amount');

        $rawMaterialPayments = RawMaterialPayment::orderBy('created_at', 'DESC')
            ->where('raw_material_id', $rawMaterial->id)
            ->paginate()
            ->onEachSide(0)
            ->through(fn($m) => [
                'id' => $m->id,
                'raw_material_id' => $m->raw_material_id,
                'amount' => $m->amount,
                'comment' => $m->comment,
                'created_at' => $m->created_at->format('d-m-Y H:i'),
            ]);

        return inertia('RawMaterialPayments/Index', compact('rawMaterial', 'rawMaterialPayments', 'totalDebits'));
    }

    public function create(int $rawMaterialId)
    {
        return inertia('RawMaterialPayments/Edit', compact(
            'rawMaterialId'
        ));
    }

    public function store(int $rawMaterialId, RawMaterialPaymentRequest $request): RedirectResponse
    {
        $this->rawMaterialPaymentService->store($request->validated());

        Toast::success('Успешно добавлено.');

        return to_route('raw-materials.raw-material-payments.index', $rawMaterialId);
    }

    public function edit(int $rawMaterialId, RawMaterialPayment $rawMaterialPayment)
    {
        return inertia('RawMaterialPayments/Edit', compact(
            'rawMaterialId',
            'rawMaterialPayment'
        ));
    }

    public function update(int $rawMaterialId, int $id, RawMaterialPaymentRequest $request): RedirectResponse
    {
        $this->rawMaterialPaymentService->update($id, $request->validated());

        Toast::success('Данные успешно изменены.');

        return to_route('raw-materials.raw-material-payments.index', [
            'raw_material' => $rawMaterialId,
            'raw_material_payment' => $id
        ]);
    }

    public function destroy(int $rawMaterialId, int $id): RedirectResponse
    {
        $this->rawMaterialPaymentService->delete($id);

        Toast::success('Успешно удалено.');

        return to_route('raw-materials.raw-material-payments.index', [
            'raw_material' => $rawMaterialId,
            'raw_material_payment' => $id
        ]);
    }
}
