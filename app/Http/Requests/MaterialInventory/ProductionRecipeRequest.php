<?php

namespace App\Http\Requests\MaterialInventory;

use App\Models\Nomenclature;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductionRecipeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nomenclature_id' => ['required', 'integer', Rule::exists('nomenclatures', 'id')->where('type', Nomenclature::TYPE_SALE)],
            'is_active' => ['nullable', 'boolean'], 'items' => ['required', 'array', 'min:1'],
            'items.*.material_nomenclature_id' => ['required', 'integer', 'distinct', Rule::exists('nomenclatures', 'id')->where('type', Nomenclature::TYPE_COMPOSITE)],
            'items.*.quantity_per_unit' => ['required', 'numeric', 'gt:0'],
        ];
    }
}
