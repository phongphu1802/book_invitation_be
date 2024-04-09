<?php

namespace App\Http\Controllers;

use App\Abstracts\AbstractRestAPIController;
use App\Http\Controllers\Traits\DestroyTrait;
use App\Http\Controllers\Traits\EditTrait;
use App\Http\Controllers\Traits\IndexTrait;
use App\Http\Controllers\Traits\ShowTrait;
use App\Http\Controllers\Traits\StoreTrait;
use App\Http\Requests\IndexRequest;
use App\Http\Requests\ConfigCreateRequest;
use App\Http\Requests\ConfigUpdateRequest;
use App\Http\Resources\ConfigResource;
use App\Http\Resources\ConfigResourceCollection;
use App\Services\ConfigService;

class ConfigController extends AbstractRestAPIController
{
    use IndexTrait, ShowTrait, StoreTrait, DestroyTrait, EditTrait;
    public function __construct(ConfigService $service)
    {
        $this->service = $service;
        $this->resourceClass = ConfigResource::class;
        $this->resourceCollectionClass = ConfigResourceCollection::class;
        $this->indexRequest = IndexRequest::class;
        $this->editRequest = ConfigUpdateRequest::class;
        $this->storeRequest = ConfigCreateRequest::class;
    }
}