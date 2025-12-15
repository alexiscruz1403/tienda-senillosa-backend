<?php

namespace App\Http\Responses;

class ApiResponse
{
    public static function success($data, $message = 'Operación exitosa', $code = 200){
        $response = ['message' => $message];
        if($data !== null){
            $response['data'] = $data;
        }
        return response()->json($response, $code);
    }

    public static function error($message = 'Error en la operación', $code = 500, $errors = null){
        $response = ['message' => $message];
        if($errors){
            $response['errors'] = $errors;
        }
        return response()->json($response, $code);
    }
}
