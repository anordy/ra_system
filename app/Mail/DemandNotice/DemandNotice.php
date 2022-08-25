<?php

namespace App\Mail\DemandNotice;

use App\Models\Debts\SentDemandNotice;
use Carbon\Carbon;
use PDF;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DemandNotice extends Mailable
{
    use Queueable, SerializesModels;

    public $payload, $debt, $paid_within_days;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($payload)
    {
        $this->payload = $payload;
        $this->debt = $payload['debt'];
        $this->paid_within_days = $payload['paid_within_days'];
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $debt = $this->debt;
        $this->debt->demand_notice_count = $this->debt->demand_notice_count + 1;
        $this->debt->next_demand_notice_date = Carbon::now()->addDays($this->payload['next_notify_date']);
        $this->debt->save();
        SentDemandNotice::create(['debt_id' => $this->debt->id, 'sent_by' => 'job']);
        $now = Carbon::now()->format('d M Y');
        $paid_within_days = $this->paid_within_days;
        $pdf = PDF::loadView('debts.demand-notice.demand-notice', compact('debt', 'now', 'paid_within_days'));

        $pdf->setPaper('a4', 'portrait');
        $pdf->setOption(['dpi' => 150, 'defaultFont' => 'sans-serif']);

        $email = $this->markdown('emails.demand-notice.demand-notice')->subject("ZRB Demand Notice for Debts - " . strtoupper($this->payload['debt']->business->name));
        $email->attachData($pdf->output(), "{$this->payload['debt']->business->name}_demand_notice.pdf");
        return $email;

    }
}
