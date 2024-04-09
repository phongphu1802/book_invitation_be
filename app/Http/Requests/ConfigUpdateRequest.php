<?php

namespace App\Http\Requests;

use App\Enums\ConfigEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ConfigUpdateRequest extends FormRequest
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
            'key' => [Rule::unique('configs')->ignore($this->key, 'key')],
            'type' => [Rule::in([ConfigEnum::PUBLIC ->value, ConfigEnum::SYSTEM->value])]
        ];
    }
}