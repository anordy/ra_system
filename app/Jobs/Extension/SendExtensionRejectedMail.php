<?php

namespace App\Jobs\Extension;

use App\Mail\Extension\ExtensionRejected;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendExtensionRejectedMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    const SERVICE = 'extension-rejected';

    private $extension;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($extension)
    {
        $this->extension = $extension;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->extension->business->taxpayer->email) {
            Mail::to($this->extension->business->taxpayer->email)
                ->send(new ExtensionRejected($this->extension));
        }
    }
}
