<?php

namespace App\Jobs\NonTaxResident;

use App\Mail\NonTaxResident\BusinessUpdateMail;
use App\Models\Ntr\NtrBusinessUpdate;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendBusinessUpdateMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    const SERVICE = 'ntr-business-update-mail';

    public $ntrBusinessUpdateId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($ntrBusinessUpdateId)
    {
        $this->ntrBusinessUpdateId = $ntrBusinessUpdateId;
    }


    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $updates = NtrBusinessUpdate::query()
                ->with(['business:id,name,country_id,ntr_business_category_id,ntr_taxpayer_id,street,status,vrn,ztn_number,ztn_location_number'])
                ->findOrFail($this->ntrBusinessUpdateId, ['id', 'ntr_business_id', 'ntr_taxpayer_id', 'current_business_info', 'current_contacts', 'current_socials', 'current_attachments', 'new_business_info', 'new_contacts', 'new_socials', 'new_attachments', 'created_at']);

            $formattedInfo = [
                'current_business_info' => json_decode($updates->current_business_info, true),
                'current_business_socials' => json_decode($updates->current_socials, true),
                'current_business_attachments' => json_decode($updates->current_attachments, true),
                'current_business_contacts' => json_decode($updates->current_contacts, true),
                'new_business_info' => json_decode($updates->new_business_info, true),
                'new_business_socials' => json_decode($updates->new_socials, true),
                'new_business_attachments' => json_decode($updates->new_attachments, true),
                'new_business_contacts' => json_decode($updates->new_contacts, true),
            ];

            $permission = Permission::query()
                ->select('id')
                ->where('name', 'non-tax-resident-view-business-updates')
                ->first();

            if ($permission) {
                $roles = DB::table('roles_permissions')
                    ->select('role_id')
                    ->where('permission_id', $permission->id)
                    ->distinct()
                    ->pluck('role_id')
                    ->toArray();

                $userEmails = User::query()
                    ->select('email')
                    ->whereIn('role_id', $roles)
                    ->pluck('email')
                    ->toArray();

                if (count($userEmails) > 0) {
                    Mail::to($userEmails)->send(new BusinessUpdateMail($formattedInfo, $updates->business->name ?? 'N/A'));
                }
            }
        } catch (\Exception $exception) {
            Log::error('NON-TAX-RESIDENT-SEND-BUSINESS-UPDATE-MAIL-JOB', [$exception]);
        }

    }
}
