<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BookingFormResource extends JsonResource
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
            "uuid" => $this->uuid,
            "bride" => $this->bride,
            "groom" => $this->groom,
            "bride_family_address" => $this->bride_family_address,
            "bride_father_name" => $this->bride_father_name,
            "bride_mother_name" => $this->bride_mother_name,
            "groom_family_address" => $this->groom_family_address,
            "groom_father_name" => $this->groom_father_name,
            "groom_mother_name" => $this->groom_mother_name,
            "wedding_date" => $this->wedding_date,
            "party_date" => $this->party_date,
            "party_name_place" => $this->party_name_place,
            "party_address" => $this->party_address,
            "image_design" => $this->image_design,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
        return $data;
    }
}