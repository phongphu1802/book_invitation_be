<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OrderCreateRequest extends FormRequest
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
            'order_date' => ['required', 'date'],
            'total_amount' => ['required'],
            'product'=> ['required'],
            'product.*.product_uuid' => ['required', Rule::exists('products','uuid')],
            'product.*.quantity' => ['required'],
        ];
    }
}
