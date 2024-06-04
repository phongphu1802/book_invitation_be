<?php

namespace App\Http\Controllers;

use App\Abstracts\AbstractRestAPIController;
use App\Http\Requests\ProductStatisticsRequest;
use App\Http\Resources\ProductStatisticsResource;
use App\Http\Resources\ProductStatisticsResourceCollection;
use App\Services\OrderDetailService;

class ProductStatisticController extends AbstractRestAPIController
{
    public function __construct(OrderDetailService $service)
    {
        $this->service = $service;
        $this->resourceClass = ProductStatisticsResource::class;
        $this->resourceCollectionClass = ProductStatisticsResourceCollection::class;
    }

    public function productStatistics(ProductStatisticsRequest $request)
    {
        $products = $this->service->productStatistics($request);
        return $this->sendOkJsonResponse(
            $this->service->resourceCollectionToData($this->resourceCollectionClass, $products)
        );
    }
}