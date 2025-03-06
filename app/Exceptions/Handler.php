<?php

namespace App\Exceptions;

use App\DTOs\JSONApiError;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Throwable;

class Handler extends ExceptionHandler
{
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

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $exception)
    {
        if (request()->segment(1) == 'api') {
            $code = $exception->getCode();
            $message = $exception->getMessage();
            if ($code < 100 || $code >= 600) {
                $code = ResponseAlias::HTTP_INTERNAL_SERVER_ERROR;
            }

            if ($exception instanceof ModelNotFoundException) {
                $message = $exception->getMessage();
                $code = ResponseAlias::HTTP_NOT_FOUND;

                if (preg_match('@\\\\(\w+)\]@', $message, $matches)) {
                    $model = $matches[1];
                    $model = preg_replace('/Table/i', '', $model);
                    $message = "{$model} not found.";
                }
            }

            if ($exception instanceof ValidationException) {
                $firstError = collect($exception->errors())->first();

                return response()->json(new JSONApiError([
                    'success' => false,
                    'message' => $firstError[0],
                ]), ResponseAlias::HTTP_UNPROCESSABLE_ENTITY);
            }

            if ($exception instanceof AuthenticationException) {
                return response()->json(new JSONApiError([
                    'success' => false,
                    'message' => 'Unauthenticated.',
                ]), ResponseAlias::HTTP_UNAUTHORIZED);
            }

            //        if ($exception instanceof ValidationException) {
            //            $validator = $exception->validator;
            //            $message = $validator->errors()->first();
            //            $code = \Illuminate\Http\Response::HTTP_UNPROCESSABLE_ENTITY;
            //
            //            if (! $request->expectsJson() and ! $request->isXmlHttpRequest()) {
            //                return Redirect::back()->withInput()->withErrors($message);
            //            }
            //        }

            if ($request->expectsJson() or $request->isXmlHttpRequest()) {
                return Response::json([
                    'success' => false,
                    'message' => $message,
                ], $code);
            }
        } else {
            $code = $exception->getCode();
            $message = $exception->getMessage();
            if ($code < 100 || $code >= 600) {
                $code = \Illuminate\Http\Response::HTTP_INTERNAL_SERVER_ERROR;
            }

            if ($exception instanceof ModelNotFoundException) {
                $message = $exception->getMessage();
                $code = \Illuminate\Http\Response::HTTP_NOT_FOUND;

                if (preg_match('@\\\\(\w+)\]@', $message, $matches)) {
                    $model = $matches[1];
                    $model = preg_replace('/Table/i', '', $model);
                    $message = "{$model} not found.";
                }
            }

            if ($exception instanceof ValidationException) {
                $validator = $exception->validator;
                $message = $validator->errors()->first();
                $code = \Illuminate\Http\Response::HTTP_UNPROCESSABLE_ENTITY;

                if (! $request->expectsJson() and ! $request->isXmlHttpRequest()) {
                    return Redirect::back()->withInput()->withErrors($message);
                }
            }

            if ($request->expectsJson() or $request->isXmlHttpRequest()) {
                return Response::json([
                    'success' => false,
                    'message' => $message,
                ], $code);
            }

            return parent::render($request, $exception);
        }
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse|ResponseAlias|void
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->isXmlHttpRequest() || $request->expectsJson()) {
            return response()->json(['error' => 'Unauthenticated.'], ResponseAlias::HTTP_UNAUTHORIZED);
        } elseif (! empty($request->all()['expires']) && ! empty($request->all()['signature'])) {

            if (! empty($request->route('hash'))) {

                $user = User::find($request->route('id'));
                $user->update(['email_verified_at' => Carbon::now()]);

                return redirect()->guest(route('login'));

            }
        } else {

            return redirect()->guest(route('login'));
        }
    }
}
