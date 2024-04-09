<?php

namespace App\Http\Controllers;

use App\Abstracts\AbstractRestAPIController;
use App\Http\Controllers\Traits\DestroyTrait;
use App\Http\Controllers\Traits\EditTrait;
use App\Http\Controllers\Traits\IndexTrait;
use App\Http\Controllers\Traits\ShowTrait;
use App\Http\Controllers\Traits\StoreTrait;
use App\Http\Requests\IndexRequest;
use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Http\Resources\UserResourceCollection;
use App\Services\UserService;

class UserController extends AbstractRestAPIController
{
    use IndexTrait, ShowTrait, StoreTrait, DestroyTrait, EditTrait;
    public function __construct(UserService $service)
    {
        $this->service = $service;
        $this->resourceClass = UserResource::class;
        $this->resourceCollectionClass = UserResourceCollection::class;
        $this->indexRequest = IndexRequest::class;
        $this->editRequest = UserUpdateRequest::class;
        $this->storeRequest = UserCreateRequest::class;
    }
}
