<?php

namespace App\Http\Livewire\Relief;

use App\Models\Business;
use App\Models\BusinessStatus;
use App\Models\Relief\Relief;
use App\Models\Relief\ReliefAttachment;
use App\Models\Relief\ReliefItems;
use App\Models\Relief\ReliefProject;
use App\Models\Relief\ReliefProjectList;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;

class ReliefRegistrations extends Component
{

    use LivewireAlert, WithFileUploads;

    //input fields
    public $supplier;
    public $supplierLocation;
    public $projectSection;
    public $project;
    public $items;
    // public $description;
    public $quantity;
    public $unit;
    public $attachments;

    //displayed fields
    public $rate;
    public $total;
    public $vatAmount;
    public $relievedAmount;
    public $amountPayable;

    //select options
    public $optionSuppliers;
    public $optionSupplierLocations;
    public $optionProjectSections;
    public $optionProjects;

    //hide/show elements
    public $showSupplierLocations;
    public $showRate;

    //backend variable
    public $vatPercent;


    public function mount()
    {
        $this->optionSuppliers = Business::where('status', BusinessStatus::APPROVED)->get();
        $this->optionSupplierLocations = null;

        $this->optionProjectSections = ReliefProject::has('reliefProjects')->get();
        $this->optionProjects = null;

        $this->items = [
            [
                'name' => '',
                // 'description' => '',
                'quantity' => '',
                'unit' => '',
                'costPerItem' => '',
                'amount' => '',
            ],
        ];

        $this->attachments = [
            [
                'name' => '',
                'file' => '',
            ],
        ];
        $this->vatPercent = 15; // todo: make this globally available, easy to change in case of changes
    }

    protected function rules()
    {
        return [
            'supplier' => 'required',
            'supplierLocation' => 'required',
            'projectSection' => 'required',
            'project' => 'required',
            'items.*.name' => 'required',
            // 'items.*.description' => 'required',
            'items.*.quantity' => 'required|numeric',
            // 'items.*.unit' => 'required',
            'items.*.costPerItem' => 'required|numeric',
            'attachments.*.file' => 'nullable|required_with:attachments.*.name|mimes:pdf',
            'attachments.*.name' => 'nullable|required_with:attachments.*.file',

        ];
    }

    public function save()
    {
        if(!Gate::allows('relief-registration-create')){
            abort(403);
        }
        $this->validate();
        try {
            DB::beginTransaction();
            $relief = Relief::create([
                'project_id' => $this->projectSection,
                'project_list_id' => $this->project,
                'location_id' => $this->supplierLocation,
                'business_id' => $this->supplier,
                'rate' => $this->rate,
                'vat' => $this->vatPercent,
                'total_amount' => $this->total,
                'vat_amount' => $this->vatAmount,
                'relieved_amount' => $this->relievedAmount,
                'amount_payable' => $this->amountPayable,
                'expire' => date('Y-m-d', strtotime('+1 month')),
                'status' => "pending",
                'created_by' => Auth::user()->id,
            ]);

            foreach ($this->items as $item) {
                $reliefItem = ReliefItems::create([
                    'relief_id' => $relief->id,
                    'item_name' => $item['name'],
                    'quantity' => $item['quantity'],
                    'unit' => $item['unit'],
                    // 'description' => $item['description'],
                    'amount_per_item' => $item['costPerItem'],
                    'amount' => $item['quantity'] * $item['costPerItem'],
                ]);
            }

            foreach ($this->attachments as $attachment) {
                if ($attachment['file'] && $attachment['name']) {
                    $documentPath = $attachment['file']->store("/relief_documents/" . $relief->id, 'local-admin');
                    $reliefDocument = ReliefAttachment::create([
                        'relief_id' => $relief->id,
                        'file_path' => $documentPath,
                        'file_name' => $attachment['name'], // todo: do not store original file name (security concerns), sanitize or use a unique ID
                    ]);
                }
            }

            DB::commit();
            session()->flash('success', 'Successfully saved');
            return redirect()->route('reliefs.applications.index');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            $this->alert('error', 'Something went wrong, Please contact our support desk for help');
        }
    }

    protected function messages()
    {
        return [
            'supplier.required' => 'Please select a supplier',
            'supplierLocation.required' => 'Please select a supplier location',
            'projectSection.required' => 'Please select a project section',
            'project.required' => 'Please select a project',
            'items.*.name.required' => 'Please enter item name',
            // 'items.*.description.required' => 'Please enter item description',
            'items.*.quantity.required' => 'Please enter item quantity',
            // 'items.*.unit.required' => 'Please enter item unit',
            'items.*.costPerItem.required' => 'Please enter item cost per item',
            'attachments.*.file.required_with' => 'Please upload an attachment',
            'attachments.*.name.required_with' => 'Please enter attachment name',
            // 'items.*.amount.required' => 'Please enter item amount',
            // 'attachments.*.name.required' => 'Please enter attachment name',
            // 'attachments.*.file.required' => 'Please select a file',
        ];
    }

    public function render()
    {
        return view('livewire.relief.registrations.add');
    }

    public function updated($propertyName)
    {
        if ($propertyName == 'supplier') {
            if ($this->supplier == "") {
                $this->optionSupplierLocations = null;
            } else {
//                todo: select only the columns you need
                $this->optionSupplierLocations = Business::find($this->supplier)->locations;
                $this->supplierLocation = $this->optionSupplierLocations->first()->id;
            }
        }

        if ($propertyName == 'projectSection') {
            if ($this->projectSection == '') {
                $this->optionProjects = null;
                $this->rate = null;
            } else {
                $this->optionProjects = ReliefProjectList::where('project_id', $this->projectSection)->get();
                $this->project = $this->optionProjects->first()->id;
                $this->rate = $this->optionProjects->first()->rate;
            }
            $this->calculateTotal();
        }

        if ($propertyName == 'project') {
            if ($this->project == '') {
                $this->rate = null;
            } else {
                $this->rate = ReliefProjectList::find($this->project)->rate;
            }
            $this->calculateTotal();
        }
    }

    public function addItem()
    {
        $this->items[] = [
            'name' => '',
            // 'description' => '',
            'quantity' => '',
            'unit' => '',
            'costPerItem' => '',
            'amount' => '',
        ];
    }

    public function removeItem($i)
    {
        unset($this->items[$i]);
        $this->calculateTotal();
    }

    public function calculateAmountPayable($i)
    {
        $costPerItem = is_numeric($this->items[$i]['costPerItem']) ? $this->items[$i]['costPerItem'] : 0;
        $quantity = is_numeric($this->items[$i]['quantity']) ? $this->items[$i]['quantity'] : 0;
        $this->items[$i]['amount'] = $costPerItem * $quantity;
        $this->calculateTotal();
    }

    public function calculateTotal()
    {
        $this->total = 0;
        foreach ($this->items as $item) {
            $this->total += is_numeric($item['amount']) ? $item['amount'] : 0;
        }

        //calculate relieved amount
        $this->vatAmount = ($this->vatPercent * $this->total) / 100;
        $rate = ($this->rate ?? 0) / 100;
        $this->relievedAmount = $rate * $this->vatAmount;

        //calculate amount payable
        $this->amountPayable = $this->total + ($this->vatAmount - $this->relievedAmount);
    }

    public function addAttachment()
    {
        $this->attachments[] = [
            'name' => '',
            'file' => '',
        ];
    }

    public function removeAttachment($i)
    {
        unset($this->attachments[$i]);
    }
}
