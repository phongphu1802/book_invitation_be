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
    use IndexTrait, ShowTrait, StoreTrait, DestroyTrait, EditTrait;
    public function __construct(BookingFormService $service)
    {
        $this->service = $service;
        $this->resourceClass = BookingFormResource::class;
        $this->resourceCollectionClass = BookingFormResourceCollection::class;
        $this->indexRequest = IndexRequest::class;
        $this->editRequest = BookingFormUpdateRequest::class;
        $this->storeRequest = BookingFormCreateRequest::class;
    }
}