<?php

namespace App\Http\Livewire\Payments;

use Exception;
use Carbon\Carbon;
use App\Models\ZmRecon;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Gate;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use App\Services\Api\ZanMalipoInternalService;

class RequestRecon extends Component
{
    use LivewireAlert;

    public $today;
    public $transaction_date, $recon_type;

    protected $listeners = [
        'submit'
    ];

    protected function rules(){
        return [
            'recon_type' => 'required',
            'transaction_date' => [
                'required',
                function ($attribute, $value, $fail) {
                    $yesterday = Carbon::now()->subDay();
                    $transaction_date = Carbon::parse($value);
                   $diff_date = $transaction_date->diffInDays($yesterday);
                    if($diff_date  >= 7) {
                        $fail('You cant request reconciliation for this date');
                    }
                }
            ]
        ];
    }

    public function mount()
    {
        $this->today = Carbon::today()->format('Y-m-d');
    }

    public function triggerAction() {
        $this->validate();

        if (!Gate::allows('manage-payments-edit')) {
            abort(403);
        }

        $doesReconDateExists = ZmRecon::where('TnxDt', $this->transaction_date)->where('ReconcOpt', $this->recon_type)->get();

        if (count($doesReconDateExists) > 0) {
            $this->alert('warning', "Reconciliation with transaction date {$this->transaction_date} exists!");
            return true;
        }

        $this->alert('warning', "Are you sure you want to request reconcilliation ?", [
            'position' => 'center',
            'toast' => false,
            'showConfirmButton' => true,
            'confirmButtonText' => "Submit",
            'onConfirmed' => 'submit',
            'showCancelButton' => true,
            'cancelButtonText' => 'Close',
            'timer' => null,
        ]);
    }


    public function submit()
    {
        try {
            $recon = ZmRecon::create([
                'TnxDt' => $this->transaction_date,
                'ReconcOpt' => $this->recon_type
            ]);
            
            $enquireRecon = (new ZanMalipoInternalService)->requestRecon($recon->id);
            
            // If response returns error rollback recon request
            if (array_key_exists('error', $enquireRecon)) {
                $this->alert('error', 'Something went wrong');
                return true;
            }
            return redirect()->route('payments.recons', encrypt($recon->id));
        } catch (Exception $e) {
            Log::error($e);
            $this->alert('error', 'Something went wrong');
        }
    }


    public function render()
    {
        return view('livewire.payments.request-recon');
    }
}
