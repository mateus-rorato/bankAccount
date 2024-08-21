<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    public function render($request, Throwable $exception)
    {
        if ($request->expectsJson()) {
            $status = 500;
            $message = $exception->getMessage();

            if ($exception instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
                $status = 404;
                $message = 'Resource not found';
            } elseif ($exception instanceof \Illuminate\Validation\ValidationException) {
                $status = 422;
                $message = $exception->validator->errors()->first();
            }

            return response()->json(['error' => $message], $status);
        }

        return parent::render($request, $exception);
    }
}
