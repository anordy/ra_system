<?php

namespace App\Http\Livewire\Reports\Registration;

use App\Exports\BusinessRegByLastTurnOverReportExport;
use App\Exports\BusinessRegByNatureReportExport;
use App\Exports\BusinessRegByNextTurnOverReportExport;
use App\Exports\BusinessRegByTaxTypeReportExport;
use App\Http\Livewire\Reports\Registration\Previews\Business\BusinessTurnOverNextPreviewTable;
use App\Models\ISIC1;
use App\Models\ISIC2;
use App\Models\ISIC3;
use App\Models\ISIC4;
use App\Models\TaxRegion;
use App\Models\TaxType;
use App\Traits\RegistrationReportTrait;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class RegistrationReport extends Component
{
    use RegistrationReportTrait, LivewireAlert;

    public $optionReportTypes;
    public $optionIsic1s = [];
    public $optionIsic2s = [];
    public $optionIsic3s = [];
    public $optionIsic4s = [];
    public $optionTaxTypes =[];
    public $optionTurnOverTypes=[];
    public $optionTaxRegions=[];
    public $optionYears=[];

    public $reportType;
    public $isic1Id;
    public $isic2Id;
    public $isic3Id;
    public $isic4Id;
    public $tax_type_id;
    public $turn_over_type;
    public $turn_over_from_amount;
    public $turn_over_to_amount;
    public $tax_region_id;


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
        $this->optionTaxTypes = TaxType::where('category','main')->get();
        $this->optionTaxRegions = TaxRegion::all();
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

        if ($propertyName == 'isic1Id') {
            if($this->isic1Id != null){
                $this->optionIsic2s = ISIC2::where('isic1_id',$this->isic1Id)->get();
            }
            $this->reset('isic2Id','isic3Id','isic4Id');
        }

        if ($propertyName == 'isic2Id') {
            if($this->isic2Id != null){
                $this->optionIsic3s = ISIC3::where('isic2_id',$this->isic2Id)->get();
            }
            $this->reset('isic3Id','isic4Id');
        }

        if ($propertyName == 'isic3Id') {
            if($this->isic3Id != null){
                $this->optionIsic4s = ISIC4::where('isic3_id',$this->isic3Id)->get();
            }
            $this->reset('isic4Id');
        }

    }

    //preview function 
    public function preview()
    {
        $this->validate();
        if ($this->reportType == 'Business-Reg-By-Nature') {
            if($this->isic4Id){
                $level = 4;
                if ($this->hasBusinessByNatureIsic4($this->isic4Id)) {
                    return redirect()->route('reports.registration.business-by-nature.preview', [encrypt($this->isic4Id),encrypt($level)]);
                } 
                return $this->alert('error', 'No data for this selection');
            }elseif($this->isic3Id){
                $level = 3;
                if ($this->hasBusinessByNatureIsic3($this->isic3Id)) {
                    return redirect()->route('reports.registration.business-by-nature.preview', [encrypt($this->isic3Id),encrypt($level)]);
                } 
                return $this->alert('error', 'No data for this selection');
            }elseif($this->isic2Id){
                $level = 2;
                if ($this->hasBusinessByNatureIsic2($this->isic2Id)) {
                    return redirect()->route('reports.registration.business-by-nature.preview', [encrypt($this->isic2Id),encrypt($level)]);
                } 
                return $this->alert('error', 'No data for this selection');
            }elseif($this->isic1Id){
                $level = 1;
                if ($this->hasBusinessByNatureIsic4($this->isic1Id)) {
                    return redirect()->route('reports.registration.business-by-nature.preview', [encrypt($this->isic1Id),encrypt($level)]);
                } 
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
