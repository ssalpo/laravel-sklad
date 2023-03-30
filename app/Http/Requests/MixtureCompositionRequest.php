<?php

namespace App\Http\Requests;

use App\Services\UnitConvertor;
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
        $unitKeys = implode(',', array_keys(UnitConvertor::UNIT_LABELS));

        return [
            'nomenclature_id' => 'required|exists:nomenclatures,id',
            'weight' => 'required|numeric',
            'weight_unit' => 'required|int:' . $unitKeys,
            'water' => 'required|numeric',
            'water_unit' => 'required|int:' . $unitKeys,
            'worker_price' => 'required|regex:/^\d+(\.\d{1,3})?$/'
        ];
    }
}
