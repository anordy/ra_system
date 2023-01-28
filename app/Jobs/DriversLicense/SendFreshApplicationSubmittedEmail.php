<?php

namespace App\Jobs\DriversLicense;

use App\Mail\TaxAget\TaxAgentApproval;
use App\Mail\TaxClearance\TaxClearanceRejected;
use App\Models\DlLicenseApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendFreshApplicationSubmittedEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($application_id)
    {
        $this->application_id = $application_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $application = DlLicenseApplication::query()->find($this->application_id);

        if (!is_null($application) && $application->taxpayer->email) {
            Mail::to($application->taxpayer->email)->send(new class($application) extends Mailable
            {
                use Queueable, SerializesModels;
                private $application;
    
                public function __construct($application)
                {
                    $this->application = $application;
                }
    
                public function build()
                {
                    return $this->markdown('emails.drivers-license.application-submitted',['application'=>$this->application])
                        ->subject('Drivers License Application');
                }
            });
        }
    }
}
