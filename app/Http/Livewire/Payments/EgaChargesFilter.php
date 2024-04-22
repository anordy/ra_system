<?php

namespace App\Http\Livewire\Payments;

use App\Enum\CustomMessage;
use App\Exports\EgaChargesExport;
use App\Traits\CustomAlert;
use App\Traits\PaymentReportTrait;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class EgaChargesFilter extends Component
{
    use CustomAlert;
    use PaymentReportTrait;

    public $range_start;
    public $range_end;
    public $today;
    public $currency = 'all';
    public $payment_status = 'all';
    public $charges_type = 'all';
    public $hasData;
    public $parameters;

    public function mount()
    {
        $this->today = date('Y-m-d');
        $this->range_start = $this->today;
        $this->range_end = $this->today;

        $this->getData();
    }

    protected function rules()
    {
        return [
            'range_start' => 'required|date',
            'range_end' => 'required|date',
            'currency' => 'required|alpha',
            'payment_status' => 'required|alpha_gen',
            'charges_type' => 'required|alpha_gen',
        ];
    }

    public function updated($propertyName)
    {
        $this->search();
    }

    public function search()
    {
        $this->validate();
        $this->getData();
    }

    public function exportExcel()
    {
        $this->validate();
        try {
            $this->getData();
            $fileName = 'ega_charges' . now()->format('d-m-Y') . '.xlsx';
            $title = 'Ega Charges';
            $records = $this->getEgaChargesQuery($this->range_start, $this->range_end, $this->currency, $this->payment_status, $this->charges_type)->get();
            $parameters = $this->parameters;
            $this->customAlert('success', 'Exporting Excel File');
            return Excel::download(new EgaChargesExport($records, $title, $parameters), $fileName);
        } catch (\Exception $exception) {
            Log::error('PAYMENTS-EGA-CHARGES-FILTER-EXPORT-EXCEL', [$exception]);
            $this->customAlert('error', CustomMessage::ERROR);
        }
    }

    public function getParameters()
    {
        return [
            'currency' => $this->currency,
            'range_start' => $this->range_start,
            'range_end' => $this->range_end,
            'payment_status' => $this->payment_status,
            'charges_type' => $this->charges_type,
        ];
    }

    public function getData()
    {
        try {
            $this->parameters = $this->getParameters();
            $query = $this->getEgaChargesQuery($this->range_start, $this->range_end, $this->currency, $this->payment_status, $this->charges_type);
            $this->hasData = $query->exists();
        } catch (\Exception $exception) {
            Log::error('PAYMENTS-EGA-CHARGES-FILTER-GET-DATA', [$exception]);
            $this->customAlert('error', CustomMessage::ERROR);
        }
    }

    public function render()
    {
        return view('livewire.payments.ega-charges-filter');
    }
}
