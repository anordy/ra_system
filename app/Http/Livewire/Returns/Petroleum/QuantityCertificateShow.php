<?php

namespace App\Http\Livewire\Returns\Petroleum;

use App\Models\Returns\Petroleum\PetroleumConfig;
use App\Models\Returns\Petroleum\QuantityCertificate;
use Illuminate\Support\Collection;
use App\Traits\CustomAlert;
use Livewire\Component;

class QuantityCertificateShow extends Component
{
    use CustomAlert;
   
    public $business;
    public $ship;
    public $port;
    public $cargo;
    public $voyage_no;
    public $ascertained;
    public $configs = [];
    public Collection $products;
    public $certificate;

    public function mount($id)
    {
        $id = decrypt($id);
        $this->certificate = QuantityCertificate::with('business', 'products')->find($id, ['id', 'business_id', 'location_id', 'ship', 'port', 'voyage_no', 'ascertained', 'download_count', 'created_by', 'status', 'created_at', 'updated_at', 'certificate_no', 'quantity_certificate_attachment']);
        if(is_null($this->certificate)){
            abort(404);
        }
        $this->ascertained = $this->certificate->ascertained;
        $this->ship = $this->certificate->ship;
        $this->port = $this->certificate->port;
        $this->voyage_no = $this->certificate->voyage_no;
        $this->business = $this->certificate->location->zin;

        $this->configs = PetroleumConfig::select('id', 'financia_year_id', 'order', 'code', 'name', 'row_type', 'value_calculated', 'col_type', 'rate_applicable', 'rate_type', 'currency', 'rate', 'rate_usd', 'value_formular', 'formular', 'active', 'value_label', 'rate_label', 'tax_label')
            ->where('row_type', 'dynamic')
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
