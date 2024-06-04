<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Request;

class UserStatisticResource extends JsonResource
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
            'user_uuid' => $this->user_uuid,
            'total_price' => $this->total_price
        ];

        $expand = Request::get('expand', []);

        if (in_array('dashboard__user_uuid', $expand)) {
            $data['user'] = $this->order_user;
        }

        return $data;
    }
}