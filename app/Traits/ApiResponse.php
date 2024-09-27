<?php

namespace App\Traits;

use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

trait ApiResponse {

    protected function successResponse($data, $message = null, $code = 200)
	{
		return response()->json([
			'status_code'=> $code,
			'message' => $message,
			'data' => $data
		],Response::HTTP_OK);
	}

	protected function errorResponse($message = null, $code,$errors=[])
	{
		return response()->json([
			'status_code'=>$code,
			'message' => $message,
            'errors'=>$errors,
			'data' => null
		],Response::HTTP_OK);
	}

    public function validationFailed($errors){
        $errors = array_values($errors);
        $errors = call_user_func_array('array_merge', $errors);
        $message=Arr::first($errors);
        return $this->errorResponse($message,Response::HTTP_UNPROCESSABLE_ENTITY,$errors);
    }

    public function serverException(\Throwable $exception){
        $more_info=[];
        if(config('app.debug')){
            $more_info=[
                'message' => $exception->getMessage(),
                'line' => $exception->getLine(),
                'file' => $exception->getFile(),
                'trace' => $exception->getTraceAsString()
            ];
        }
        $error = [
            'status' => 'server_error',
            'status_code' => Response::HTTP_INTERNAL_SERVER_ERROR,
            'message' => "Something went wrong! We are working hard to fix it",
            'more_info' => $more_info
        ];
        Log::error($exception->getMessage(),$error);
        return response()->json($error,Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
