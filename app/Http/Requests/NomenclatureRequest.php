<?php

namespace App\Http\Requests;

use App\Models\Nomenclature;
use App\Services\UnitConvertor;
use Illuminate\Foundation\Http\FormRequest;

class NomenclatureRequest extends FormRequest
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
            'name' => 'required|min:2|max:255',
            'is_price_manual' => 'required|boolean',
            'price' => 'required_if:is_price_manual,true|regex:/^\d+(\.\d{1,3})?$/|gt:0',
            'type' => 'required|in:' . implode(',', array_keys(Nomenclature::TYPES_LIST)),
            'unit' => 'required|int:'. implode(',', array_keys(UnitConvertor::UNIT_LABELS)),
        ];
    }

    public function messages()
    {
        return [
            'price.required_if' => 'Обязательно для заполнения, когда включено ручное заполнение.'
        ];
    }
}
