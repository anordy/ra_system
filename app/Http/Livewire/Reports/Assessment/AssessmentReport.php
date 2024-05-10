<?php

namespace App\Http\Livewire\Reports\Assessment;

use App\Enum\CustomMessage;
use App\Enum\GeneralConstant;
use App\Enum\ReportStatus;
use App\Exports\AssessmentReportExport;
use App\Models\FinancialYear;
use App\Models\TaxType;
use App\Traits\AssessmentReportTrait;
use App\Traits\CustomAlert;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class AssessmentReport extends Component
{
    use CustomAlert, AssessmentReportTrait;

    public $optionYears;
    public $optionPeriods;
    public $optionSemiAnnuals;
    public $optionQuarters;
    public $optionMonths;
    public $optionTaxTypes;


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
            'tax_type_id' => 'required|string',
            'year' => 'required|strip_tag|string',
            'period' => $this->year != 'all' ? 'required|string' : '',
            'month' => $this->period == 'Monthly' ? 'required|numeric|min:1|max:12' : '',
            'quater' => $this->period == 'Quarterly' ? 'required|string' : '',
            'semiAnnual' => $this->period == 'Semi-Annual' ? 'required|string' : '',
        ];
    }

    public function mount()
    {
        $this->optionYears = FinancialYear::pluck('code');
        $this->optionTaxTypes = TaxType::query()
            ->select([
                'id',
                'name'
            ])
            ->whereIn('code', ['verification', 'investigation', 'audit'])
            ->get();

        $this->optionPeriods = [ReportStatus::MONTHLY, ReportStatus::QUARTERLY, ReportStatus::SEMI_ANNUAL, ReportStatus::ANNUAL];
        $this->optionSemiAnnuals = [ReportStatus::FIRST_SEMI_ANNUAL, ReportStatus::SECOND_SEMI_ANNUAL];
        $this->optionQuarters = [ReportStatus::FIRST_QUARTER, ReportStatus::SECOND_QUARTER, ReportStatus::THIRD_QUARTER, ReportStatus::FOURTH_QUARTER];
        $this->optionMonths = ReportStatus::MONTHS_DESC;
    }

    public function updated($propertyName)
    {
        if ($propertyName == 'period') {
            $this->reset('month', 'quater', 'semiAnnual');
        }
        if ($propertyName == 'year') {
            $this->reset('month', 'quater', 'semiAnnual', 'period');
        }
    }

    public function exportExcel()
    {
        $this->validate();

        try {
            $parameters = $this->getParameters();

            if (!isset($parameters['dates']) ||
                !isset($parameters['type']) ||
                !isset($parameters['year']) ||
                !isset($parameters['period']) ||
                !isset($parameters['month']) ||
                !isset($parameters['quater']) ||
                !isset($parameters['semiAnnual']) ||
                !isset($parameters['tax_type_id'])) {
                throw new \InvalidArgumentException("Missing required parameters");
            }

            $records = $this->getRecords($parameters);
            if ($records->count() < 1) {
                $this->customAlert('error', 'No Records Found in the selected criteria');
                return;
            }

            if ($parameters['tax_type_id'] == ReportStatus::all) {
                $tax_type = ReportStatus::All;
            } else {
                $tax_type = TaxType::findOrFail($parameters['tax_type_id']);
            }

            if ($parameters['year'] == ReportStatus::all) {
                if ($tax_type == ReportStatus::All) {
                    $fileName = $tax_type . '_' . 'Assessments' . '.xlsx';
                    $title = 'Notice of Assessments' . ' For ' . $tax_type;
                } else {
                    $fileName = $tax_type->name . '_' . 'Assessments' . '.xlsx';
                    $title = 'Notice of Assessments' . ' For ' . $tax_type->name;
                }

            } else {
                if ($tax_type == ReportStatus::All) {
                    $fileName = $tax_type . '_' . 'Assessments' . ' - ' . $parameters['year'] . '.xlsx';
                    $title = 'Assessments' . ' For ' . $tax_type . '-' . $parameters['year'];
                } else {
                    $fileName = $tax_type->name . '_' . 'Assessments' . ' - ' . $parameters['year'] . '.xlsx';
                    $title = 'Assessments' . ' For ' . $tax_type->name . '-' . $parameters['year'];
                }
            }
            $this->customAlert('success', 'Exporting Excel File');
            return Excel::download(new AssessmentReportExport($records, $title, $parameters), $fileName);
        } catch (\Exception $exception){
            Log::error('ASSESSMENT-REPORT-EXPORT-XSL', [$exception]);
            $this->customAlert(GeneralConstant::ERROR, CustomMessage::error());
        }

    }

    public function exportPdf()
    {
        $this->validate();
        $parameters = $this->getParameters();
        $records = $this->getRecords($parameters);
        if ($records->count() < 1) {
            $this->customAlert('error', 'No Records Found in the selected criteria');
            return;
        }
        $this->customAlert('success', 'Exporting Pdf File');
        return redirect()->route('reports.assessments.download.pdf', encrypt(json_encode($parameters)));
    }

    public function preview()
    {
        $this->validate();
        $parameters = $this->getParameters();
        $records = $this->getRecords($parameters)->get();
        if ($records->count() < 1) {
            $this->customAlert('error', 'No Records Found in the selected criteria');
            return;
        }
        return redirect()->route('reports.assessments.preview', encrypt(json_encode($this->getParameters())));
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
            $date = Carbon::parse($this->year . "-" . $this->month . "-01");
            $start = $date->startOfMonth()->format('Y-m-d H:i:s');
            $end = $date->endOfMonth()->format('Y-m-d H:i:s');
            $from = $date->startOfMonth()->format('Y-m-d');
            $to = $date->endOfMonth()->format('Y-m-d');
            return ['startDate' => $start, 'endDate' => $end, 'from' => $from, 'to' => $to];
        } elseif ($this->quater) {

            if ($this->quater == ReportStatus::FIRST_QUARTER) {
                $this->startMonth = ReportStatus::January;
                $this->endMonth = ReportStatus::March;
            } elseif ($this->quater == ReportStatus::SECOND_QUARTER) {
                $this->startMonth = ReportStatus::April;
                $this->endMonth = ReportStatus::June;
            } elseif ($this->quater == ReportStatus::THIRD_QUARTER) {
                $this->startMonth = ReportStatus::July;
                $this->endMonth = ReportStatus::September;
            } elseif ($this->quater == ReportStatus::FOURTH_QUARTER) {
                $this->startMonth = ReportStatus::October;
                $this->endMonth = ReportStatus::December;
            }

            $startDate = Carbon::parse($this->year . "-" . $this->startMonth . "-01");
            $endDate = Carbon::parse($this->year . "-" . $this->endMonth . "-01");
            $start = $startDate->startOfMonth()->format('Y-m-d H:i:s');
            $end = $endDate->endOfMonth()->format('Y-m-d H:i:s');
            $from = $startDate->format('Y-m-d');
            $to = $endDate->format('Y-m-d');
            return ['startDate' => $start, 'endDate' => $end, 'from' => $from, 'to' => $to];
        } elseif ($this->semiAnnual) {
            if ($this->semiAnnual == ReportStatus::FIRST_SEMI_ANNUAL) {
                $this->startMonth = ReportStatus::January;
                $this->endMonth = ReportStatus::June;
            } elseif ($this->semiAnnual == ReportStatus::SECOND_SEMI_ANNUAL) {
                $this->startMonth = ReportStatus::July;
                $this->endMonth = ReportStatus::December;
            }
            $startDate = Carbon::parse($this->year . "-" . $this->startMonth . "-01");
            $endDate = Carbon::parse($this->year . "-" . $this->endMonth . "-01");
            $start = $startDate->startOfMonth()->format('Y-m-d H:i:s');
            $end = $endDate->endOfMonth()->format('Y-m-d H:i:s');
            $from = $startDate->format('Y-m-d');
            $to = $endDate->format('Y-m-d');
            return ['startDate' => $start, 'endDate' => $end, 'from' => $from, 'to' => $to];
        } else {
            $startDate = Carbon::parse($this->year . "-" . "01" . "-01");
            $endDate = Carbon::parse($this->year . "-" . "12" . "-01");
            $start = $startDate->startOfMonth()->format('Y-m-d H:i:s');
            $end = $endDate->endOfMonth()->format('Y-m-d H:i:s');
            $from = $startDate->format('Y-m-d');
            $to = $endDate->format('Y-m-d');
            return ['startDate' => $start, 'endDate' => $end, 'from' => $from, 'to' => $to];
        }
    }

    public function render()
    {
        return view('livewire.reports.assessment.assessment-report');
    }
}
