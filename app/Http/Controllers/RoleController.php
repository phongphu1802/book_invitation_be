<?php

namespace App\Http\Controllers;

use App\Abstracts\AbstractRestAPIController;
use App\Http\Controllers\Traits\DestroyTrait;
use App\Http\Controllers\Traits\EditTrait;
use App\Http\Controllers\Traits\IndexTrait;
use App\Http\Controllers\Traits\ShowTrait;
use App\Http\Controllers\Traits\StoreTrait;
use App\Http\Requests\IndexRequest;
use App\Http\Requests\RoleCreateRequest;
use App\Http\Requests\RoleUpdateRequest;
use App\Http\Resources\RoleResource;
use App\Http\Resources\RoleResourceCollection;
use App\Services\RoleService;

class RoleController extends AbstractRestAPIController
{
    use IndexTrait, ShowTrait, StoreTrait, DestroyTrait, EditTrait;
    public function __construct(RoleService $service)
    {
        $this->service = $service;
        $this->resourceClass = RoleResource::class;
        $this->resourceCollectionClass = RoleResourceCollection::class;
        $this->indexRequest = IndexRequest::class;
        $this->editRequest = RoleUpdateRequest::class;
        $this->storeRequest = RoleCreateRequest::class;
    }
}
