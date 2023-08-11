<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Session;

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
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        if ($this->shouldReport($exception) && app()->bound('sentry')) {
            app('sentry')->captureException($exception);
        }
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        if ($exception instanceof \Illuminate\Session\TokenMismatchException) {
            Session::flash('fail_msg','Token expired. Please try again.');
            return back()
            ->withInput(request()->except('_token'));
        }

        if ($exception instanceof \Spatie\Permission\Exceptions\UnauthorizedException && $exception->getStatusCode() == 403) {
            if ($exception->getMessage() == "User is not logged in.") {
                return redirect()->route('login', ['tenant' => tenant('id')]);
            }
            elseif ($exception->getMessage() == "User does not have the right permissions.") {
                Session::flash('fail_msg','Permission denied.');
                return redirect()->route('dashboard', ['tenant' => tenant('id')]);
            }
        }

        return parent::render($request, $exception);
    }
}
