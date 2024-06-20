<?php

namespace App\Http\Livewire\Reports\TaxPayer;

use App\Enum\ReportFormats;
use App\Enum\TaxPayerReportCodes;
use App\Models\Reports\Report;
use App\Models\Reports\ReportType;
use App\ReportHelpers\TaxPayerReport;
use App\Traits\CustomAlert;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Barryvdh\DomPDF\Facade\Pdf;

class TaxPayer extends Component
{
    use CustomAlert;

    public $report_types = [];
    public $report_type_id;
    public $reports = [];
    public $report_code, $start_date, $format, $end_date;
    public $fileName;

    public function mount()
    {
        $this->report_types = ReportType::query()
            ->select('id', 'name')->get();

        if ($this->report_type_id) {
            $this->reports = Report::query()
                ->select('code', 'name', 'has_parameter')
                ->where('report_type_id', $this->report_type_id)
                ->get();
        }
    }

    public function render()
    {
        return view('livewire.reports.tax-payer.tax-payer');
    }

    public function updated($property)
    {
        if ($property == 'report_type_id') {
            $this->reports = Report::query()
                ->select('code', 'name', 'has_parameter', 'id')
                ->where('report_type_id', $this->report_type_id)
                ->get();
        }

    }

    protected function rules()
    {
        return [
            'report_type_id' => 'required',
            'report_code' => 'required',
            'format' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'report_type_id.required' => 'The report type field is required.
'
        ];
    }

    public function submit()
    {
        $this->validate();

        $report_type_id = $this->report_type_id;
        $start_date = $this->start_date;
        $end_date = $this->end_date;
        $report_code = $this->report_code;

        try {

            if ($report_code == TaxPayerReportCodes::GfsData) {
                $results = self::getGfsData($start_date, $end_date);
            } elseif ($report_code == TaxPayerReportCodes::TaxPayerData) {
                $results = $this->getTaxPayerData($start_date, $end_date);
            } elseif ($report_code == TaxPayerReportCodes::TaxPayerContributionData) {
                $results = $this->getTaxPayerContributionData($start_date, $end_date);
            } elseif ($report_code == TaxPayerReportCodes::TaxPayerForPastTwelveMonth) {
                $results = TaxPayerReport::getTaxPayerForPastTwelveMonth();
            } elseif ($report_code == TaxPayerReportCodes::HotelDataReport) {
                $results = $this->getHotelDataReport($start_date, $end_date);
            } elseif ($report_code == TaxPayerReportCodes::RentingPremisses) {
                $results = $this->getRentingPremisses($start_date, $end_date);
            } elseif ($report_code == TaxPayerReportCodes::FiledTaxPayer) {
                $results = $this->getFiledTaxPayer($start_date, $end_date);
            } elseif ($report_code == TaxPayerReportCodes::NonFiledTaxPayer) {
                $results = $this->getNonFiledTaxPayer($start_date, $end_date);
            } elseif ($report_code == '108') {
                $results = $this->getFiledTaxPayer($start_date, $end_date);
            }


            if (empty($results)) {
                $this->customAlert('error', 'No Records Found in the selected criteria');
                return redirect()->back();

            }

            $payload = [
                'start_date' => $start_date,
                'end_date' => $end_date,
                'code' => $report_code
            ];

            if ($this->format == ReportFormats::PDF) {
                $this->fileName = $this->exportPdf($results, $payload);
                $this->customAlert('success', 'Report exported successfully. Download the Pdf File');

            }

        } catch (\Exception $e) {
            Log::error($e);
            return response()->json(['error' => $e->getMessage()], 500);
        }

    }

    public static function getRentingPremisses($start_date, $end_date)
    {
        if (is_null($start_date)) {
            // Fetch data without date range
            $results = TaxPayerReport::getRentingPremissesData(false, null);
        } else {
            // Fetch data with date range
            $results = TaxPayerReport::getRentingPremissesData(false, ['start_date' => $start_date, 'end_date' => $end_date]);
        }
        return $results;
    }

    public static function getTaxPayerContributionData($start_date, $end_date)
    {
        if (is_null($start_date)) {
            // Fetch data without date range
            $results = TaxPayerReport::getTaxPayerContribution(false, null);
        } else {
            // Fetch data with date range
            $results = TaxPayerReport::getTaxPayerContribution(false, ['start_date' => $start_date, 'end_date' => $end_date]);
        }
        return $results;
    }

    public static function getTaxPayerData($start_date, $end_date)
    {
        if (is_null($start_date)) {
            // Fetch data without date range
            $results = TaxPayerReport::getTaxPayer(false, null);
        } else {
            // Fetch data with date range
            $results = TaxPayerReport::getTaxPayer(false, ['start_date' => $start_date, 'end_date' => $end_date]);
        }
        return $results;
    }

    public static function getHotelDataReport($start_date, $end_date)
    {
        if (is_null($start_date)) {
            // Fetch data without date range
            $results = TaxPayerReport::getHotelData(false, null);
        } else {
            // Fetch data with date range
            $results = TaxPayerReport::getHotelData(false, ['start_date' => $start_date, 'end_date' => $end_date]);
        }
        return $results;
    }

    public static function getGfsData($start_date, $end_date)
    {
        if (is_null($start_date)) {
            // Fetch data without date range
            $results = self::gfsCodeRevenue(false, null);
        } else {
            // Fetch data with date range
            $results = self::gfsCodeRevenue(false, ['start_date' => $start_date, 'end_date' => $end_date]);
        }
        return $results;
    }

    public static function gfsCodeRevenue($selector, $data)
    {

        if ($selector) {
            $data = DB::select("
                    SELECT t.ID, t.created_at, t.PAYMENT_STATUS, t.TOTAL_AMOUNT, su.GFS_CODE, su.NAME AS SUB_VAT_NAME
                    FROM TAX_RETURNS t
                    INNER JOIN SUB_VATS su ON su.ID = t.SUB_VAT_ID
                    WHERE t.PAYMENT_STATUS = 'complete'
                ");
        } else {
            $data = DB::select("
                    SELECT t.ID, t.created_at, t.PAYMENT_STATUS, t.TOTAL_AMOUNT, su.GFS_CODE, su.NAME AS SUB_VAT_NAME
                    FROM TAX_RETURNS t
                    INNER JOIN SUB_VATS su ON su.ID = t.SUB_VAT_ID
                    WHERE t.PAYMENT_STATUS = 'complete'
                    AND t.created_at BETWEEN TO_DATE(:start_date, 'YYYY-MM-DD') AND TO_DATE(:end_date, 'YYYY-MM-DD')
                ", [
                'start_date' => $data['start_date'],
                'end_date' => $data['end_date']
            ]);
        }

        return $data;
    }

    private function getFiledTaxPayer($start_date, $end_date)
    {
        if (is_null($start_date)) {
            // Fetch data without date range
            $results = TaxPayerReport::getFiledTaxPayerData(false, null);
        } else {
            // Fetch data with date range
            $results = TaxPayerReport::getFiledTaxPayerData(false, ['start_date' => $start_date, 'end_date' => $end_date]);
        }
        return $results;
    }
    public function exportPdf($records, $payload)
    {
        $code = $payload['code'];

        Log::info("Export PDF called with code: {$code}");
        $dateRange = '';
        if (!empty($payload['start_date']) && !empty($payload['end_date'])) {
            $dateRange = ' From ' . $payload['start_date'] . ' to ' . $payload['end_date'];
        }

        switch ($code) {
            case TaxPayerReportCodes::GfsData:
                $view = 'exports.tax-payer.reports.pdf.gfs-data';
                $tittle = 'GFS Code data';
                $fileName = 'gfs_code_data';
                break;
            case TaxPayerReportCodes::TaxPayerData:
                $view = 'exports.tax-payer.reports.pdf.tax-payer-data';
                $tittle = 'Tax payer data';
                $fileName = 'taxpayer_data';
                break;
            case TaxPayerReportCodes::TaxPayerContributionData:
                $view = 'exports.tax-payer.reports.pdf.tax-payer-contribution';
                $tittle = 'Tax payer contribution data';
                $fileName = 'taxpayer_contribution_data';
                break;
            case TaxPayerReportCodes::TaxPayerForPastTwelveMonth:
                $view = 'exports.tax-payer.reports.pdf.tax-payer-twelve-month';
                $tittle = 'Tax payers for past twelve months';
                $fileName = 'taxpayer_twelve_months';
                break;
            case TaxPayerReportCodes::HotelDataReport:
                $view = 'exports.tax-payer.reports.pdf.hotel-data';
                $tittle = 'Hotel data';
                $fileName = 'hotel_data';
                break;
            case TaxPayerReportCodes::RentingPremisses:
                $view = 'exports.tax-payer.reports.pdf.renting-premises';
                $tittle = 'Renting premises report';
                $fileName = 'renting_premises';
                break;
            case TaxPayerReportCodes::FiledTaxPayer:
                $view = 'exports.tax-payer.reports.pdf.filed-tax-payer';
                $tittle = 'Filed Tax payers report';
                $fileName = 'file_taxpayer';
                break;
            case TaxPayerReportCodes::NonFiledTaxPayer:
                $view = 'exports.tax-payer.reports.pdf.non-filed-tax-payer';
                $tittle = 'Non Filed Tax payers report'.$dateRange;
                $fileName = 'non_filed_taxpayer';
                break;
            default:
                Log::error("No matching case for code: {$code}");
                return;
        }

        $tittle = 'Report for '.$tittle.$dateRange;

        $pdf = PDF::loadView($view, compact('records', 'tittle'));
        $pdf->setPaper('a4', 'landscape');
        $pdf->setOption(['dpi' => 150, 'defaultFont' => 'sans-serif']);
        $fileName = $fileName.'_' . time() . '.pdf';

        $directory = storage_path('app/public/reports');
        $filePath = $directory . '/' . $fileName;

        if (!file_exists($directory)) {
            mkdir($directory, 0777, true);
        }

        $pdf->save($filePath);
        return $fileName;

    }

}
