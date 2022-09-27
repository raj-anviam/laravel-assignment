<?php
namespace App\Traits;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

trait ApiResponseTrait {

    // error response would have same format across the project, parameters can be changed accordingly
    public function errorResponse($error = null, $code = 404, $message = 'Some error has been occurred', $status = false, $data = []) {
        return response()->json([
            'message'  => $error,
            'status' => $status,
            'error' => $message,
            'data'    => $data
        ], $code);
    }
    
    // success response would have same format across the project, parameters can be changed accordingly
    public function successResponse($data = [], $code = Response::HTTP_OK, $status = true, $message = 'Record found successfully') {
        return response()->json([
            'error'  => '',
            'status' => $status,
            'message' => $message,
            'data'    => $data
        ], $code);
    }
}