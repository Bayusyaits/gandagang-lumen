<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        OAuthServerException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        if (app()->bound('sentry') && $this->shouldReport($exception)) {
            app('sentry')->captureException($exception);
        }
    
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Exception               $exception
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function render($request, Exception $exception)
    {
        if ($this->isHttpException($exception)) {
            switch ($exception->getStatusCode()) {
            // not authorized
                case '403':
                    return response()->json([
                'status'  => 'error',
                'code'    => 403,
                'message' => 'forbidden access',
                'data'    => null
                    ]);
                break;

            // not found
                case '404':
                    return response()->json([
                'status'  => 'error',
                'code'    => 404,
                'message' => 'not found',
                'data'    => null
                    ]);
                break;

            // internal error
                case '500':
                    return response()->json([
                'status'  => 'error',
                'code'    => 500,
                'message' => 'internal server error',
                'data'    => null
                    ]);
                break;

                default:
                    return $this->renderHttpException($exception);
                break;
            }
        }

        if ($exception  instanceof tokenInvalidException) {
            switch (get_class($exception)) {
                case OAuthServerException::class:
                    return response()->json(
                        [
                        'error' => $exception->getErrorType(),
                        'message' => $exception->getMessage(),
                        'hint' => $exception->getHint(),
                        ],
                        $exception->getHttpStatusCode()
                    );
                case TokenInvalidException::class:
                    return response()->json(
                        [
                        'error' => $exception->getErrorType(),
                        'message' => 'Token is invalid',
                        'hint' => $exception->getHint(),
                        ],
                        $exception->getHttpStatusCode()
                    );
                case TokenExpiredException::class:
                    return response()->json(
                        [
                        'error' => $exception->getErrorType(),
                        'message' => 'Token is expired',
                        'hint' => $exception->getHint(),
                        ],
                        400
                    );
                case JWTException::class:
                    return response()->json(
                        [
                        'error' => $exception->getErrorType(),
                        'message' => 'There is problem with your token',
                        'hint' => $exception->getHint(),
                        ],
                        400
                    );
                default:
                    return (parent::render($request, $exception));
            }
        }
    }
}
