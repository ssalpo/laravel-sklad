<?php

namespace App\Http\Requests;

use App\UnitConvertor;
use Illuminate\Foundation\Http\FormRequest;

class MixtureCompositionItemRequest extends FormRequest
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
            'mixture_composition_id' => 'required|exists:mixture_compositions,id',
            'nomenclature_id' => 'required|exists:nomenclatures,id',
            'price' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'quantity' => 'required|numeric',
            'unit' => 'required|int:'. implode(',', array_keys(UnitConvertor::UNIT_LABELS)),
        ];
    }

    protected function prepareForValidation()
    {
        return $this->merge([
            'mixture_composition_id' => $this->route('mixture_composition')
        ]);
    }
}
