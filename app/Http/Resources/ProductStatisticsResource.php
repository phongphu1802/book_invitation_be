<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Request;

class ProductStatisticsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $data = [
            'product_uuid' => $this->product_uuid,
            'total_product' => $this->total_product,
        ];

        $expand = Request::get('expand', []);

        if (in_array('dashboard__product_uuid', $expand)) {
            $data['product'] = $this->product;
        }

        return $data;
    }
}