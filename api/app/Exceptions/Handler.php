<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Illuminate\Database\QueryException;
use App\Traits\ApiResponser;
class Handler extends ExceptionHandler
{
    use ApiResponser;
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
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
        if($exception instanceof ModelNotFoundException){
            $modelName = strtolower(class_basename($exception->getModel()));
            $modelName = substr($modelName,2);

            return $this->trueResponse("Does not exists any {$modelName} with the specified identificator", 404);
        }

        if($exception instanceof UnauthorizedHttpException){
            $res = [
                'code'          => 401,
                'redirect'      => true,
                'description'   => 'The request has not been applied!'
            ];
            return response()->json($res,401);
            // return $this->trueResponse("The request has not been applied!",401);
        }

        if($exception instanceof NotFoundHttpException){
            return $this->trueResponse("Url not valid",404);
        }

        if($exception instanceof MethodNotAllowedHttpException){
            return $this->trueResponse("The specified method for the request is invalid",405);
        }
        if($exception instanceof AuthorizationException){
            return $this->trueResponse($exception->getMessage(), 403);
        }

        return parent::render($request, $exception);
    }
}
