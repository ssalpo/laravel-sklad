<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\NomenclatureRequest;
use App\Models\Nomenclature;
use App\Services\NomenclatureService;
use App\Services\UnitConvertor;
use Illuminate\Http\RedirectResponse;
use Inertia\Response;

class NomenclatureController extends Controller
{
    public function __construct(
        public NomenclatureService $nomenclatureService
    )
    {
    }

    public function index(): Response
    {
        $nomenclatures = Nomenclature::orderBy('created_at', 'DESC')->paginate()
            ->through(fn($model) => [
                'id' => $model->id,
                'name' => $model->name,
                'price' => $model->price,
                'markup' => $model->markup,
                'price_for_sale' => $model->price_for_sale,
                'type' => $model->type,
                'unit' => UnitConvertor::UNIT_LABELS[$model->unit],
            ]);

        return inertia('Nomenclatures/Index', compact('nomenclatures'));
    }

    public function create(): Response
    {
        $nomenclatureTypes = Nomenclature::TYPES_LIST;

        return inertia('Nomenclatures/Edit', compact(
            'nomenclatureTypes'
        ));
    }


    public function store(NomenclatureRequest $request): RedirectResponse
    {
        $this->nomenclatureService->store($request->validated());

        return to_route('nomenclatures.index');
    }

    public function edit(Nomenclature $nomenclature): Response
    {
        $nomenclatureTypes = Nomenclature::TYPES_LIST;

        return inertia('Nomenclatures/Edit', compact(
            'nomenclature',
            'nomenclatureTypes'
        ));
    }

    public function update(int $nomenclature, NomenclatureRequest $request): RedirectResponse
    {
        $this->nomenclatureService->update($nomenclature, $request->validated());

        return to_route('nomenclatures.index');
    }
}
