<?php

namespace App\Http\Livewire\Reports\General;

use App\Enum\CustomMessage;
use App\Enum\ReportStatus;
use App\Models\FinancialYear;
use App\Models\Parameter;
use App\Models\Report;
use App\Models\ReportParameter;
use App\Models\ReportRegister\RgSubCategory;
use App\Models\ReportType;
use App\Services\JasperReport\ReportService;
use App\Traits\CustomAlert;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Jaspersoft\Exception\RESTRequestException;
use Livewire\Component;

class Initial extends Component
{
    use CustomAlert;

    public $report_types = [];
    public $report_type_id;
    public $reports = [];
    public $report_code, $start_date, $format, $end_date;
    public $fileName, $report, $parameters = [];
    public $years = [], $months = [], $year = ReportStatus::all, $month = ReportStatus::all, $duration = ReportStatus::Year;

    public function mount()
    {
        $this->report_types = ReportType::query()
            ->select('id', 'name', 'permission')
            ->orderBy('name', 'ASC')
            ->get();

        $this->years = FinancialYear::query()
            ->select('code')
            ->where('code', '>=', 2022)
            ->where('code', '<=', now()->year)
            ->pluck('code')
            ->toArray();

        $this->months = ReportStatus::MONTHS_DESC;

        if ($this->report_type_id) {
            $this->reports = Report::query()
                ->select('code', 'name', 'has_parameter')
                ->where('report_type_id', $this->report_type_id)
                ->orderBy('name', 'ASC')
                ->get();
        }
    }


    public function updated($property)
    {
        if ($property === 'report_type_id') {
            $this->parameters = [];

            $this->reports = Report::query()
                ->select('code', 'name', 'has_parameter', 'id', 'report_url')
                ->where('report_type_id', $this->report_type_id)
                ->orderBy('name', 'ASC')
                ->get();
        }

        if ($property === 'report_code') {
            $this->report = Report::query()
                ->select('code', 'name', 'has_parameter', 'id', 'report_url')
                ->where('code', $this->report_code)
                ->where('report_type_id', $this->report_type_id)
                ->first();
            
            $this->parameters = ReportParameter::query()
                ->join('parameters p', 'p.id', '=', 'report_parameters.parameter_id')
                ->where('report_id', $this->report->id)
                ->whereNull('parent_id')
                ->get()
                ->toArray();
        }

        $genericProperty = explode('.', $property);

        if (isset($genericProperty[2]) && $genericProperty[2] === "value") {
            $parameter = $this->parameters[$genericProperty[1]];

            if (isset($parameter['model_name'])) {

                $childParameter = Parameter::where('parent_id', $parameter['id'])->first();
                $values = array_column($this->parameters, 'code');

                if ($childParameter && !in_array($childParameter->code, $values)) {
                    if (isset($childParameter->parent_id)) {
                        if ($childParameter->code === Parameter::RG_SUB_CATEGORY_ID) {
                            $childParameter->model_name = 'SELECT ID,NAME FROM RG_SUB_CATEGORIES WHERE RG_CATEGORY_ID ='. $parameter['value'];
                        }
                        $this->parameters[] = $childParameter;
                    } else {
                        $this->parameters[] = $childParameter;
                    }
                }

            }
        }

    }

    protected function rules()
    {
        return [
            'report_type_id' => 'required',
            'report_code' => 'required',
            'format' => 'required',
            'parameters.*.code' => 'nullable',
            'parameters.*.value' => 'nullable',
            'year' => 'nullable',
            'duration' => 'nullable',
            'start_date' => 'nullable',
            'end_date' => 'nullable'
        ];
    }

    public function messages()
    {
        return [
            'report_type_id.required' => 'The report type field is required.'
        ];
    }

    public function submit()
    {

        $formattedParameters = [];

        foreach ($this->parameters as $parameter) {
            $formattedParameters[$parameter['code']] = $parameter['value'] ?? null;
        }

        if ($this->duration === ReportStatus::range) {
            $formattedParameters['start_date'] = $this->start_date;
            $formattedParameters['end_date'] = $this->end_date;
        } else if ($this->duration === ReportStatus::Year) {
            if ($this->year === ReportStatus::all) {
                // Take all the years
                $formattedParameters['start_date'] = Carbon::create(2022)->startOfYear()->format('Y-m-d');
                $formattedParameters['end_date'] = Carbon::now()->endOfYear()->format('Y-m-d');
            } else {
                // Format months
                if ($this->month === ReportStatus::all) {
                    // Take all months
                    $formattedParameters['start_date'] = Carbon::create($this->year)->startOfYear()->format('Y-m-d');
                    $formattedParameters['end_date'] = Carbon::create($this->year)->endOfYear()->format('Y-m-d');
                } else {
                    // Take specific month
                    $formattedParameters['start_date'] = Carbon::create($this->year, $this->month)->startOfMonth()->format('Y-m-d');
                    $formattedParameters['end_date'] = Carbon::create($this->year, $this->month)->endOfMonth()->format('Y-m-d');
                }
            }
        }

        try {
            $filePath = (new ReportService())->getReport($this->report, $this->format, $formattedParameters);
            return response()->download($filePath)->deleteFileAfterSend(true);
        } catch (RESTRequestException $requestException) {
            $this->customAlert('error', $requestException->statusCode .': '. $requestException->getMessage());
        } catch (\Exception $e) {
            Log::error($e);
            $this->customAlert('error', CustomMessage::error());
        }
    }


    public function render()
    {
        return view('livewire.reports.general.initial');
    }
}
