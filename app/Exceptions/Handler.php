<?php

namespace App\Exceptions;

use App\Helpers\APIResponse;
use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Log;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    private function exceptRequiredInfo(Exception $e): array
    {
        return [
            'code' => $e->getCode(),
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'stacktrace' => $e->getTraceAsString(),
        ];
    }
    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (AuthenticationException $e) {
            Log::channel('singleError')->error(
                'AuthenticationException caught',
                $this->exceptRequiredInfo($e)
            );
        })->stop();

        $this->renderable(function (AuthenticationException $e) {

            return response()->json(
                APIResponse::formatJSONPayload('fail', ['authentication' => $e->getMessage()]),
                401
            );
        });

        $this->reportable(function (AppForbiddenException $e) {
            Log::channel('singleError')->error(
                'AppForbiddenException caught',
                $this->exceptRequiredInfo($e)
            );
        })->stop();

        $this->renderable(function (AppForbiddenException $e) {
            return response()->json(
                APIResponse::formatJSONPayload('fail', ['forbidden' => $e->getMessage()]),
                403
            );
        });

        $this->reportable(function (AppValidationException $e) {
            Log::channel('singleError')->error(
                'AppValidationException caught',
                $this->exceptRequiredInfo($e)
            );
        })->stop();

        $this->renderable(function (AppValidationException $e) {
            return response()->json(
                APIResponse::formatJSONPayload('fail', $e->getErrors()),
                400
            );
        });

        $this->reportable(function (Exception $e) {
            Log::channel('singleCritical')->error(
                'Exception caught',
                $this->exceptRequiredInfo($e)
            );
        });

        $this->renderable(function (Exception $e) {
            return response()->json(
                APIResponse::formatJSONPayload(
                    'error',
                    $e->getMessage()
                ),
                500
            );
        });
    }
}
