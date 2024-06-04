<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class ProductStatisticsRequest extends FormRequest
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
        $to = Carbon::parse($this->end_date);

        return [
            'from_date' => 'required|before:to_date,' . date('Y-m-d', strtotime($to . ' +1 day')),
            'to_date' => ['required', 'date'],
        ];
    }
}