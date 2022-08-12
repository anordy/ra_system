<?php

namespace App\Http\Livewire\Returns\ExciseDuty;

use App\Models\Business;
use App\Models\BusinessStatus;
use App\Models\Returns\ExciseDuty\MnoReturn;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class MnoReturnSummary extends Component
{
    public $totalSubmittedReturns = [];
    public $totalPaidReturns =[];
    public $totalUnpaidReturns =[];
    public $totalLateFiledReturns = [];
    public $totalLatePaidReturns = [];

    public function mount(){
        $this->totalSubmittedReturns = MnoReturn::query()->whereNotNull('submitted_at')->count();

        //total paid returns
        $this->totalPaidReturns = MnoReturn::where('status','complete')->count();

        //total unpaid returns
        $this->totalUnpaidReturns = MnoReturn::where('status','!=','complete')->count();

        //late filed returns
        $this->totalLateFiledReturns = DB::table('mno_returns')
                    ->join('financial_months', 'mno_returns.financial_month_id','financial_months.id')
                    ->where('mno_returns.submitted_at','>','financial_months.due_date')
                    ->count();
        
        //total late paid returns
        $this->totalLatePaidReturns = DB::table('mno_returns')
                    ->join('zm_bills','mno_returns.id','zm_bills.billable_id')
                    ->join('zm_payments','zm_payments.zm_bill_id','zm_bills.id')
                    ->where('zm_bills.billable_type',MnoReturn::class)
                    ->where('mno_returns.status','complete')
                    ->where('mno_returns.submitted_at','>','zm_payments.trx_time')
                    ->count();
    }

    public function render()
    {
        return view('livewire.returns.excise-duty.mno.mno-return-summary');
    }
}
