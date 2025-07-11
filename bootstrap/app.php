<?php

use Illuminate\Http\Response;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Validation\ValidationException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
        then: function () {
            Route::middleware(['auth:api'])->group(function () {
                Route::prefix('api/visits')->group(__DIR__ . '/../routes/api/visit.php');
            });
        }
    )
    ->withMiddleware(function (Middleware $middleware): void {
   
    })

    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->dontReport([
            \Laravel\Passport\Exceptions\OAuthServerException::class,
            \League\OAuth2\Server\Exception\OAuthServerException::class,
            \Illuminate\Auth\Access\AuthorizationException::class,
            ModelNotFoundException::class,
            ValidationException::class,
            HttpException::class,
        ]);
        $exceptions->renderable(function (Throwable $exception, $request) {
            if ($exception instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException) {
                $result = ['success' => false, 'http_code' => Response::HTTP_NOT_FOUND, 'error_code' => null, 'errors' => null, 'message' => __('messages.not_found')];
                return response()->json($result, $result['http_code']);
            }

            if ($exception instanceof HttpException) {
                $code = $exception->getStatusCode();
                $message = Response::$statusTexts[$code];

                $result = ['success' => false, 'http_code' => $code, 'error_code' => null, 'errors' => null, 'message' => $message];
                return response()->json($result, $result['http_code']);
            }

            if ($exception instanceof ModelNotFoundException) {
                $model = strtolower(class_basename($exception->getModel()));

                $result = ['success' => false, 'http_code' => Response::HTTP_NOT_FOUND, 'error_code' => null, 'errors' => null, 'message' => "Does not exist any instance of {$model} with the given id"];
                return response()->json($result, $result['http_code']);
            }

            if ($exception instanceof ValidationException) {
                $message = $exception->validator->errors()->getMessages();

                $result = ['success' => false, 'http_code' => Response::HTTP_UNPROCESSABLE_ENTITY, 'error_code' => null, 'errors' => null, 'message' => $message];
                return response()->json($result, $result['http_code']);
            }

            if ($exception instanceof ClientException) {
                $message = $exception->getResponse()->getBody();
                $code = $exception->getCode();

                $result = ['success' => false, 'http_code' => $code, 'error_code' => null, 'errors' => null, 'message' => $message];
                return response()->json($result, $result['http_code']);
            }

            //            $result = ['success' => false, 'http_code' => Response::HTTP_INTERNAL_SERVER_ERROR, 'error_code' => null, 'errors' => null, 'message'=> 'Unexpected error. Try later'];
            //            return response()->json($result, $result['http_code']);
        });
    })->create();
