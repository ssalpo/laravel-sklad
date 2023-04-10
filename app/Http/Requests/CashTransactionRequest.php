<?php

namespace App\Http\Requests;

use App\Models\CashTransaction;
use Illuminate\Foundation\Http\FormRequest;

class CashTransactionRequest extends FormRequest
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
            'created_by' => 'required',
            'type' => 'required|in:' . implode(',', array_keys(CashTransaction::TYPE_LABELS)),
            'amount' => 'required|gt:0|regex:/^\d+(\.\d{1,3})?$/',
            'comment' => 'nullable'
        ];
    }

    protected function prepareForValidation()
    {
        return $this->merge(['created_by' => auth()->id()]);
    }
}
