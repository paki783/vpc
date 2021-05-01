<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use App\Helpers\Helper;
use stdClass;
use Illuminate\Support\Facades\Log;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        $data = new stdClass;
        if ($exception instanceof UnauthorizedHttpException) {
            $preException = $exception->getPrevious();
            if ($preException instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
                return Helper::errorResponse(401, 'Token Expired.', $data);
                // return response()->json(['error' => 'TOKEN_EXPIRED']);
            } else if ($preException instanceof
                \Tymon\JWTAuth\Exceptions\TokenInvalidException
            ) {
                return Helper::errorResponse(401, 'Token Invalid.', $data);
                // return response()->json(['error' => 'TOKEN_INVALID']);
            } else if ($preException instanceof
                \Tymon\JWTAuth\Exceptions\TokenBlacklistedException
            ) {
                return Helper::errorResponse(401, 'Token Black Listed.', $data);
                // return response()->json(['error' => 'TOKEN_BLACKLISTED']);
            }
            if ($exception->getMessage() === 'Token not provided') {
                return Helper::errorResponse(401, 'Token Not Provided.', ['Token not provided.']);
                // return response()->json(['error' => 'Token not provided']);
            }
        }

        if ($exception instanceof \Spatie\Permission\Exceptions\UnauthorizedException) {
            // Code here ...
            return Helper::errorResponse(401, 'User does not have the right permissions.', $data);
        }
        // if (\Request::is('api/*')) {
            // return Helper::errorResponse(401, 'Something Went Wrong due to some system failure try again later.', $exception);
        // } else {
            return parent::render($request, $exception);
        // }
    }
}
