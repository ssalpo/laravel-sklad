<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\NomenclatureRequest;
use App\Models\Nomenclature;
use App\Services\NomenclatureService;
use App\Services\Toast;
use App\Services\UnitConvertor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
        $nomenclatures = Nomenclature::orderBy('type', 'ASC')
            ->orderBy('created_at', 'DESC')
            ->paginate(100)
            ->through(fn($model) => [
                'id' => $model->id,
                'name' => $model->name,
                'price' => $model->price,
                'markup' => $model->markup,
                'price_for_sale' => $model->price_for_sale,
                'dollar_exchange_rate' => $model->dollar_exchange_rate,
                'is_price_manual' => $model->is_price_manual,
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

        Toast::success('Новая номенклатура успешно добавлена.');

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

        Toast::success('Данные номенклатуры успешно обновлены.');

        return to_route('nomenclatures.index');
    }

    public function changeMarkups(Request $request)
    {
        $exchangeRate = $request->input('dollar_exchange_rate', 0);

        if($exchangeRate) {
            $this->nomenclatureService->changeMarkups($exchangeRate);
        }

        Toast::info('Себестоимость всех товаров изменены.');
    }
}
