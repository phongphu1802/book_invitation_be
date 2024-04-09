<?php

namespace App\Http\Controllers;

use App\Abstracts\AbstractRestAPIController;
use App\Http\Controllers\Traits\DestroyTrait;
use App\Http\Controllers\Traits\EditTrait;
use App\Http\Controllers\Traits\IndexTrait;
use App\Http\Controllers\Traits\ShowTrait;
use App\Http\Controllers\Traits\StoreTrait;
use App\Http\Requests\IndexRequest;
use App\Http\Requests\ProductCreateRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Http\Requests\UploadRequest;
use App\Http\Resources\ProductResource;
use App\Http\Resources\ProductResourceCollection;
use App\Services\ProductService;

class ProductController extends AbstractRestAPIController
{
    use IndexTrait, ShowTrait, StoreTrait, DestroyTrait, EditTrait;
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
        //upload local
        $image = $request->file('image');
        $imageName = time().'.'.$image->getClientOriginalExtension();
        $image->move(public_path('images/products'), $imageName);

        $imageUrl = url(config('constants.upload_path.product_path') . $imageName);
        $model = $this->service->create(array_merge($request->all(), ['image' => $imageUrl]));

        return $this->sendCreatedJsonResponse(
            $this->service->resourceToData($this->resourceClass, $model)
        );
    }
}
