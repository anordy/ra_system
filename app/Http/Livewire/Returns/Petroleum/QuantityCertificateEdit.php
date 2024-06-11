<?php

namespace App\Http\Livewire\Returns\Petroleum;

use Exception;
use Livewire\Component;
use App\Traits\CustomAlert;
use Livewire\WithFileUploads;
use App\Models\BusinessLocation;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Enum\QuantityCertificateStatus;
use App\Traits\WorkflowProcesssingTrait;
use App\Models\Returns\Petroleum\PetroleumConfig;
use App\Models\Returns\Petroleum\QuantityCertificate;
use App\Models\Returns\Petroleum\QuantityCertificateItem;

class QuantityCertificateEdit extends Component
{
    use CustomAlert, WorkflowProcesssingTrait, WithFileUploads;

    public $location;
    public $ship;
    public $port;
    public $cargo;
    public $liters_observed;
    public $liters_at_20;
    public $metric_tons;
    public $ascertained;
    public $configs = [];
    public $quantity_certificate_attachment;
    public $voyage_no;
    public Collection $products;

    public $certificate;

    protected function rules()
    {
        return [
            'ship' => 'required|strip_tag',
            'port' => 'required|strip_tag',
            'voyage_no' => 'nullable|strip_tag',
            'ascertained' => 'required|date',
            'location' => [
                'required',
                'exists:business_locations,zin'
            ],
            'products.*.config_id' => 'required',
            'products.*.liters_observed' => 'required|numeric',
            'products.*.liters_at_20' => 'required|numeric',
            'products.*.metric_tons' => 'required|numeric',
            'quantity_certificate_attachment' => 'nullable|mimes:pdf|max:1024'
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


    public function mount($id)
    {
        $id = decrypt($id);
        $this->certificate = QuantityCertificate::with('location', 'products')->find($id);
        if (is_null($this->certificate)) {
            abort(404);
        }
        $this->ascertained = date('Y-m-d', strtotime($this->certificate->ascertained));
        $this->ship = $this->certificate->ship;
        $this->port = $this->certificate->port;
        $this->voyage_no = $this->certificate->voyage_no;
        $this->location = $this->certificate->location->zin ?? '';

        $this->configs = PetroleumConfig::where('row_type', 'dynamic')
            ->where('col_type', '!=', 'heading')
            ->get();

        $this->products = collect([]);
        foreach ($this->certificate->products as $product) {
            $this->products->push(collect([
                'config_id' => $product->config_id,
                'liters_observed' => $product->liters_observed,
                'liters_at_20' => $product->liters_at_20,
                'metric_tons' => $product->metric_tons,
            ]));
        }
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
        if ($this->certificate->status == 'filled') {
            session()->flash('success', "You can't edit the already filled certificate");
            return;
        }
        try {
            if ($this->quantity_certificate_attachment) {
                $attachment_location = $this->quantity_certificate_attachment->store('/quantity-certificates', 'local-admin');
                $this->certificate->quantity_certificate_attachment = $attachment_location;
                $this->certificate->save();
            }

            $this->certificate->update([
                'ascertained' => $this->ascertained,
                'ship' => $this->ship,
                'port' => $this->port,
                'voyage_no' => $this->voyage_no,
                'created_by' => auth()->user()->id,
                'status' => QuantityCertificateStatus::DRAFT
            ]);

            $this->certificate->products()->delete();
            $product_payload = collect();
            foreach ($this->products as $product) {
                $product = (object) $product;
                $product_payload->push(new QuantityCertificateItem([
                    'certificate_id' => $this->certificate->id,
                    'config_id' => $product->config_id,
                    'cargo_name' => collect($this->configs)->firstWhere('id', $product->config_id)->name ?? '',
                    'liters_observed' => $product->liters_observed,
                    'liters_at_20' => $product->liters_at_20,
                    'metric_tons' => $product->metric_tons,
                ]));
            }

            $this->certificate->products()->saveMany($product_payload);

            $this->registerWorkflow(get_class($this->certificate), $this->certificate->id);
            $this->doTransition('certificate_corrected', ['status' => 'approved', 'comment' => null]);

            DB::commit();
            session()->flash('success', 'Certificate of Quantity has been updated and forwarded for approval');
            $this->redirect(route('petroleum.certificateOfQuantity.index'));
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
            $this->customAlert('error', 'Something went wrong, please contact the administrator for help');
        }
    }

    public function submit()
    {
        $this->save();
    }

    public function render()
    {
        return view('livewire.returns.petroleum.quantity_certificate.edit');
    }
}
