<?php


use Exception;
use Carbon\Carbon;
use App\Models\Audit;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;

class AuditService 
{
    use Queueable;

    /**
     * Create a new audit instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Pass modal class.
     *
     * @param  mixed  $modal_class ie. User::class, Business::class
     * @return array
     */
    public function trigger($modal_class)
    {
        $data = [
            'auditable_id' => auth()->user()->id,
            'auditable_type' => $modal_class,
            'event'      => "logged in",
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
