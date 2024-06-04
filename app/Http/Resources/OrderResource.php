<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Request;

class OrderResource extends JsonResource
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
            'uuid' => $this->uuid,
            'user_uuid' => $this->user_uuid,
            'total_amount' => $this->total_amount,
            'order_date' => $this->order_date,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at
        ];

        $expand = Request::get('expand', []);

        if (in_array('order__product', $expand)) {
            $data['products'] = OrderDetailResource::collection($this->order_details);
        }

        if (in_array('order__user_uuid', $expand)) {
            $data['user'] = $this->order_user;
        }

        return $data;
    }
}