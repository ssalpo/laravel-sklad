<?php

namespace App\Http\Requests\MaterialInventory;

use App\Models\Nomenclature;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MaterialMovementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nomenclature_id' => ['required', 'integer', Rule::exists('nomenclatures', 'id')->where('type', Nomenclature::TYPE_COMPOSITE)],
            'quantity' => ['required', 'numeric', 'gt:0'], 'comment' => ['nullable', 'string', 'max:1000'],
            'occurred_at' => ['required', 'date'], 'production_run_id' => ['nullable', 'integer', 'exists:production_runs,id'],
        ];
    }
}
