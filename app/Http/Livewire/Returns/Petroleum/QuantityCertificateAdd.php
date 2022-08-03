<?php

namespace App\Http\Livewire\Returns\Petroleum;

use App\Models\Business;
use App\Models\QuantityCertificate;
use App\Models\Returns\Petroleum\PetroleumConfig;
use App\Models\Returns\Petroleum\PetroleumConfigHead;
use App\Models\Returns\Petroleum\PetroleumReturn;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;


class QuantityCertificateAdd extends Component
{
    use LivewireAlert;


    public $business;
    public $ship;
    public $port;
    public $cargo;
    public $liters_observed;
    public $liters_at_20;
    public $metric_tons;
    public $ascertained;


    protected function rules(){
        return [
            'ship' => 'required',
            'port' => 'required',
            'business' => [
                'required',
                'exists:businesses,z_no'
            ],
            'cargo' => 'required',
            'liters_observed' => 'required|numeric',
            'liters_at_20' => 'required|numeric',
            'metric_tons' => 'required|numeric',
            'ascertained' => 'required|date',
        ];
    }

    public function mount()
    {
        $this->ascertained = Carbon::now()->toDateString();
    }




    public function save()
    {
        $this->validate();
        DB::beginTransaction();
        try {
            $business = Business::firstWhere('z_no',$this->business);
            
            QuantityCertificate::create([
                'business_id' => $business->id,
                'ascertained' => $this->ascertained,
                'ship' => $this->ship,
                'port' => $this->port,
                'cargo' => $this->cargo,
                'liters_observed' => $this->liters_observed,
                'liters_at_20' => $this->liters_at_20,
                'metric_tons' => $this->metric_tons,
                'created_by' => auth()->user()->id,
                'download_count' => 0
            ]);

            DB::commit();
            session()->flash('success', 'Certificate of Quantity has been generated successfully');
            $this->redirect(route('petroleum.certificateOfQuantity.index'));
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            $this->alert('error', 'Something went wrong');
        }
    }

    public function submit()
    {
        $this->save();
    }

    public function render()
    {
        return view('livewire.returns.petroleum.quantity_certificate.add');
    }
}
