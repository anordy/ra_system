<?php

namespace App\Http\Livewire\Reports\Claims;

use App\Enum\CustomMessage;
use App\Enum\ReportStatus;
use App\Enum\TaxClaimStatus;
use App\Exports\ClaimsReportExport;
use App\Models\Taxpayer;
use App\Traits\ClaimReportTrait;
use App\Traits\GenericReportTrait;
use Illuminate\Support\Facades\Gate;
use App\Traits\CustomAlert;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class ClaimsReport extends Component
{
    use CustomAlert, ClaimReportTrait, GenericReportTrait;

    public $optionYears;
    public $optionPeriods;
    public $optionSemiAnnuals;
    public $optionQuarters;
    public $optionMonths;
    public $optionTaxPayers;
    public $showPreviewTable = false;
    public $activateButtons = false;


    public $year;
    public $month;
    public $period;
    public $quater;
    public $semiAnnual;
    public $taxpayer = 'all';
    public $status;
    public $payment_status;
    public $payment_method;
    public $duration;
    public $from;
    public $to;
    public $today;
    public $startMonth;
    public $endMonth;

    protected function rules()
    {
        return [
            'status' => 'required|alpha_gen',
            'duration' => 'required|alpha_gen',
            'year' => $this->duration == ReportStatus::yearly ? 'required|alpha_gen' : 'nullable',
            'from' => $this->duration == ReportStatus::date_range ? 'required|date' : 'nullable',
            'to' => $this->duration == ReportStatus::date_range ? 'required|date|after:from' : 'nullable',
            'payment_status' => $this->status == TaxClaimStatus::APPROVED || $this->status == ReportStatus::all ? 'required|alpha_gen' : 'nullable',
            'payment_method'=> $this->status == TaxClaimStatus::APPROVED || $this->status == ReportStatus::all ? 'required|alpha_gen' : 'nullable',
            'period' => $this->year != ReportStatus::all && !empty($this->year) ? 'required' : 'nullable',
            'month' => $this->period == ReportStatus::MONTHLY ? 'required|alpha_gen' : 'nullable',
            'quater' => $this->period == ReportStatus::QUARTERLY ? 'required|alpha_gen' : 'nullable',
            'semiAnnual' => $this->period == ReportStatus::SEMI_ANNUAL ? 'required|alpha_gen' : 'nullable',
        ];
    }

    public function mount()
    {
        $this->today = date('Y-m-d');
        $this->initializeOptions();
        $this->optionTaxPayers = Taxpayer::query()->select('id', 'first_name', 'middle_name', 'last_name')->orderBy('first_name')->get();
    }

    public function updated($propertyName)
    {
        if ($propertyName == ReportStatus::Period) {
            $this->reset('month', 'quater', 'semiAnnual');
        }
        if ($propertyName == ReportStatus::yearly) {
            $this->reset('month', 'quater', 'semiAnnual', 'period');
        }

        if ($this->status == TaxClaimStatus::REJECTED or $this->status == TaxClaimStatus::PENDING) {
            $this->payment_status = '';
            $this->payment_method = '';
        }

        if ($this->duration ==  ReportStatus::yearly) {
            $this->from = '';
            $this->to = '';
        } else {
            $this->year = '';
        }
    }

    public function preview()
    {
        if (!Gate::allows('managerial-claim-report-preview')) {
            abort(403);
        }
        $this->validate();
        try {
            $parameters = $this->getParameters();
            $records = $this->getRecords($parameters);
            if ($records->count() < 1) {
                $this->customAlert('error', 'No Records Found in the selected criteria');
                return;
            }
            return redirect()->route('reports.claims.preview', encrypt(json_encode($this->getParameters())));
        } catch (\Exception $exception) {
            Log::error('REPORTS-CLAIMS-CLAIMS-REPORT-PREVIEW', [$exception]);
            $this->customAlert('error', CustomMessage::ERROR);
        }

    }

    public function exportPdf()
    {
        if (!Gate::allows('managerial-claim-report-pdf')) {
            abort(403);
        }
        $this->validate();
        try {
            $parameters = $this->getParameters();
            $records = $this->getRecords($parameters);
            if ($records->count() < 1) {
                $this->customAlert('error', 'No Records Found in the selected criteria');
                return;
            }
            $this->customAlert('success', 'Exporting Pdf File');
            return redirect()->route('reports.claim.download.pdf', encrypt(json_encode($parameters)));
        } catch (\Exception $exception) {
            Log::error('REPORTS-CLAIMS-CLAIMS-REPORT-EXPORT-PDF', [$exception]);
            $this->customAlert('error', CustomMessage::ERROR);
        }

    }

    public function exportExcel()
    {
        if (!Gate::allows('managerial-claim-report-excel')) {
            abort(403);
        }
        $this->validate();

        try {
            $parameters = $this->getParameters();
            $records = $this->getRecords($parameters);
            if ($records->count() < 1) {
                $this->customAlert('error', 'No Records Found in the selected criteria');
                return;
            }

            if (!array_key_exists('from', $parameters['dates']) && !array_key_exists('to', $parameters['dates'])) {
                throw new \Exception('Missing from and to keys in parameters');
            }

            if (isset($parameters['duration']) && $parameters['duration'] == ReportStatus::yearly) {
                if (isset($parameters['year']) && $parameters['year'] == ReportStatus::all) {
                    $fileName = 'claim_report.xlsx';
                    $title = 'All Claim reports';
                } else {

                    if (isset($parameters['status']) && $parameters['status'] != ReportStatus::all) {
                        $fileName = $parameters['status'].'_claim_report.xlsx';
                        $title = $parameters['status'] . ' claim reports from ' . $parameters['dates']['from'] . ' to ' . $parameters['dates']['to'] . '';
                    } else {
                        $fileName = 'claim_report.xlsx';
                        $title = 'All claim reports from ' . $parameters['dates']['from'] . ' to ' . $parameters['dates']['to'] . '';
                    }
                }
            } else {
                if (isset($parameters['status']) && $parameters['status'] != ReportStatus::all) {
                    $fileName = $parameters['status'].'_claim_report.xlsx';
                    $title = $parameters['status'] . ' claim reports from ' . $parameters['from'] . ' to ' . $parameters['to'] . '';
                } else {
                    $fileName = 'claim_report.xlsx';
                    $title = 'All Claim reports from ' . $parameters['from'] . ' to ' . $parameters['to'] . '';
                }
            }

            $this->customAlert('success', 'Exporting Excel File');
            return Excel::download(new ClaimsReportExport($records, $title, $parameters), $fileName);
        } catch (\Exception $exception) {
            Log::error('REPORTS-CLAIMS-CLAIMS-REPORT-EXPORT-EXCEL', [$exception]);
            $this->customAlert('error', CustomMessage::ERROR);
        }

    }


    public function getParameters()
    {
        return [
            'tax_payer_id' => $this->taxpayer ?? ReportStatus::all,
            'status' => $this->status,
            'duration' => $this->duration,
            'payment_status' => $this->payment_status,
            'payment_method' => $this->payment_method,
            'from' => $this->from,
            'to' => $this->to,
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
        } elseif ($this->month >= 1 & $this->month <= 12) {
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
        return view('livewire.reports.claims.claims-report');
    }
}
