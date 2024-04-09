<?php

namespace App\Http\Controllers;

use App\Abstracts\AbstractRestAPIController;
use App\Http\Controllers\Traits\IndexTrait;
use App\Http\Controllers\Traits\ShowTrait;
use App\Http\Resources\OrderDetailResourceCollection;
use App\Services\OrderDetailService;
use App\Http\Requests\IndexRequest;
use App\Http\Resources\OrderDetailResource;

class OrderDetailController extends AbstractRestAPIController
{
    use IndexTrait, ShowTrait;
    public function __construct(OrderDetailService $service)
    {
        $this->service = $service;
        $this->indexRequest = IndexRequest::class;
        $this->resourceCollectionClass = OrderDetailResourceCollection::class;
        $this->resourceClass = OrderDetailResource::class;
    }
}
