<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\NomenclatureOperationRequest;
use App\Models\Nomenclature;
use App\Models\NomenclatureOperation;
use App\Services\NomenclatureOperationService;
use App\Services\Toast;
use App\Services\UnitConvertor;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class NomenclatureOperationController extends Controller
{
    public const TYPE_ROUTES = [
        NomenclatureOperation::OPERATION_TYPE_WITHDRAW => 'nomenclature-operations.index-withdraw'
    ];

    public const TYPE_BACK_ROUTES = [
        NomenclatureOperation::OPERATION_TYPE_WITHDRAW => 'nomenclature-operations.index-withdraw'
    ];

    public function __construct(
        public NomenclatureOperationService $nomenclatureOperationService
    )
    {
    }

    public function withdrawIndex()
    {
        $nomenclatureOperations = NomenclatureOperation::with('nomenclature')
            ->typeWithdraw()
            ->paginate()
            ->through(fn($m) => [
                'id' => $m->id,
                'nomenclature' => [
                    'name' => $m->nomenclature->name,
                    'unit' => UnitConvertor::UNIT_LABELS[$m->nomenclature->unit]
                ],
                'can_edit' => $m->can_edit,
                'quantity' => $m->quantity,
                'created_at' => $m->created_at->format('d-m-Y H:i')
            ]);

        return inertia('NomenclatureOperations/Index', [
            'type' => NomenclatureOperation::OPERATION_TYPE_WITHDRAW,
            'nomenclatureOperations' => $nomenclatureOperations
        ]);
    }

    public function create()
    {
        $currentType = \request('type');

        $backRoute = Arr::get(self::TYPE_BACK_ROUTES, $currentType);

        $nomenclatures = Nomenclature::saleType()->get(['id', 'name']);

        return inertia('NomenclatureOperations/Edit', compact('nomenclatures', 'currentType', 'backRoute'));
    }

    public function store(NomenclatureOperationRequest $request)
    {
        $this->nomenclatureOperationService->store($request->validated());

        Toast::success('Успешно добавлено.');

        return to_route(self::TYPE_ROUTES[$request->type]);
    }

    public function edit(NomenclatureOperation $nomenclatureOperation)
    {
        $currentType = \request('type');

        $nomenclatures = Nomenclature::saleType()->get(['id', 'name']);

        $backRoute = Arr::get(self::TYPE_BACK_ROUTES, $currentType);

        return inertia('NomenclatureOperations/Edit', compact('nomenclatures',  'currentType', 'nomenclatureOperation', 'backRoute'));
    }

    public function update(int $nomenclatureOperationId, NomenclatureOperationRequest $request)
    {
        $this->nomenclatureOperationService->update($nomenclatureOperationId, $request->validated());

        Toast::success('Успешно обновлено.');

        return to_route(self::TYPE_ROUTES[$request->type]);
    }

    public function destroy(int $nomenclatureOperationId)
    {
        $this->nomenclatureOperationService->delete($nomenclatureOperationId);

        Toast::success('Успешно удалено.');

        return back();
    }
}
