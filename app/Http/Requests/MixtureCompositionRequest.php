<?php

namespace App\Http\Requests;

use App\Models\Nomenclature;
use Illuminate\Foundation\Http\FormRequest;

class MixtureCompositionRequest extends FormRequest
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
            'nomenclature_id' => 'required|exists:nomenclatures,id',
            'currency_type' => 'required|in:' . implode(',', array_keys(Nomenclature::CURRENCY_TYPES)),
            'weight' => 'required|numeric',
            'weight_unit_id' => 'required|exists:units,id',
            'water' => 'required|numeric',
            'water_unit_id' => 'required|exists:units,id',
            'worker_price' => 'required|regex:/^\d+(\.\d{1,2})?$/'
        ];
    }
}
