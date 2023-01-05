<?php

namespace App\Mail\DemandNotice;

use PDF;
use Exception;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use App\Models\Returns\TaxReturn;
use App\Models\Debts\DemandNotice;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use App\Models\TaxAssessments\TaxAssessment;
use Illuminate\Support\Facades\DB;

class DebtDemandNotice extends Mailable
{
    use Queueable, SerializesModels;

    public $payload;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($payload)
    {
        $this->payload = $payload;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $debt = $this->payload['debt'];
        DB::beginTransaction();
        try {
            DemandNotice::create([
                'debt_id' => $this->payload['debt']->id,
                'debt_type' => get_class($debt),
                'sent_by' => 'job',
                'sent_on' => Carbon::now(),
                'category' => 'debt',
                'paid_within_days' => $this->payload['paid_within_days'],
                'next_notify_days' => $this->payload['next_notify_days'],
                'next_notify_date' => Carbon::today()->addDays($this->payload['next_notify_days'])
            ]);
            DB::commit();
    
            $now = Carbon::now()->format('d M Y');
            $paid_within_days = $this->payload['paid_within_days'];
    
            if (get_class($debt) === TaxReturn::class) {
                $tax_return = $this->payload['debt'];
                $pdf = PDF::loadView('debts.demand-notice.return-demand-notice', compact('tax_return', 'now', 'paid_within_days'));

                $pdf->setPaper('a4', 'portrait');
                $pdf->setOption(['dpi' => 150, 'defaultFont' => 'sans-serif']);
        
                $email = $this->markdown('emails.demand-notice.demand-notice')->subject("ZRB Demand Notice for Debt - " . strtoupper($this->payload['debt']->business->name));
                $email->attachData($pdf->output(), "{$this->payload['debt']->business->name}_demand_notice.pdf");
                return $email;
            } else if (get_class($debt) === TaxAssessment::class) {
                $pdf = PDF::loadView('debts.demand-notice.assessment-demand-notice', compact('debt', 'now', 'paid_within_days'));
    
                $pdf->setPaper('a4', 'portrait');
                $pdf->setOption(['dpi' => 150, 'defaultFont' => 'sans-serif']);
        
                $email = $this->markdown('emails.demand-notice.demand-notice')->subject("ZRB Demand Notice for Debt - " . strtoupper($this->payload['debt']->business->name));
                $email->attachData($pdf->output(), "{$this->payload['debt']->business->name}_demand_notice.pdf");
                return $email;
            }
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
        }

    }
}
