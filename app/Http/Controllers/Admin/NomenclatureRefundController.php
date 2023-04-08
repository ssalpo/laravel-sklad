<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NomenclatureOperation;
use App\Services\UnitConvertor;
use Illuminate\Http\Request;

class NomenclatureRefundController extends Controller
{
    public function index()
    {
        $filterParams = request()?->collect()->except(['page'])->all();

        $nomenclatureRefunds = NomenclatureOperation::typeRefund()
            ->filter($filterParams)
            ->with(['order', 'nomenclature'])
            ->paginate(1)
            ->withQueryString()
            ->through(fn($m) => [
                'id' => $m->id,
                'order_id' => $m->order_id,
                'nomenclature' => [
                    'name' => $m->nomenclature->name,
                    'unit' => UnitConvertor::UNIT_LABELS[$m->nomenclature->unit]
                ],
                'quantity' => $m->quantity,
                'price' => $m->price,
                'price_for_sale' => $m->price_for_sale,
                'comment' => $m->comment,
                'created_at' => $m->created_at->format('d-m-Y H:i'),
            ]);

        return inertia(
            'NomenclatureRefunds/Index',
            compact('nomenclatureRefunds', 'filterParams')
        );
    }
}
