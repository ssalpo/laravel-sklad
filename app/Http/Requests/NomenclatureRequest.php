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
            'type' => 'required|in:' . implode(',', array_keys(Nomenclature::TYPES_LIST)),
            'price_for_sale' => 'nullable|regex:/^\d+(\.\d{1,3})?$/',
            'unit' => 'required|int:'. implode(',', array_keys(UnitConvertor::UNIT_LABELS)),
        ];
    }
}
