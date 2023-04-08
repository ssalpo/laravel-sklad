<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderRefundRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'order_id' => 'required|numeric|exists:orders,id',
            'order_item_id' => 'required|numeric|exists:order_items,id',
            'nomenclature_id' => 'required|numeric|exists:nomenclatures,id',
            'comment' => 'required|string',
            'quantity' => 'required|numeric|gt:0',
        ];
    }
}
