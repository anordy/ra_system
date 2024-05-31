<?php

namespace App\Http\Livewire\TaxRefund;

use App\Enum\BillStatus;
use App\Enum\CustomMessage;
use App\Enum\GeneralConstant;
use App\Events\SendSms;
use App\Jobs\SendCustomSMS;
use App\Models\BusinessLocation;
use App\Models\Currency;
use App\Models\SystemSetting;
use App\Models\TaxRefund\PortLocation;
use App\Models\TaxRefund\TaxRefund;
use App\Models\TaxRefund\TaxRefundItem;
use App\Models\Tra\ExitedGood;
use App\Traits\CustomAlert;
use App\Traits\PaymentsTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class Initiate extends Component
{
    use CustomAlert, PaymentsTrait;

    public $isZraRegistered = false;
    public $hasRefundDocument = false;
    public $ztnNumber, $importerName, $phoneNumber, $importerTIN, $importerVRN, $portId, $totalPayableAmount = 0;
    public $ports = [];
    public $rate, $location;

    public function mount()
    {
        $this->rate = SystemSetting::select('id', 'value')->where('code', SystemSetting::TAX_REFUND_RATE)->firstOrFail();
        $this->ports = PortLocation::select('id', 'name')->get();
    }

    public function proceed()
    {
        $this->validate([
            'portId' => 'required|numeric|exists:port_locations,id',
            'ztnNumber' => 'nullable|required_if:isZraRegistered,' . GeneralConstant::ONE . '|exists:business_locations,zin',
            'isZraRegistered' => 'required|numeric',
            'hasRefundDocument' => 'required|numeric',
            'importerName' => 'nullable|required_if:isZraRegistered,' . GeneralConstant::ZERO . '|alpha_space',
            'phoneNumber' => 'nullable|required_if:isZraRegistered,' . GeneralConstant::ZERO . '|phone_no',
        ], [
            'importerName.required_if' => 'Importer name is required',
            'phoneNumber.required_if' => 'Phone number is required',
            'ztnNumber.required_if' => 'Location ztn number is required',
            'isZraRegistered.numeric' => 'Invalid choice format',
            'hasRefundDocument.numeric' => 'Invalid choice format',
        ]);

        if ($this->ztnNumber) {
            $this->location = BusinessLocation::with(['business:id,name,mobile'])
                ->select('id', 'business_id', 'name')
                ->where('zin', $this->ztnNumber)
                ->first();

            if (!$this->location) {
                $this->customAlert('warning', 'Business Location Not Found');
                return;
            }

            $this->importerName = $this->location->name;
            $this->phoneNumber = $this->location->business->mobile;
        }

    }

    public function submit()
    {
        $this->proceed();

        try {
            DB::beginTransaction();

            $refundData = [
                'port_id' => $this->portId,
                'total_exclusive_tax_amount' => $this->totalPayableAmount,
                'rate' => $this->rate->value,
                'total_payable_amount' => $this->totalPayableAmount * $this->rate->value,
                'importer_name' => $this->importerName ?? null,
                'phone_number' => $this->phoneNumber ?? null,
                'business_location_id' => $location->id ?? null,
                'ztn_number' => $this->ztnNumber ?? null,
                'payment_status' => BillStatus::SUBMITTED,
                'payment_due_date' => Carbon::now()->addMonths(1),
                'currency' => Currency::TZS
            ];

            $taxRefund = TaxRefund::create($refundData);

            foreach ($this->allItems as $item) {
                $data = [
                    'tansad_number' => $item['tansad_number'] ?? null,
                    'efd_number' => $item['efd_number'] ?? null,
                    'exclusive_tax_amount' => $item['excl_tax_amount'] ?? null,
                    'rate' => $this->rate->value,
                    'refund_id' => $taxRefund->id,
                    'item_name' => $item['item_name'] ?? null,
                ];

                TaxRefundItem::create($data);
            }
            DB::commit();

//            if ($taxRefund->businessLocation) {
//                // Send Notification to Taxpayer
//                event(new SendSms(SendCustomSMS::SERVICE, NULL,
//                    [
//                        'phone' => $taxRefund->businessLocation->mobile,
//                        'message' => "Tax refund received"
//                    ]
//                ));
//            }


            $this->customAlert('success', 'Refund added successfully');
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error('TAX-REFUND-INITIATE-SUBMIT', [$exception]);
            $this->customAlert('warning', CustomMessage::ERROR);
            return;
        }

        if ($this->hasRefundDocument === GeneralConstant::ZERO) {
            try {
                $this->generateTaxRefundControlNumber($taxRefund);
            } catch (\Exception $exception) {
                Log::error('TAX-REFUND-INITIATE-SUBMIT', [$exception]);
                $this->customAlert('error', 'Failed to Generate control number');
            }

        }


        return redirect()->route('tax-refund.show', [encrypt($taxRefund->id)]);
    }

    public function updated($propertyName)
    {
        if ($propertyName === 'hasRefundDocument') {
            $this->reset('allItems');
        } else if ($propertyName === 'isZraRegistered') {
            $this->reset('ztnNumber', 'allItems', 'importerName', 'phoneNumber');
        }

        $exploded = explode('.', $propertyName);
        if (count($exploded) == 3 && $exploded['0'] == 'allItems' && ($exploded[2] == 'item_name' || $exploded[2] == 'excl_tax_amount')) {
            $this->addItem($exploded[1], false);
        }

    }


    public function clear()
    {
        $this->reset('isZraRegistered', 'hasRefundDocument', 'ztnNumber');
    }

    public function addItem($i, $addRow = true)
    {
        $this->validate([
            'allItems.*.tansad_number' => 'required_if:hasRefundDocument,' . GeneralConstant::ONE . '|min:4|max:14|alpha_num|exists:exited_goods,tansad_number',
            'allItems.*.efd_number' => 'required_if:hasRefundDocument,' . GeneralConstant::ONE . '|min:4|max:14|alpha_num|exists:efdms_receipts,receipt_number',
            'allItems.*.item_name' => 'required_if:hasRefundDocument,' . GeneralConstant::ZERO . '|alpha_num_space',
            'allItems.*.excl_tax_amount' => 'required_if:hasRefundDocument,' . GeneralConstant::ZERO . '|numeric',
        ], [
            'allItems.*.tansad_number.required' => 'Tansad number is required',
            'allItems.*.tansad_number.exists' => 'Tansad number not found',
            'allItems.*.efd_number.required' => 'Efd number is required',
            'allItems.*.efd_number.exists' => 'Efd number not found',
            'allItems.*.item_name.required_if' => 'Item name is required',
            'allItems.*.excl_tax_amount.required_if' => 'Amount is required',
        ]);


        if ($this->hasRefundDocument === GeneralConstant::ONE) {
            // TODO: Check if TANSAD number is already added to avoid duplicates
            $exitedGood = ExitedGood::select('value_excluding_tax')
                ->where('tansad_number', $this->allItems[$i]['tansad_number'])
                ->first();

            if (!$exitedGood) {
                $this->customAlert('warning', "Exited Good with TANSAD number {$this->allItems[$i]['tansad_number']} not found");
                return;
            }

            $utilized = TaxRefundItem::select('id')
                ->orWhere('tansad_number', $this->allItems[$i]['tansad_number'])
                ->orWhere('efd_number', $this->allItems[$i]['efd_number'])
                ->first();

            if ($utilized) {
                $this->customAlert('warning', "The provided TANSAD number or EFD Receipt number has already been utilized");
                return;
            }

            $this->allItems[$i]['excl_tax_amount'] = $exitedGood->value_excluding_tax;
        }

        $this->totalPayableAmount = 0;
        foreach ($this->allItems as $item) {
            $this->totalPayableAmount += $item['excl_tax_amount'];
        }

        if ($addRow) {
            $this->allItems[] = [
                'tansad_number' => '',
                'efd_number' => '',
                'excl_tax_amount' => '',
                'item_name' => ''
            ];
        }
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