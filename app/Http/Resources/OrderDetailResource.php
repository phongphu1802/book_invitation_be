<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Request;

class OrderDetailResource extends JsonResource
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
            'quantity' => $this->quantity,
            'order_uuid' => $this->order_uuid,
            'product_uuid' => $this->product_uuid,
            'sub_total' => $this->sub_total,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];

        $expand = Request::get('expand', []);

        if (in_array('order_detail__product_uuid', $expand)) {
            $data['product'] = $this->product;
        }

        if (in_array('order_detail__product', $expand)) {
            $data['product'] = $this->product;
        }

        return $data;
    }
}