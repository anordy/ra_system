<?php

namespace App\Http\Livewire\Returns\Petroleum;

use App\Models\Business;
use App\Models\Returns\Petroleum\PetroleumConfig;
use App\Models\Returns\Petroleum\QuantityCertificate;
use App\Models\Returns\Petroleum\QuantityCertificateItem;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Illuminate\Support\Collection;


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
    public $voyage_no;
    public $configs = [];
    public Collection $products;


    protected function rules()
    {
        return [
            'ship' => 'required',
            'port' => 'required',
            'voyage_no' => 'nullable',
            'ascertained' => 'required|date|after_or_equal:today',
            'business' => [
                'required',
                'exists:businesses,zin'
            ],
            'products.*.config_id' => 'required',
            'products.*.liters_observed' => 'required|numeric',
            'products.*.liters_at_20' => 'required|numeric',
            'products.*.metric_tons' => 'required|numeric',
        ];
    }

    protected $messages = [
        'products.*.config_id.required' => 'Product field required',
        'products.*.liters_observed.required' => 'Listres observed field is required',
        'products.*.liters_observed.numeric' => 'Litres observed field must be a number',
        'products.*.liters_at_20.required' => 'Litres at 20 field is required',
        'products.*.liters_at_20.numeric' => 'Litres at 20 field must be a number',
        'products.*.metric_tons.required' => 'Metric tons field is required',
        'products.*.metric_tons.numeric' => 'Metric tons field must be numeric',
    ];


    public function mount()
    {
        $this->ascertained = Carbon::now()->toDateString();

        $this->configs = PetroleumConfig::where('row_type', 'dynamic')
            ->where('col_type', '!=', 'heading')
            ->get();


        $this->fill([
            'products' => collect([
                [
                    'config_id' => '',
                    'liters_observed' => '',
                    'liters_at_20' => '',
                    'metric_tons' => '',
                ]
            ]),
        ]);
    }


    public function addProduct()
    {
        $this->products->push([
            'config_id' => '',
            'liters_observed' => '',
            'liters_at_20' => '',
            'metric_tons' => '',
        ]);
    }

    public function removeProduct($key)
    {
        $this->products->pull($key);
    }



    public function save()
    {
        $this->validate();
        DB::beginTransaction();
        try {
            $business = Business::firstWhere('zin', $this->business);
            
            $certificate = QuantityCertificate::create([
                'business_id' => $business->id,
                'ascertained' => $this->ascertained,
                'ship' => $this->ship,
                'port' => $this->port,
                'voyage_no' => $this->voyage_no,
                'created_by' => auth()->user()->id,
                'download_count' => 0
            ]);
            
            $certificateNumber = $business->zin.'-'.$certificate->id;
            $certificateUpdate = QuantityCertificate::find($certificate->id);
            $certificateUpdate->certificate_no = $certificateNumber;
            $certificateUpdate->save();

            $product_payload = collect();
            foreach ($this->products as $product) {
                $product = (object) $product;
                $product_payload->push(new QuantityCertificateItem([
                    'certificate_id' => $certificate->id,
                    'config_id' => $product->config_id,
                    'cargo_name' => collect($this->configs)->firstWhere('id', $product->config_id)->name ?? '',
                    'liters_observed' => $product->liters_observed,
                    'liters_at_20' => $product->liters_at_20,
                    'metric_tons' => $product->metric_tons,
                ]));
            }

            $certificate->products()->saveMany($product_payload);

            DB::commit();
            session()->flash('success', 'Certificate of Quantity has been generated successfully');
            $this->redirect(route('petroleum.certificateOfQuantity.index'));
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            report($e);
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
