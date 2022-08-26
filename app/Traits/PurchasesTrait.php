<?php

namespace App\Traits;

use Exception;
use Carbon\Carbon;
use App\Models\Audit;
use Illuminate\Support\Facades\Log;

trait PurchasesTrait
{
    public function triggerAudit($modal_class, $event, $tag, $auditable_id, $old_values, $new_values)
    {
        $data = [
            'auditable_id' => $auditable_id,
            'auditable_type' => $modal_class,
            'event' => $event,
            'tags' => $tag,
            'old_values' => json_encode($old_values),
            'new_values' => json_encode($new_values),
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
