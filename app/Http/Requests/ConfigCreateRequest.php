<?php

namespace App\Http\Requests;

use App\Enums\ConfigEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Symfony\Component\Console\Input\Input;

class ConfigCreateRequest extends FormRequest
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
            'key' => ['required', Rule::unique('configs', 'key')->whereNull('deleted_at')],
            'type' => ['required', Rule::in([ConfigEnum::PUBLIC ->value, ConfigEnum::SYSTEM->value])],
            'value' => [
                Rule::when($this->datatype === ConfigEnum::IMAGES->value, ['array']),
                Rule::when($this->datatype === ConfigEnum::BOOLEAN->value, ['boolean']),
                Rule::when($this->datatype === ConfigEnum::TEXT->value, ['string']),
                Rule::when($this->datatype === ConfigEnum::IMAGE->value, ['string']),
                Rule::when($this->datatype === ConfigEnum::NUMBER->value, ['numeric']),
            ],
            'datatype' => ['required', Rule::in([ConfigEnum::BOOLEAN->value, ConfigEnum::TEXT->value, ConfigEnum::IMAGE->value, ConfigEnum::IMAGES->value, ConfigEnum::NUMBER->value])]
        ];
    }
}