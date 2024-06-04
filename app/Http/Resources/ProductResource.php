<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Request;

class ProductResource extends JsonResource
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
            'name' => $this->name,
            'image' => $this->image,
            'detail_images' => json_decode($this->detail_images),
            'price' => $this->price,
            'category_uuid' => $this->category_uuid,
            'description' => $this->description,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at
        ];

        $expand = Request::get('expand', []);

        if (in_array('product__category_uuid', $expand)) {
            $data['category'] = $this->category;
        }

        return $data;
    }
}