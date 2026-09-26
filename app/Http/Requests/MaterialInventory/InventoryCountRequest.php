<?php

namespace App\Http\Requests\MaterialInventory;

use App\Models\Nomenclature;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class InventoryCountRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return ['comment' => ['nullable', 'string', 'max:1000'], 'items' => ['required', 'array', 'min:1'], 'items.*.nomenclature_id' => ['required', 'integer', 'distinct', Rule::exists('nomenclatures', 'id')->where('type', Nomenclature::TYPE_COMPOSITE)], 'items.*.actual_quantity' => ['required', 'numeric', 'min:0']];
    }
}
