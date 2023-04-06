<?php

namespace App\Http\Livewire\Returns\Petroleum;

use App\Models\Business;
use App\Models\Returns\Petroleum\PetroleumConfig;
use App\Models\Returns\Petroleum\QuantityCertificate;
use Illuminate\Support\Collection;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;


class QuantityCertificateShow extends Component
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
    public $configs = [];
    public Collection $products;

    public $certificate;





    public function mount($id)
    {
        $id = decrypt($id);
        $this->certificate = QuantityCertificate::with('business', 'products')->find($id);
        if(is_null($this->certificate)){
            abort(404);
        }
        $this->ascertained = $this->certificate->ascertained;
        $this->ship = $this->certificate->ship;
        $this->port = $this->certificate->port;
        $this->voyage_no = $this->certificate->voyage_no;
        $this->business = $this->certificate->location->zin;

        $this->configs = PetroleumConfig::where('row_type', 'dynamic')
            ->where('col_type', '!=', 'heading')
            ->get();

        
            $this->products = collect([]);
            foreach ($this->certificate->products as $product) {
                $this->products->push(collect([
                    'cargo_name' => $product->cargo_name,
                    'liters_observed' => $product->liters_observed,
                    'liters_at_20' => $product->liters_at_20,
                    'metric_tons' => $product->metric_tons,
                ]));
            }

        
    }

    public function render()
    {
        return view('livewire.returns.petroleum.quantity_certificate.show');
    }
}
