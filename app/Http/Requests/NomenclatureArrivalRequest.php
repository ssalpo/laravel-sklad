<?php

namespace App\Http\Requests;

use App\Services\UnitConvertor;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class NomenclatureArrivalRequest extends FormRequest
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
            'nomenclature_id' => 'required|exists:nomenclatures,id',
            'quantity' => 'required|numeric',
            'unit' => 'required|in:' . implode(',', array_keys(UnitConvertor::UNIT_LABELS)),
            'price_for_sale' => 'required|regex:/^\d+(\.\d{1,3})?$/',
            'comment' => 'nullable|string',
            'arrival_at' => 'nullable|date_format:Y-m-d H:i'
        ];
    }

    protected function prepareForValidation()
    {
        return $this->merge([
            'arrival_at' => $this->arrival_at ? Carbon::parse($this->arrival_at)->format('Y-m-d H:i') : null
        ]);
    }
}
