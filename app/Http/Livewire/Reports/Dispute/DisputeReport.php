<?php

namespace App\Http\Livewire\Reports\Dispute;

use App\Enum\CustomMessage;
use App\Enum\ReportStatus;
use App\Exports\DisputeReportExport;
use App\Models\FinancialYear;
use App\Models\TaxType;
use App\Traits\CustomAlert;
use App\Traits\DisputeReportTrait;
use App\Traits\GenericReportTrait;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class DisputeReport extends Component
{
    use CustomAlert, DisputeReportTrait, GenericReportTrait;

    public $optionYears = [];
    public $optionPeriods = [];
    public $optionSemiAnnuals = [];
    public $optionQuarters = [];
    public $optionMonths = [];
    public $optionTaxTypes = [];

    public $showPreviewTable = false;
    public $activateButtons = false;

    public $year;
    public $month;
    public $period;
    public $quater;
    public $semiAnnual;
    public $tax_type_id = 'all';
    public $type;
    public $filing_report_type;
    public $payment_report_type;
    public $startMonth;
    public $endMonth;

    protected function rules()
    {
        return [
            'tax_type_id' => 'required|alpha_num',
            'year' => 'required|alpha_num',
            'period' => $this->year != ReportStatus::all ? ['required', Rule::in([ReportStatus::MONTHLY, ReportStatus::QUARTERLY, ReportStatus::SEMI_ANNUAL, ReportStatus::ANNUAL])] : 'nullable',
            'month' => $this->period == ReportStatus::MONTHLY ? ['required', 'alpha_gen'] : 'nullable',
            'quater' => $this->period == ReportStatus::QUARTERLY ? ['required', Rule::in([ReportStatus::FIRST_QUARTER, ReportStatus::SECOND_QUARTER, ReportStatus::THIRD_QUARTER, ReportStatus::FOURTH_QUARTER])] : '',
            'semiAnnual' => $this->period == ReportStatus::SEMI_ANNUAL ? 'required|alpha_gen' : 'nullable',
        ];
    }

    public function mount()
    {
        $this->initializeOptions();
        $this->optionTaxTypes = TaxType::whereIn('code', [TaxType::WAIVER, TaxType::OBJECTION, TaxType::WAIVER_OBJECTION])->get();
    }

    public function updated($propertyName)
    {
        if ($propertyName == ReportStatus::Period) {
            $this->reset('month', 'quater', 'semiAnnual');
        }
        if ($propertyName == ReportStatus::Year) {
            $this->reset('month', 'quater', 'semiAnnual', 'period');
        }
    }

    public function exportExcel()
    {
        $this->validate();
        try {
            $parameters = $this->getParameters();
            $records = $this->getRecords($parameters);
            if ($records->count() < 1) {
                $this->customAlert('error', 'No Records Found in the selected criteria');
                return;
            }

            if (!array_key_exists('tax_type_id', $parameters)) {
                throw new \Exception('Missing tax_type_id key in parameters');
            }

            if ($parameters['tax_type_id'] == ReportStatus::all) {
                $tax_type_name = ReportStatus::All;
            } else {
                $tax_type_name = TaxType::findOrFail($parameters['tax_type_id'])->name;
            }

            if (!array_key_exists('year', $parameters)) {
                throw new \Exception('Missing year key in parameters');
            }

            if ($parameters['year'] == ReportStatus::all) {
                $fileName = $tax_type_name . '_' . 'Disputes' . '.xlsx';
                $title = 'Notice of Dispute' . ' For ' . $tax_type_name;
            } else {
                $fileName = $tax_type_name . '_' . 'Dispute' . ' - ' . $parameters['year'] . '.xlsx';
                $title = 'Dispute' . ' For ' . $tax_type_name . '-' . $parameters['year'];
            }
            $this->customAlert('success', 'Exporting Excel File');
            return Excel::download(new DisputeReportExport($records, $title, $parameters), $fileName);

        } catch (\Exception $exception) {
            Log::error('REPORTS-DISPUTE-DISPUTE-REPORT-EXPORT-EXCEL', [$exception]);
            $this->customAlert('error', CustomMessage::ERROR);
        }
    }

    public function exportPdf()
    {
        $this->validate();
        try {
            $parameters = $this->getParameters();
            $records = $this->getRecords($parameters);
            if ($records->count() < 1) {
                $this->customAlert('error', 'No Records Found in the selected criteria');
                return;
            }
            $this->customAlert('success', 'Exporting Pdf File');
            return redirect()->route('reports.disputes.download.pdf', encrypt(json_encode($parameters)));
        } catch (\Exception $exception) {
            Log::error('REPORTS-DISPUTE-DISPUTE-REPORT-EXPORT-PDF', [$exception]);
            $this->customAlert('error', CustomMessage::ERROR);
        }
    }

    public function preview()
    {
        $this->validate();
        try {
            $parameters = $this->getParameters();
            $records = $this->getRecords($parameters);
            if ($records->count() < 1) {
                $this->customAlert('error', 'No Records Found in the selected criteria');
                return;
            }
            return redirect()->route('reports.disputes.preview', encrypt(json_encode($this->getParameters())));
        } catch (\Exception $exception) {
            Log::error('REPORTS-DISPUTE-DISPUTE-REPORT-PREVIEW', [$exception]);
            $this->customAlert('error', CustomMessage::ERROR);
        }
    }

    public function getParameters()
    {
        return [
            'tax_type_id' => $this->tax_type_id,
            'type' => $this->type,
            'year' => $this->year,
            'period' => $this->period,
            'month' => $this->month,
            'quater' => $this->quater,
            'semiAnnual' => $this->semiAnnual,
            'dates' => $this->getStartEndDate(),
        ];
    }

    public function getStartEndDate()
    {
        if ($this->year == ReportStatus::all) {
            return [
                'startDate' => null,
                'endDate' => null,
            ];
        } elseif ($this->month) {
            $date = \Carbon\Carbon::parse($this->year . "-" . $this->month . "-01");
            $start = $date->startOfMonth()->format('Y-m-d H:i:s');
            $end = $date->endOfMonth()->format('Y-m-d H:i:s');
            $from = $date->startOfMonth()->format('Y-m-d');
            $to = $date->endOfMonth()->format('Y-m-d');
            return ['startDate' => $start, 'endDate' => $end, 'from' => $from, 'to' => $to];
        } elseif ($this->quater) {
            if ($this->quater == ReportStatus::FIRST_QUARTER) {
                $this->startMonth = ReportStatus::ONE;
                $this->endMonth = ReportStatus::THREE;
            } elseif ($this->quater == ReportStatus::SECOND_QUARTER) {
                $this->startMonth = ReportStatus::FOUR;
                $this->endMonth = ReportStatus::SIX;
            } elseif ($this->quater == ReportStatus::THIRD_QUARTER) {
                $this->startMonth = ReportStatus::SEVEN;
                $this->endMonth = ReportStatus::NINE;
            } elseif ($this->quater == ReportStatus::FOURTH_QUARTER) {
                $this->startMonth = ReportStatus::TEN;
                $this->endMonth = ReportStatus::TWELVE;
            }

            $startDate = \Carbon\Carbon::parse($this->year . "-" . $this->startMonth . "-01");
            $endDate = \Carbon\Carbon::parse($this->year . "-" . $this->endMonth . "-01");
            $start = $startDate->startOfMonth()->format('Y-m-d H:i:s');
            $end = $endDate->endOfMonth()->format('Y-m-d H:i:s');
            $from = $startDate->format('Y-m-d');
            $to = $endDate->format('Y-m-d');
            return ['startDate' => $start, 'endDate' => $end, 'from' => $from, 'to' => $to];
        } elseif ($this->semiAnnual) {
            if ($this->semiAnnual == ReportStatus::FIRST_SEMI_ANNUAL) {
                $this->startMonth = ReportStatus::ONE;
                $this->endMonth = ReportStatus::SIX;
            } elseif ($this->semiAnnual == ReportStatus::SECOND_SEMI_ANNUAL) {
                $this->startMonth = ReportStatus::SEVEN;
                $this->endMonth = ReportStatus::TWELVE;
            }
            $startDate = \Carbon\Carbon::parse($this->year . "-" . $this->startMonth . "-01");
            $endDate = \Carbon\Carbon::parse($this->year . "-" . $this->endMonth . "-01");
            $start = $startDate->startOfMonth()->format('Y-m-d H:i:s');
            $end = $endDate->endOfMonth()->format('Y-m-d H:i:s');
            $from = $startDate->format('Y-m-d');
            $to = $endDate->format('Y-m-d');
            return ['startDate' => $start, 'endDate' => $end, 'from' => $from, 'to' => $to];
        } else {
            $startDate = \Carbon\Carbon::parse($this->year . "-" . "01" . "-01");
            $endDate = \Carbon\Carbon::parse($this->year . "-" . "12" . "-01");
            $start = $startDate->startOfMonth()->format('Y-m-d H:i:s');
            $end = $endDate->endOfMonth()->format('Y-m-d H:i:s');
            $from = $startDate->format('Y-m-d');
            $to = $endDate->format('Y-m-d');
            return ['startDate' => $start, 'endDate' => $end, 'from' => $from, 'to' => $to];
        }
    }

    public function render()
    {
        return view('livewire.reports.dispute.dispute-report');
    }
}
