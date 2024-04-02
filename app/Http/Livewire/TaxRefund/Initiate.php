<?php

namespace App\Http\Livewire\TaxRefund;

use App\Enum\BillStatus;
use App\Enum\CustomMessage;
use App\Models\TaxRefund\PortLocation;
use App\Models\TaxRefund\TaxRefund;
use App\Models\TaxRefund\TaxRefundItem;
use App\Models\Tra\ExitedGood;
use App\Traits\CustomAlert;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class Initiate extends Component
{
    use CustomAlert;

    public $isZraRegistered = false;
    public $hasRefundDocument = false;
    public $ztnNumber, $importerName, $phoneNumber, $importerTIN, $importerVRN, $portId, $totalPayableAmount = 0;
    public $ports = [];

    public function mount()
    {
        $this->ports = PortLocation::select('id', 'name')->get();
    }

    public function proceed()
    {
        $this->validate([
            'ztnNumber' => 'nullable|required_if:isZraRegistered,1|exists:business_locations,zin',
            'isZraRegistered' => 'required',
            'hasRefundDocument' => 'required',
            'importerName' => 'nullable|required_if:isZraRegistered,0|alpha_space',
            'phoneNumber' => 'nullable|required_if:isZraRegistered,0|phone_no',
        ], [
            'importerName.required_if' => 'Importer name is required',
            'phoneNumber.required_if' => 'Phone number is required',
            'ztnNumber.required_if' => 'Location ztn number is required',
        ]);

    }

    public function submit()
    {
        try {
            DB::beginTransaction();

            $refundData = [
                'total_exclusive_tax_amount' => $this->totalPayableAmount,
                'rate' => 0.15,
                'total_payable_amount' => $this->totalPayableAmount * 0.15,
                'importer_name' => $this->importerName ?? null,
                'phone_number' => $this->phoneNumber ?? null,
                'ztn_number' => $this->ztnNumber ?? null,
                'payment_status' => BillStatus::PENDING,
                'payment_due_date' => Carbon::now()->addMonths(1)
            ];

            $taxRefund = TaxRefund::create($refundData);

            foreach ($this->allItems as $item) {
                $data = [
                    'tansad_number' => $item['tansad_number'] ?? null,
                    'efd_number' => $item['efd_number'] ?? null,
                    'exclusive_tax_amount' => $item['excl_tax_amount'] ?? null,
                    'rate' => 0.15,
                    'refund_id' => $taxRefund->id,
                    'item_name' => $item['item_name'] ?? null,
                ];

                TaxRefundItem::create($data);
            }
            DB::commit();

            // TODO: Generate control number

            // TODO: After payment of control number generate receipt number 
            $this->customAlert('success', 'Refund added successfully');
            return redirect()->route('tax-refund.index');
        } catch (\Exception $exception) {
            DB::rollBack();
            $this->customAlert('warning', CustomMessage::ERROR);
            Log::error('TAX-REFUND-INITIATE-SUBMIT', [$exception]);
        }
    }

    public function updated($propertyName)
    {
        if ($propertyName === 'hasRefundDocument') {
            $this->reset('ztnNumber', 'allItems');
        } else if ($propertyName === 'isZraRegistered') {
            $this->reset('ztnNumber', 'allItems');
        }
    }


    public function clear()
    {
        $this->reset('isZraRegistered', 'hasRefundDocument', 'ztnNumber');
    }

    public function addItem($i)
    {
        $this->validate([
            'allItems.*.tansad_number' => 'required|min:4|max:14|alpha_num|exists:exited_goods,tansad_number',
            'allItems.*.efd_number' => 'required|min:4|max:14|alpha_num|exists:efdms_receipts,receipt_number'
        ], [
            'allItems.*.tansad_number.required' => 'Tansad number is required',
            'allItems.*.tansad_number.exists' => 'Tansad number not found',
            'allItems.*.efd_number.required' => 'Efd number is required',
            'allItems.*.efd_number.exists' => 'Efd number not found',
        ]);

        // TODO: Check if tansad number is already added to avoid duplicates
        $this->allItems[$i]['excl_tax_amount'] = ExitedGood::select('value_excluding_tax')
            ->where('tansad_number', $this->allItems[$i]['tansad_number'])
            ->firstOrFail()
            ->value_excluding_tax;

        $this->totalPayableAmount = 0;
        foreach ($this->allItems as $item) {
            $this->totalPayableAmount += $item['excl_tax_amount'];
        }

        $this->allItems[] = [
            'tansad_number' => '',
            'efd_number' => '',
            'excl_tax_amount' => '',
            'item_name' => ''
        ];
    }

    public function removeItem($i)
    {
        unset($this->allItems[$i]);
        $this->totalPayableAmount = 0;
        foreach ($this->allItems as $item) {
            $this->totalPayableAmount += $item['excl_tax_amount'];
        }
    }

    public $allItems = [
        [
            'tansad_number' => '',
            'efd_number' => '',
            'excl_tax_amount' => '',
            'item_name' => ''
        ]
    ];


    public function render()
    {
        return view('livewire.tax-refund.initiate');
    }

}