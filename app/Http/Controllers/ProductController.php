<?php

namespace App\Http\Controllers;

use App\Abstracts\AbstractRestAPIController;
use App\Http\Controllers\Traits\DestroyTrait;
use App\Http\Controllers\Traits\IndexTrait;
use App\Http\Controllers\Traits\ShowTrait;
use App\Http\Requests\IndexRequest;
use App\Http\Requests\ProductCreateRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Http\Resources\ProductResource;
use App\Http\Resources\ProductResourceCollection;
use App\Services\ProductService;

class ProductController extends AbstractRestAPIController
{
    use IndexTrait, ShowTrait, DestroyTrait;
    public function __construct(ProductService $service)
    {
        $this->service = $service;
        $this->resourceClass = ProductResource::class;
        $this->resourceCollectionClass = ProductResourceCollection::class;
        $this->indexRequest = IndexRequest::class;
        $this->editRequest = ProductUpdateRequest::class;
        $this->storeRequest = ProductCreateRequest::class;
    }

    public function store(ProductCreateRequest $request)
    {
        $data = [
            "category_uuid" => $request->category_uuid,
            "name" => $request->name,
            "description" => $request->description,
            "price" => $request->price,
            "image" => $request->image,
        ];

        $data["detail_images"] = json_encode($request->detail_images);

        $model = $this->service->create(array_filter($data));

        return $this->sendCreatedJsonResponse(
            $this->service->resourceToData($this->resourceClass, $model)
        );
    }

    public function edit(ProductUpdateRequest $request)
    {
        $model = $this->service->findOrFailById($request->id);

        $data = [
            "category_uuid" => $request->category_uuid,
            "name" => $request->name,
            "description" => $request->description,
            "price" => $request->price,
            "image" => $request->image,
        ];

        if ($request->detail_images)
            $data["detail_images"] = json_encode($request->detail_images);

        $this->service->update($model, array_filter($data));

        return $this->sendCreatedJsonResponse(
            $this->service->resourceToData($this->resourceClass, $model)
        );
    }
}