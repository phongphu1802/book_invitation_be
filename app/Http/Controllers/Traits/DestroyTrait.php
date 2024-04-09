<?php

namespace App\Http\Controllers\Traits;

use Illuminate\Http\JsonResponse;

trait DestroyTrait
{
    /**
     * @param $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        $this->service->destroy($id);

        return $this->sendOkJsonResponse();
    }
}
