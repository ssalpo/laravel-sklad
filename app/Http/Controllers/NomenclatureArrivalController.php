<?php

namespace App\Http\Controllers;

use App\Models\Nomenclature;
use App\Models\NomenclatureArrival;
use App\UnitConvertor;
use Illuminate\Http\Request;

class NomenclatureArrivalController extends Controller
{

    public function index()
    {
        $nomenclatureArrivals = NomenclatureArrival::with(['nomenclature'])
            ->orderBy('created_at', 'DESC')
            ->paginate()
            ->through(fn($model) => [
                'id' => $model->id,
                'nomenclature' => $model->nomenclature->name,
                'quantity' => $model->quantity,
                'unit' => UnitConvertor::UNIT_LABELS[$model->unit],
                'price_for_sale' => $model->price_for_sale,
                'comment' => $model->comment,
                'arrival_at' => $model->arrival_at->format('d.m.Y H:i'),
            ]);

        return inertia('NomenclatureArrivals/Index', compact('nomenclatureArrivals'));
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

        $currentDate = date('yyyy-MM-ddThh:mm');

        return inertia('NomenclatureArrivals/Edit', compact('nomenclatures', 'currentDate'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
