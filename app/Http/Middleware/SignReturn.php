<?php

namespace App\Http\Middleware;

use App\Jobs\RepostReturnSignature;
use App\Models\Returns\TaxReturn;
use App\Traits\VerificationTrait;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SignReturn
{
    use VerificationTrait;

    /**
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        return $next($request);
    }

    /**
     * @param $request
     * @param $response
     * @return bool|void
     */
    public function terminate($request, $response)
    {
        try {

            Log::info('SIGN-RETURN-START', ['MESSAGE' => $response]);

            if (!$request->return) {
                Log::error('Could not find the return on request');
                return true;
            }

            $return = TaxReturn::findOrFail($request->return);

            if ($this->verify($return)) {
                if (!$this->sign($return)) {
                    Log::info('FAILED-TO-UPDATE-SECURE-DATA', ['MESSAGE' => ['return' => $request->return, 'RESPONSE' => $response, 'MESSAGE' => 'FAILED']]);
                    dispatch(new RepostReturnSignature($request->return));
                    return false;
                }
                return true;
            }

            Log::info('FAILED-TO-OBTAIN-SECURE-DATA', ['MESSAGE' => ['return' => $request->return, 'RESPONSE' => $response, 'MESSAGE' => 'FAILED']]);
            dispatch(new RepostReturnSignature($request->return));
            return false;

        } catch (\Throwable $exception) {
            Log::error('SIGN-EXCEPTION', ['MESSAGE' => $exception]);
            DB::table('tax_returns')->where(['id' => $request->return])->update(['failed_verification' => 1]);
            dispatch(new RepostReturnSignature($request->return));
        }
    }
}
