<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Response;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function sendError($message="", $code = 500){
        return Response::make([
            "success" => false,
            "message" => $message
        ], $code);
    }

    public function sendRessponse($data =null, $message="", $code = 200){
        return Response::make([
            "success" => true,
            "message" => $message,
            "data" => $data
        ], $code);
    }
}
