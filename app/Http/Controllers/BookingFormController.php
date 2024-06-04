<?php

namespace App\Http\Controllers;

use App\Abstracts\AbstractRestAPIController;
use App\Http\Controllers\Traits\DestroyTrait;
use App\Http\Controllers\Traits\EditTrait;
use App\Http\Controllers\Traits\IndexTrait;
use App\Http\Controllers\Traits\ShowTrait;
use App\Http\Controllers\Traits\StoreTrait;
use App\Http\Requests\IndexRequest;
use App\Http\Requests\BookingFormCreateRequest;
use App\Http\Requests\BookingFormUpdateRequest;
use App\Http\Resources\BookingFormResource;
use App\Http\Resources\BookingFormResourceCollection;
use App\Services\BookingFormService;

class BookingFormController extends AbstractRestAPIController
{
    use IndexTrait, ShowTrait, DestroyTrait, EditTrait;
    public function __construct(BookingFormService $service)
    {
        $this->service = $service;
        $this->resourceClass = BookingFormResource::class;
        $this->resourceCollectionClass = BookingFormResourceCollection::class;
        $this->indexRequest = IndexRequest::class;
        $this->editRequest = BookingFormUpdateRequest::class;
    }

    public function store(BookingFormCreateRequest $requestStore)
    {
        $data = [
            "user_uuid" => auth()->user()->getKey(),
            "bride" => $requestStore->bride,
            "groom" => $requestStore->groom,
            "map" => $requestStore->map,
            "bride_family_address" => $requestStore->bride_family_address,
            "bride_father_name" => $requestStore->bride_father_name,
            "bride_mother_name" => $requestStore->bride_mother_name,
            "groom_family_address" => $requestStore->groom_family_address,
            "groom_father_name" => $requestStore->groom_father_name,
            "groom_mother_name" => $requestStore->groom_mother_name,
            "wedding_date" => $requestStore->wedding_date,
            "party_date" => $requestStore->party_date,
            "party_name_place" => $requestStore->party_name_place,
            "party_address" => $requestStore->party_address,
            "image_design" => $requestStore->image_design,
            "product_uuid" => $requestStore->product_uuid,
        ];

        $bookingForm = $this->service->create(array_filter($data));

        return $this->sendOkJsonResponse($this->service->resourceToData($this->resourceClass, $bookingForm));
    }
}