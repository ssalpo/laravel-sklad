<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorehouseRequest;
use App\Models\Storehouse;
use Illuminate\Http\Request;

class StorehouseController extends Controller
{
    public function index()
    {
        $storehouses = Storehouse::paginate()
            ->through(fn($model) => [
                'id' => $model->id,
                'name' => $model->name
            ]);

        return inertia('Storehouses/Index', compact('storehouses'));
    }

    public function create()
    {
        return inertia('Storehouses/Edit');
    }

    public function store(StorehouseRequest $request)
    {
        Storehouse::create($request->validated());

        return to_route('storehouses.index');
    }

    public function edit(Storehouse $storehouse)
    {
        return inertia('Storehouses/Edit', compact('storehouse'));
    }

    public function update(StorehouseRequest $request, Storehouse $storehouse)
    {
        $storehouse->update($request->validated());

        return to_route('storehouses.index');
    }
}
