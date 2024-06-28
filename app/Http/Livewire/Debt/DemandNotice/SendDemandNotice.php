<?php

namespace App\Http\Livewire\Debt\DemandNotice;

use Exception;
use Carbon\Carbon;
use Livewire\Component;
use App\Models\Debts\Debt;
use App\Models\Debts\DemandNotice;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Traits\CustomAlert;

class SendDemandNotice extends Component
{

    use CustomAlert;

    public $debt;

    public function mount($debtId)
    {
        $this->debt = Debt::findOrFail(decrypt($debtId));
    }

    public function send()
    {
        DB::beginTransaction();
        try {
            $this->debt->demand_notice_count = $this->debt->demand_notice_count + 1;
            $this->debt->sent_demand_notice_date = Carbon::now();
            $this->debt->save();
            DemandNotice::create(['debt_id' => $this->debt->id, 'sent_by' => 'user']);
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            Log::error('Error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
            $this->customAlert('error', 'Something went wrong, please contact the administrator for help');
        }
    }

    public function render()
    {
        return view('livewire.debts.send-demand-notice');
    }
}
