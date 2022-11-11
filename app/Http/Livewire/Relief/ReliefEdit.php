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
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Gate;

class ReliefEdit extends Component
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
    public $relief;
    public $previousAttachments;

    public function mount($enc_id)
    {
        $this->relief = Relief::find(decrypt($enc_id));
        $this->supplier = $this->relief->business_id;
        $this->optionSuppliers = Business::where('status', BusinessStatus::APPROVED)->get();
        $this->supplierLocation = $this->relief->location_id;
        $this->optionSupplierLocations = Business::find($this->supplier)->locations;

        $this->projectSection = ReliefProject::find($this->relief->project_id)->id;
        $this->optionProjectSections = ReliefProject::all();

        $this->project = ReliefProjectList::find($this->relief->project_list_id)->id;
        $this->optionProjects = ReliefProjectList::where('project_id', $this->projectSection)->get();
        $this->rate = ReliefProjectList::find($this->relief->project_list_id)->rate;

        foreach ($this->relief->reliefItems as $item) {
            $this->items[] = [
                'name' => $item->item_name,
                'quantity' => $item->quantity,
                'unit' => $item->unit,
                'costPerItem' => $item->amount_per_item,
                'amount' => $item->amount,
            ];
        }
        $this->previousAttachments = [];
        if ($this->relief->reliefAttachments->count() > 0) {
            foreach ($this->relief->reliefAttachments as $reliefAttachment) {
                $this->previousAttachments[] = [
                    'id' => $reliefAttachment->id,
                    'file_name' => $reliefAttachment->file_name,
                    'file_path' => $reliefAttachment->file_path,
                ];
            }
        }
        $this->attachments = [
            [
                'name' => '',
                'file' => '',
            ],
        ];
        $this->vatPercent = 15;
        $this->calculateTotal();
    }

    protected function rules()
    {
        return [
            'supplier' => 'required',
            'supplierLocation' => 'required',
            'projectSection' => 'required',
            'project' => 'required',
            'items.*.name' => 'required',
            'items.*.quantity' => 'required|numeric',
            'items.*.costPerItem' => 'required|numeric',
            'attachments.*.file' => 'nullable|required_with:attachments.*.name|mimes:pdf',
            'attachments.*.name' => 'nullable|required_with:attachments.*.file',
        ];
    }

    public function save()
    {
        if(!Gate::allows('relief-applications-edit')){
            abort(403);
        }
        $this->validate();
        try {
            DB::beginTransaction();
            $this->relief->update([
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

            $this->relief->reliefItems()->delete();
            foreach ($this->items as $item) {
                $reliefItem = ReliefItems::create([
                    'relief_id' => $this->relief->id,
                    'item_name' => $item['name'],
                    'quantity' => $item['quantity'],
                    'unit' => $item['unit'],
                    'amount_per_item' => $item['costPerItem'],
                    'amount' => $item['quantity'] * $item['costPerItem'],
                ]);

            }

            //remove previous attachments which are not in the current attachments
            foreach ($this->relief->reliefAttachments as $savedAttachment) {
                $removeAttachment = true;
                foreach ($this->previousAttachments as $remainingAttachment) {
                    if ($remainingAttachment['id']==$savedAttachment->id){
                        $removeAttachment = false;
                        break;
                    }
                }
                if($removeAttachment){
                    ReliefAttachment::find($savedAttachment->id)->delete();
                    Storage::disk('local-admin')->delete($savedAttachment->file_path);
                }
            }

            foreach ($this->attachments as $attachment) {
                if ($attachment['file'] && $attachment['name']) {
                    $documentPath = $attachment['file']->store("/relief_documents/" . $this->relief->id, 'local-admin');
                    $reliefDocument = ReliefAttachment::create([
                        'relief_id' => $this->relief->id,
                        'file_path' => $documentPath,
                        'file_name' => $attachment['name'],
                    ]);
                }
            }


            DB::commit();
            session()->flash('success', 'Successfully Edited');
            return redirect()->route('reliefs.applications.index');
        } catch (\Exception$e) {
            DB::rollBack();
            Log::error($e->getMessage());
            $this->alert('error', 'Something went wrong');
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
            'items.*.quantity.required' => 'Please enter item quantity',
            'items.*.costPerItem.required' => 'Please enter item cost per item',
            'attachments.*.file.required_with' => 'Please upload an attachment',
            'attachments.*.name.required_with' => 'Please enter attachment name',
        ];
    }

    public function render()
    {
        return view('livewire.relief.relief-edit');
    }

    public function updated($propertyName)
    {
        if ($propertyName == 'supplier') {
            if ($this->supplier == "") {
                $this->optionSupplierLocations = null;
            } else {
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
        //calculate total
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

    public function removePreviousAttachment($id)
    {
        // $attachment = ReliefAttachment::find($id);
        // $attachment->delete();
        foreach ($this->previousAttachments as $key => $attachment) {
            if ($attachment['id'] == $id) {
                unset($this->previousAttachments[$key]);
            }
        }
    }

}
