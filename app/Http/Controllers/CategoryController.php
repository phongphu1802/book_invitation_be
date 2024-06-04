<?php

namespace App\Http\Controllers;

use App\Abstracts\AbstractRestAPIController;
use App\Http\Controllers\Traits\DestroyTrait;
use App\Http\Controllers\Traits\EditTrait;
use App\Http\Controllers\Traits\IndexTrait;
use App\Http\Controllers\Traits\ShowTrait;
use App\Http\Controllers\Traits\StoreTrait;
use App\Http\Requests\CategoryIndexAccessRequest;
use App\Http\Requests\IndexRequest;
use App\Http\Requests\CategoryCreateRequest;
use App\Http\Requests\CategoryUpdateRequest;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\CategoryResourceCollection;
use App\Models\Category;
use App\Services\CategoryService;
use Illuminate\Support\Facades\Request;

class CategoryController extends AbstractRestAPIController
{
    use ShowTrait, StoreTrait, EditTrait;
    public function __construct(CategoryService $service)
    {
        $this->service = $service;
        $this->resourceClass = CategoryResource::class;
        $this->resourceCollectionClass = CategoryResourceCollection::class;
        $this->indexRequest = IndexRequest::class;
        $this->editRequest = CategoryUpdateRequest::class;
        $this->storeRequest = CategoryCreateRequest::class;
    }

    public function index(IndexRequest $request)
    {
        $expand = Request::get('expand', []);

        if (in_array('category__root', $expand)) {
            $categories = $this->service->getCollectionWithPaginationByCondition($request, ['parent_uuid' => null]);
        } else {
            $categories = $this->service->getCollectionWithPagination();
        }

        return $this->sendOkJsonResponse(
            $this->service->resourceCollectionToData($this->resourceCollectionClass, $categories)
        );
    }

    public function indexAccess(CategoryIndexAccessRequest $request)
    {
        $category = $this->service->findOrFailById($request->get('category_uuid'));

        $categoryAccessDenied = array_merge($category->getAllChildrenUUIDs(), [$request->get('category_uuid')]);

        $categories = $this->service->getCollectionWithPaginationWhereNotIn($request, null, $categoryAccessDenied);

        return $this->sendOkJsonResponse(
            $this->service->resourceCollectionToData($this->resourceCollectionClass, $categories)
        );
    }

    public function destroy($id)
    {
        $categorys = $this->service->findAllWhere(['parent_uuid' => $id]);

        foreach ($categorys as $category) {
            $this->service->update($category, ['parent_uuid' => null]);
        }

        $this->service->destroy($id);

        return $this->sendOkJsonResponse();
    }
}