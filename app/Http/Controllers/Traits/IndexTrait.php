<?php

namespace App\Http\Controllers\Traits;

use App\Http\Resources\UserResource;
use App\Http\Resources\UserResourceCollection;
use Illuminate\Http\JsonResponse;

trait IndexTrait
{
    /**
     * @return JsonResponse
     */
    public function index()
    {
        app($this->indexRequest);

        $models = $this->service->getCollectionWithPagination();

        return $this->sendOkJsonResponse(
            $this->service->resourceCollectionToData($this->resourceCollectionClass, $models)
        );
    }
}
