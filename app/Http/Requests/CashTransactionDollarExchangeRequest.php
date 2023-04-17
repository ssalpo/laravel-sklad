<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CashTransactionDollarExchangeRequest extends FormRequest
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
            'dollar_exchange_rate' => 'required|gt:0|regex:/^\d+(\.\d{1,4})?$/'
        ];
    }
}
