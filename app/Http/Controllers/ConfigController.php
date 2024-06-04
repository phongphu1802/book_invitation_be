<?php

namespace App\Http\Controllers;

use App\Abstracts\AbstractRestAPIController;
use App\Enums\ConfigEnum;
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
    use IndexTrait, ShowTrait, DestroyTrait;
    public function __construct(ConfigService $service)
    {
        $this->service = $service;
        $this->resourceClass = ConfigResource::class;
        $this->resourceCollectionClass = ConfigResourceCollection::class;
        $this->indexRequest = IndexRequest::class;
        $this->editRequest = ConfigUpdateRequest::class;
        $this->storeRequest = ConfigCreateRequest::class;
    }

    public function store(ConfigCreateRequest $request)
    {
        $data = [
            "key" => $request->key,
            "description" => $request->description,
            "type" => $request->type,
            "datatype" => $request->datatype,
        ];

        if ($request->datatype == ConfigEnum::IMAGES->value) {
            $data["value"] = json_encode($request->value);
        } else {
            $data["value"] = $request->value;
        }

        $config = $this->service->create(array_filter($data, function ($value) {
            return !is_null($value);
        }));

        return $this->sendOkJsonResponse($this->service->resourceToData($this->resourceClass, $config));
    }

    public function edit(ConfigUpdateRequest $request)
    {

        $model = $this->service->findOrFailById($request->id);

        $data = [
            "key" => $request->key,
            "description" => $request->description,
            "type" => $request->type,
            "datatype" => $request->datatype,
        ];

        if (($request->datatype && $request->datatype == ConfigEnum::IMAGES->value) || $model->datatype == ConfigEnum::IMAGES->value) {
            $data["value"] = json_encode($request->value);
        } else {
            $data["value"] = $request->value;
        }

        $this->service->update($model, array_filter($data, function ($value) {
            return !is_null($value);
        }));

        return $this->sendOkJsonResponse($this->service->resourceToData($this->resourceClass, $model));
    }
}