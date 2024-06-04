<?php

namespace App\Http\Resources;

use App\Enums\ConfigEnum;
use Illuminate\Http\Resources\Json\JsonResource;

class ConfigResource extends JsonResource
{

    public function convertData($value, $datatype)
    {
        if ($datatype == ConfigEnum::BOOLEAN->value) {
            return filter_var($value, FILTER_VALIDATE_BOOLEAN);
        }
        if ($datatype == ConfigEnum::IMAGES->value) {
            return json_decode($value);
        }
        if ($datatype == ConfigEnum::NUMBER->value) {
            return $value;
        }
        return $value;
    }

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
            'key' => $this->key,
            'value' => $this->convertData($this->value, $this->datatype),
            'datatype' => $this->datatype,
            'description' => $this->description,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
        ];

        return $data;
    }
}