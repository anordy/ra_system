<?php

namespace App\Http\Livewire\Reports\General;

use App\Enum\CustomMessage;
use App\Models\Parameter;
use App\Models\Report;
use App\Models\ReportParameter;
use App\Models\ReportType;
use App\Services\JasperReport\ReportService;
use App\Traits\CustomAlert;
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


    public function updated($property)
    {
        if ($property === 'report_type_id') {
            $this->parameters = [];

            $this->reports = Report::query()
                ->select('code', 'name', 'has_parameter', 'id', 'report_url')
                ->where('report_type_id', $this->report_type_id)
                ->get();
        }

        if ($property === 'report_code') {
            $this->report = Report::query()
                ->select('code', 'name', 'has_parameter', 'id', 'report_url')
//                ->where('code', $this->report_code)
                ->where('report_type_id', $this->report_type_id)
                ->first();
//dd($this->report ,$this->report_code,$property ,$this);
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
                    $this->parameters[] = $childParameter;
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
