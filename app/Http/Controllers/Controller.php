<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\Storage;

use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @param int $status
     * @param null $message
     * @param null $data
     * @return \Illuminate\Http\JsonResponse
     */
    protected function responseJson($status = 200, $message = null, $data = null, $errors = []) {
        return response()->json([
            'status'        =>  $status,
            'message'       =>  $message,
            'response'      =>  [
                'data'      => $data,
                'errors'    => $errors
            ]
        ], $status);
    }

    protected function uploadFileToS3($dir, $file)
    {
        if($file){
            $path = Storage::put($dir, $file);
            return Storage::url($path);
        }
        return null;
    }
}
