<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClientDiscountRequest extends FormRequest
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
            'client_id' => 'required|exists:clients,id',
            'nomenclature_id' => 'required|exists:nomenclatures,id',
            'discount' => 'required|regex:/^\d+(\.\d{1,3})?$/',
        ];
    }

    public function prepareForValidation()
    {
        return $this->merge([
            'client_id' => $this->route('client')->id
        ]);
    }
}
