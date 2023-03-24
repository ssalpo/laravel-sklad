<?php

namespace App\Http\Requests;

use App\Models\Nomenclature;
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
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|min:2|max:255',
            'type' => 'required|in:' . implode(',', array_keys(Nomenclature::TYPES_LIST)),
            'price_for_sale' => 'nullable|regex:/^\d+(\.\d{1,2})?$/',
            'currency_type' => 'required|in:' . implode(',', array_keys(Nomenclature::CURRENCY_TYPES)),
            'unit_id' => 'required|exists:units,id',
        ];
    }
}
