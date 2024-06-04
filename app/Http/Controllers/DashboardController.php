<?php

namespace App\Http\Controllers;

use App\Abstracts\AbstractRestAPIController;
use App\Http\Requests\DasboardStatisticRequest;
use App\Http\Resources\DashboardResource;
use App\Http\Resources\DashboardResourceCollection;
use App\Services\OrderService;
use App\Services\UserService;

class DashboardController extends AbstractRestAPIController
{
    protected $userService;
    public function __construct(OrderService $service, UserService $userService)
    {
        $this->service = $service;
        $this->userService = $userService;
        $this->resourceClass = DashboardResource::class;
    }

    public function profitStatistics(DasboardStatisticRequest $request)
    {

        $profitStatistic = $this->service->profitStatistics($request);

        return $this->sendOkJsonResponse(
            $this->service->resourceCollectionToData($this->resourceClass, $this->service->resourceToData($this->resourceClass, $profitStatistic))
        );
    }

    public function userRegisterStatistics(DasboardStatisticRequest $request)
    {
        $userRegisterStatistics = $this->userService->userRegisterStatistics($request);

        return $this->sendOkJsonResponse(
            $this->service->resourceCollectionToData($this->resourceClass, $this->service->resourceToData($this->resourceClass, $userRegisterStatistics))
        );
    }
}