<?php

namespace App\Http\Livewire\Reports\Registration;

use App\Exports\BusinessRegByLastTurnOverReportExport;
use App\Exports\BusinessRegByNatureReportExport;
use App\Exports\BusinessRegByNextTurnOverReportExport;
use App\Exports\BusinessRegByTaxTypeReportExport;
use App\Http\Livewire\Reports\Registration\Previews\Business\BusinessTurnOverNextPreviewTable;
use App\Models\ISIC1;
use App\Models\TaxType;
use App\Traits\RegistrationReportTrait;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class RegistrationReport extends Component
{
    use RegistrationReportTrait, LivewireAlert;

    public $optionReportTypes;
    public $optionIsic1s;
    public $optionTaxTypes;
    public $optionTurnOverTypes;

    public $reportType;
    public $isic1Id;
    public $tax_type_id;
    public $turn_over_type;
    public $turn_over_from_amount;
    public $turn_over_to_amount;

    public function rules()
    {
        return [
            'reportType' => 'required',
            'isic1Id' => $this->reportType == 'Business-Reg-By-Nature' ? 'required' : '',
            'tax_type_id' => $this->reportType == 'Business-Reg-By-TaxType' ? 'required' : '',
            'turn_over_type' => $this->reportType =='Business-Reg-By-Turn-Over' ? 'required' : '',
            'turn_over_from_amount' => $this->reportType =='Business-Reg-By-Turn-Over' ? 'required' : '',
            'turn_over_to_amount' => $this->reportType =='Business-Reg-By-Turn-Over' ? 'required' : '',
        ];
    }

    public function mount()
    {
        $this->optionReportTypes = [
            'Business-Reg-By-Nature' => 'Registered Business By Nature of Business',
            'Business-Reg-By-TaxType' => 'Registered Business By Tax Type',
            'Business-Reg-By-Turn-Over' => 'Registered Business By Turn Over',
        ];
        $this->optionIsic1s = ISIC1::all();
        $this->optionTaxTypes = TaxType::all();
        $this->optionTurnOverTypes=[
            'Last-12-Months' => 'Turn Over for Last 12 Months',
            'Next-12-Months' => 'Turn Over for Next 12 Months',
        ];

    }

    public function updated($propertyName)
    {

        if ($propertyName == 'reportType') {
            $this->reset('isic1Id', 'tax_type_id','turn_over_type','turn_over_from_amount','turn_over_to_amount');
        }

    }

    //preview function 
    public function preview()
    {
        $this->validate();
        if ($this->reportType == 'Business-Reg-By-Nature') {
            if ($this->hasBusinessByNature($this->isic1Id)) {
                return redirect()->route('reports.registration.business-by-nature.preview', encrypt($this->isic1Id));
            } else {
                return $this->alert('error', 'No data for this selection');
            }
        }
        
        elseif($this->reportType == 'Business-Reg-By-TaxType'){
            if ($this->hasBusinessByTaxType($this->tax_type_id)) {
                return redirect()->route('reports.registration.business-by-tax-type.preview',encrypt($this->tax_type_id));
            } else {
                return $this->alert('error', 'No data for this selection');
            }
        }

        elseif($this->reportType == 'Business-Reg-By-Turn-Over'){
            if($this->turn_over_type == 'Last-12-Months'){
                if($this->hasBusinessByTurnOverLast($this->turn_over_from_amount,$this->turn_over_to_amount)){
                    return redirect()->route('reports.registration.business-by-turn-over-last.preview',[$this->turn_over_from_amount,$this->turn_over_to_amount]);
                }else{
                    return $this->alert('error', 'No data for this selection');
                }
            }
            if($this->turn_over_type == 'Next-12-Months'){
                if($this->hasBusinessByTurnOverNext($this->turn_over_from_amount,$this->turn_over_to_amount)){
                    return redirect()->route('reports.registration.business-by-turn-over-next.preview',[$this->turn_over_from_amount,$this->turn_over_to_amount]);
                }else{
                    return $this->alert('error', 'No data for this selection');
                }
            }
        }
    }

    //export excel
    public function exportExcel()
    {
        $this->validate();
        if ($this->reportType == 'Business-Reg-By-Nature') {
            if ($this->hasBusinessByNature($this->isic1Id)) {
                $this->alert('success', 'Exporting Excel File');
                return Excel::download(new BusinessRegByNatureReportExport($this->isic1Id), 'Business By Nature.xlsx');
            } else {
                return $this->alert('error', 'No data for this selection');
            }

        }elseif($this->reportType == 'Business-Reg-By-TaxType'){
            if ($this->hasBusinessByTaxType($this->tax_type_id)) {
                return Excel::download(new BusinessRegByTaxTypeReportExport($this->tax_type_id), 'Business By TaxType.xlsx');
            } else {
                return $this->alert('error', 'No data for this selection');
            }
        }

        elseif($this->reportType == 'Business-Reg-By-Turn-Over'){
            if($this->turn_over_type == 'Last-12-Months'){
                if($this->hasBusinessByTurnOverLast($this->turn_over_from_amount,$this->turn_over_to_amount)){
                    return Excel::download(new BusinessRegByLastTurnOverReportExport($this->turn_over_from_amount,$this->turn_over_to_amount), 'Business By TurnOver-Last 12 Months.xlsx');
                }else{
                    return $this->alert('error', 'No data for this selection');
                }
            }
            if($this->turn_over_type == 'Next-12-Months'){
                if($this->hasBusinessByTurnOverNext($this->turn_over_from_amount,$this->turn_over_to_amount)){
                    return Excel::download(new BusinessRegByNextTurnOverReportExport($this->turn_over_from_amount,$this->turn_over_to_amount), 'Business By TurnOver-Last 12 Months.xlsx');
                }else{
                    return $this->alert('error', 'No data for this selection');
                }
            }
        }
    }

    public function exportPdf()
    {
        $this->validate();
        if ($this->reportType == 'Business-Reg-By-Nature') {
            if ($this->hasBusinessByNature($this->isic1Id)) {
                return redirect()->route('reports.registration.business-by-nature.pdf',encrypt($this->isic1Id));
            } else {
                return $this->alert('error', 'No data for this selection');
            }
        }elseif($this->reportType == 'Business-Reg-By-TaxType'){
            if ($this->hasBusinessByTaxType($this->tax_type_id)) {
                return redirect()->route('reports.registration.business-by-tax-type.pdf',encrypt($this->tax_type_id));
            } else {
                return $this->alert('error', 'No data for this selection');
            }
        }

        elseif($this->reportType == 'Business-Reg-By-Turn-Over'){
            if($this->turn_over_type == 'Last-12-Months'){
                if($this->hasBusinessByTurnOverLast($this->turn_over_from_amount,$this->turn_over_to_amount)){
                    return redirect()->route('reports.registration.business-by-turn-over-last.pdf',[$this->turn_over_from_amount,$this->turn_over_to_amount]);
                }else{
                    return $this->alert('error', 'No data for this selection');
                }
            }
            if($this->turn_over_type == 'Next-12-Months'){
                if($this->hasBusinessByTurnOverNext($this->turn_over_from_amount,$this->turn_over_to_amount)){
                    return redirect()->route('reports.registration.business-by-turn-over-next.pdf',[$this->turn_over_from_amount,$this->turn_over_to_amount]);
                }else{
                    return $this->alert('error', 'No data for this selection');
                }
            }
        }
    }

    public function render()
    {
        return view('livewire.reports.registration.registration-report');
    }
}
