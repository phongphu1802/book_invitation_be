<?php

namespace App\Http\Controllers;

use App\Abstracts\AbstractRestAPIController;
use App\Http\Requests\UploadRequest;

class UploadController extends AbstractRestAPIController
{
    public function upload(UploadRequest $request)
    {
        //upload local
        $image = $request->file('image');
        $imageName = $image->getFilename() . time() . '.' . $image->getClientOriginalExtension();
        $type = $request->get('type');
        $uploadPath = config('constants.upload_path.' . ($type ?: 'system'));
        $image->move(public_path($uploadPath), $imageName);
        $imageUrl = url($uploadPath . $imageName);

        return $this->sendOkJsonResponse(['data' => ['absolute_url' => $imageUrl, 'url' => $uploadPath . $imageName]]);
    }
}