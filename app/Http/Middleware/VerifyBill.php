<?php

namespace App\Http\Middleware;

use App\Models\ZmBill;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class VerifyBill
{
    
    /**
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!$request->return) {
            Log::error('Could not find the return on request');
            return $next($request);
        }

        $return = ZmBill::findOrFail($request->return);

        if ($this->verify($return)){
            return $next($request);
        }

        // Flag suspect of fraud, could be done from trait.
        $return->update(['is_suspected' => 1]);

        // Update request logs, need to create table
        //DB::table('request_logs')->where(['request_id'=>$request->REQUESTID])
        //    ->update(['message'=>'SUSPECTED FRAUD DETECTED, INVALID SIGNATURE WAS DETECTED','is_failed'=>1,'status_id'=>56]);

        Log::channel('verification')
            ->error('SUSPECTED FRAUD DETECTED, INVALID SIGNATURE WAS DETECTED --- Return '. $return->id, compact($return));

        return $next($request);
    }
}
