<?php

namespace App\Http\Controllers;

use App\Http\Requests\MixtureCompositionItemRequest;
use App\Models\MixtureCompositionItem;
use App\Models\Nomenclature;
use Illuminate\Http\Request;

class MixtureCompositionItemController extends Controller
{
    public function create(int $mixtureCompositionId)
    {
        $nomenclatures = Nomenclature::compositeType()->whereNotIn(
            'id', MixtureCompositionItem::whereMixtureCompositionId($mixtureCompositionId)->pluck('nomenclature_id')
        )->get(['id', 'name']);

        return inertia('MixtureCompositionItems/Edit', compact('mixtureCompositionId', 'nomenclatures'));
    }

    public function store(int $mixtureCompositionId, MixtureCompositionItemRequest $request)
    {
        MixtureCompositionItem::create($request->validated());

        return to_route('mixture-compositions.show', $mixtureCompositionId);
    }

    public function edit(int $mixtureCompositionId, int $mixtureCompositionItemId)
    {
        $mixtureCompositionItem = MixtureCompositionItem::where('mixture_composition_id', $mixtureCompositionId)->findOrFail($mixtureCompositionItemId);
        $nomenclatures = Nomenclature::compositeType()->get(['id', 'name']);

        return inertia('MixtureCompositionItems/Edit', compact(
            'mixtureCompositionId',
            'mixtureCompositionItem',
            'nomenclatures'
        ));
    }

    public function update(int $mixtureCompositionId, int $mixtureCompositionItemId, MixtureCompositionItemRequest $request)
    {
        $mixtureCompositionItem = MixtureCompositionItem::where('mixture_composition_id', $mixtureCompositionId)
            ->findOrFail($mixtureCompositionItemId);

        $mixtureCompositionItem->update($request->validated());

        return to_route('mixture-compositions.show', $mixtureCompositionId);
    }

    public function destroy(int $mixtureCompositionId, int $mixtureCompositionItemId)
    {
        MixtureCompositionItem::where('mixture_composition_id', $mixtureCompositionId)
            ->findOrFail($mixtureCompositionItemId)->delete();

        return to_route('mixture-compositions.show', $mixtureCompositionId);
    }
}
