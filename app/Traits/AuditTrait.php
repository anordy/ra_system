<?php

namespace App\Traits;

use Exception;
use Carbon\Carbon;
use App\Models\Audit;
use Illuminate\Support\Facades\Log;

trait AuditTrait
{

    /**
     * Trigger audit.
     *
     * @param  $modal_class ie. User::class, Business::class
     * @param  $event ie. created, updated, deleted
     * @param  $tags ie. Password
     * @param  $auditable_id ie. 1 id of the operated process
     * 
     * @return array
     */
    public function triggerAudit($modal_class, $event, $tags, $auditable_id, $user_name)
    {
        $data = [
            'auditable_id' => $auditable_id,
            'auditable_type' => $modal_class,
            'event' => $event,
            'tags' => $tags,
            'new_values' => $user_name,
            'url'        => request()->fullUrl(),
            'ip_address' => request()->getClientIp(),
            'user_agent' => request()->userAgent(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'user_id'    => auth()->user()->id,
        ];

        try {
            Audit::create($data);
        } catch(Exception $e){
            Log::error($e);
        }
    }
}
