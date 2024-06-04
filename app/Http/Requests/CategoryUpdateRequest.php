<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CategoryUpdateRequest extends FormRequest
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
            'name' => [
                Rule::unique('categories', 'name')->ignore($this->id, 'uuid')->whereNull('deleted_at')
            ],
            'parent_uuid' => [
                Rule::when($this->parent_uuid == null, ['nullable']),
                Rule::when($this->parent_uuid != null, ['exists:categories,uuid']),
            ]

        ];
    }
}