<?php

namespace App\Exceptions;

use Exception;
use Http\Client\Exception\HttpException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\UnauthorizedException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
        if($request->wantsJson()){
            if ($exception instanceof NotFoundHttpException) {
                return response()->json(['error' => 'Resource not found.'], 404);
            }
            if ($exception instanceof AuthenticationException) {
                return response()->json(['error' => 'Authentication error.'], 401);
            }
            if ($exception instanceof MethodNotAllowedHttpException) {
                return response()->json(['error' => 'HTTP Method Not Allowed.'], 405);
            }
            if ($exception instanceof UnauthorizedException) {
                return response()->json(['error' => 'Sorry you do not have the right permission.'], 403);
            }
            if ($exception instanceof HttpException) {
                return response()->json(['error' => $exception->getMessage()], 403);
            }
            if($exception instanceof ValidationException){
                return response()->json(['error' => $exception->validator->errors()->all()], 422);
            }
        }
        return parent::render($request, $exception);
    }



    /**
     * Convert an authentication exception into an unauthenticated response.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Illuminate\Auth\AuthenticationException $exception
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        $guards = $exception->guards();
        $c_guard = $guards ? $guards[0] : '';
        if($c_guard == 'candidate')
            return $request->expectsJson()
                ? response()->json(['message' => $exception->getMessage()], 401)
                : redirect()->guest(route('candidate.candidatelogin'));

        return $request->expectsJson()
            ? response()->json(['message' => $exception->getMessage()], 401)
            : redirect()->guest(route('login'));
    }
}
