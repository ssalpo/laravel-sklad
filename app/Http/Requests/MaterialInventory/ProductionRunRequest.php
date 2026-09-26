<?php

namespace App\Http\Requests\MaterialInventory;

use App\Models\Nomenclature;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductionRunRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return ['nomenclature_id' => ['required', 'integer', Rule::exists('nomenclatures', 'id')->where('type', Nomenclature::TYPE_SALE)], 'quantity' => ['required', 'numeric', 'gt:0'], 'produced_at' => ['required', 'date'], 'comment' => ['nullable', 'string', 'max:1000']];
    }
}
