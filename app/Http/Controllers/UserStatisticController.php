<?php

namespace App\Http\Controllers;

use App\Abstracts\AbstractRestAPIController;
use App\Http\Requests\UserStatisticRequest;
use App\Http\Resources\ProductStatisticsResource;
use App\Http\Resources\ProductStatisticsResourceCollection;
use App\Http\Resources\UserStatisticResource;
use App\Http\Resources\UserStatisticResourceCollection;
use App\Services\OrderService;

class UserStatisticController extends AbstractRestAPIController
{
    public function __construct(OrderService $service)
    {
        $this->service = $service;
        $this->resourceClass = UserStatisticResource::class;
        $this->resourceCollectionClass = UserStatisticResourceCollection::class;
    }

    public function userStatistics(UserStatisticRequest $request)
    {
        $users = $this->service->userStatistics($request);

        return $this->sendOkJsonResponse(
            $this->service->resourceCollectionToData($this->resourceCollectionClass, $users)
        );
    }
}