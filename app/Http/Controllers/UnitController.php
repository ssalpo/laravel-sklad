<?php

namespace App\Http\Controllers;

use App\Http\Requests\UnitRequest;
use App\Models\Unit;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    public function index()
    {
        $units = Unit::paginate()
            ->through(fn($model) => [
                'id' => $model->id,
                'name' => $model->name
            ]);

        return inertia('Units/Index', compact('units'));
    }

    public function create()
    {
        return inertia('Units/Edit');
    }

    public function store(UnitRequest $request)
    {
        Unit::create($request->validated());

        return to_route('units.index');
    }

    public function edit(Unit $unit)
    {
        return inertia('Units/Edit', compact('unit'));
    }

    public function update(UnitRequest $request, Unit $unit)
    {
        $unit->update($request->validated());

        return to_route('units.index');
    }
}
