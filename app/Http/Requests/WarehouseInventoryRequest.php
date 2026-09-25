<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WarehouseInventoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'comment' => ['nullable', 'string', 'max:1000'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.nomenclature_id' => ['required', 'integer', 'distinct', 'exists:nomenclatures,id'],
            'items.*.actual_quantity' => ['required', 'numeric', 'min:0'],
        ];
    }
}
