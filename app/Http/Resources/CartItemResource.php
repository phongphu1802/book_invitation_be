<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Request;

class CartItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $cart_item = [
            'uuid' => $this->uuid,
            'product_uuid'=>$this->product_uuid,
            'quantity' => $this->quantity,
            'sub_total' => $this->sub_total,
        ];

        $expand = Request::get('expand', []);

        if (in_array('cart_item__product', $expand)) {
            $cart_item['product'] = $this->product;
        }

        return $cart_item;
    }


}
