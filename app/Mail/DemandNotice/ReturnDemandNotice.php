<?php

namespace App\Mail\DemandNotice;

use App\Models\Debts\DemandNotice;
use App\Models\Returns\TaxReturn;
use PDF;
use Carbon\Carbon;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReturnDemandNotice extends Mailable
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
        $paid_within_days = 30;
        DB::beginTransaction();
        try {
            DemandNotice::create([
                'debt_id' => $this->payload['return']->id,
                'debt_type' => TaxReturn::class,
                'sent_by' => 'job',
                'sent_on' => Carbon::today(),
                'category' => 'normal',
                'paid_within_days' => $paid_within_days
            ]);
            DB::commit();

            $tax_return = $this->payload['return'];
    
            $now = Carbon::now()->format('d M Y');
    
            $pdf = PDF::loadView('debts.demand-notice.return-demand-notice', compact('tax_return', 'now', 'paid_within_days'));
    
            $pdf->setPaper('a4', 'portrait');
            $pdf->setOption(['dpi' => 150, 'defaultFont' => 'sans-serif']);
    
            $email = $this->markdown('emails.demand-notice.return-demand-notice')->subject("Zanzibar Revenue Authority(ZRA) Return Debt Demand Notice - " . strtoupper($tax_return->business->name));
            $email->attachData($pdf->output(), "{$tax_return->business->name}_demand_notice.pdf");
            return $email;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
        }
   

    }
}
