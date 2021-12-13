<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;

use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var string[]
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var string[]
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /* Convert a validation exception into a JSON response.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \Illuminate\Validation\ValidationException  $exception
    * @return \Illuminate\Http\JsonResponse
    */
    protected function invalidJson($request, ValidationException $exception)
    {
        return response()->json([
            'message' => __('Os dados enviados são inválidos.'),
            'errors' => $exception->errors(),
        ], $exception->status);
    }
}
