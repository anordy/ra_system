<?php

namespace App\Exceptions;

use App\Enum\CustomMessage;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Support\Facades\Log;
use Throwable;

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
       
    }

    public function render($request, Throwable $exception)
    {
        if ($exception instanceof TokenMismatchException) {
           return redirect()->route('login');
        }

        if ($exception instanceof ModelNotFoundException){
            $model = explode('\\', $exception->getModel());
            abort(404, end($model));
        }

        if ($exception instanceof DecryptException) {
            Log::error('DECRYPTION-FAILURE', [$exception]);
            abort(500, CustomMessage::ERROR);
        }

        if ($exception instanceof \DivisionByZeroError) {
            Log::error('DIVISION-BY-ZERO-ERROR', [$exception]);
            session()->flash('error', __('Possible Division By Zero Detected'));
            return back();
        }

        return parent::render($request, $exception);
    }
}
