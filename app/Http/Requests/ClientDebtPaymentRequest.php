<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClientDebtPaymentRequest extends FormRequest
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
            'created_by' => 'required|numeric',
            'amount' => 'required|regex:/^\d+(\.\d{1,3})?$/'
        ];
    }

    public function prepareForValidation()
    {
        return $this->merge(['created_by' => auth()->id()]);
    }
}
