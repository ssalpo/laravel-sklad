<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClientRequest extends FormRequest
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
            'phone' => 'nullable|min:2|max:255',
            'discount' => 'nullable|regex:/^\d+(\.\d{1,3})?$/',
            'discount_for_single' => 'nullable|bool'
        ];
    }
}
