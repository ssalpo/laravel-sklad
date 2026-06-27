<?php

namespace App\Http\Requests;

use App\Models\ClientPriceTemplate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ClientPriceTemplateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'client_id' => 'required|exists:clients,id',
            'nomenclature_id' => [
                'required',
                'exists:nomenclatures,id',
                Rule::unique(ClientPriceTemplate::class)
                    ->where('client_id', $this->route('client')->id)
                    ->whereNull('deleted_at')
                    ->ignore($this->route('client_price_template')),
            ],
            'price' => 'required|regex:/^\d+(\.\d{1,3})?$/',
        ];
    }

    public function messages()
    {
        return [
            'nomenclature_id.unique' => 'Для этой номенклатуры уже добавлена цена.',
        ];
    }

    protected function prepareForValidation()
    {
        return $this->merge([
            'client_id' => $this->route('client')->id,
        ]);
    }
}
