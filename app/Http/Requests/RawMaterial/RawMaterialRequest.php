<?php

namespace App\Http\Requests\RawMaterial;

use Illuminate\Foundation\Http\FormRequest;

class RawMaterialRequest extends FormRequest
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
            'client_id' => 'required|exists:clients,id',
            'nomenclature_id' => 'required|exists:nomenclatures,id',
            'quantity' => 'required|numeric',
            'price' => 'required|numeric',
            'total_amount' => 'required|numeric',
            'prepaid_amount' => 'sometimes|numeric'
        ];
    }
}
