<?php

namespace App\Exceptions;

use App\Http\Helpers\ServiceResponse;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\UnauthorizedException;
use Spatie\Permission\Exceptions\UnauthorizedException as ExceptionsUnauthorizedException;
use Symfony\Component\CssSelector\Exception\InternalErrorException;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
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

    public function render($request, Throwable $e)
    {
        if ($e instanceof AccessDeniedHttpException || $e instanceof AuthorizationException || $e instanceof ExceptionsUnauthorizedException) {
            $resp = new ServiceResponse($e->getMessage(), false, null);
            return response()->json($resp->getRepr(), JsonResponse::HTTP_FORBIDDEN);
        }

        if ($e instanceof NotFoundHttpException || $e instanceof ModelNotFoundException || $e instanceof FileNotFoundException) {
            $resp = new ServiceResponse($e->getMessage(), false, null);
            return response()->json($resp->getRepr(), JsonResponse::HTTP_NOT_FOUND);
        }

        if ($e instanceof UnauthorizedException || $e instanceof UnauthorizedHttpException) {
            $resp = new ServiceResponse($e->getMessage(), false, null);
            return response()->json($resp->getRepr(), JsonResponse::HTTP_UNAUTHORIZED);
        }

        if ($e instanceof BadRequestException) {
            $resp = new ServiceResponse($e->getMessage(), false, null);
            return response()->json($resp->getRepr(), JsonResponse::HTTP_BAD_REQUEST);
        }

        if ($e instanceof Exception) {
            $resp = new ServiceResponse($e->getMessage(), false, null);
            return response()->json($resp->getRepr(), JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }

        return parent::render($request, $e);
    }
}
