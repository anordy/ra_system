<?php

namespace App\Http\Livewire\Relief;

use App\Models\Business;
use App\Models\BusinessLocation;
use App\Models\Relief\Relief;
use App\Models\Relief\ReliefMinistry;
use App\Models\Relief\ReliefProject;
use App\Models\Relief\ReliefProjectList;
use App\Models\Relief\ReliefSponsor;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Gate;
use App\Traits\CustomAlert;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class ReliefGenerateReport extends Component
{

    use CustomAlert;
    //values for period selects
    public $year;
    public $period;
    public $month;
    public $quater;
    public $semiAnnual;

    //values for report type selects
    public $reportType;
    public $projectSectionId;
    public $projectId;
    public $ministryId;
    public $supplierId;
    public $supplierLocationId;
    public $sponsorId;

    //filter unique checkboxes
    public $filterIncludeNonMinistry;
    public $filterIncludeNonSponsor;

    //select options
    public $optionYears;
    public $optionPeriods;
    public $optionMonths;
    public $optionQuarters;
    public $optionSemiAnnuals;
 
    public $optionReportTypes;
    public $optionProjectSections;
    public $optionProjects;

    public $optionMinistries;

    public $optionSuppliers;
    public $optionSupplierLocations;
    
    public $optionSponsors;

    //hide/show elements
    public $showOptions;
    public $showMonths;
    public $showQuarters;
    public $showSemiAnnuals;
    public $showMoreFilters;

    public $showProjectSections = true;
    public $showProjects = false;
    public $showSponsors = false;
    public $showSuppliers = false;
    public $showSuppliersLocations = false;
    public $showMinistries = false;

    //backend variables
    public $startMonth;
    public $endMonth;
    public $dates;
    public $isCeilingReport = false;


    public function mount()
    {
        //set option report types
        $this->optionReportTypes = [
            'project' => 'By Project',
            'sponsor' => 'By Sponsor',
            'supplier' => 'By Supplier',
            'ministry' => 'By Ministry',
            'ceiling' => 'Ceiling Report',
        ];
        $this->reportType = 'project';
        $this->projectSectionId = 'all';
        $this->optionProjectSections = ReliefProject::orderBy('name', 'asc')->get();
        $this->projectId = 'all';

        $this->optionMinistries = ReliefMinistry::orderBy('name','asc')->get();

        $this->optionSuppliers = Business::orderBy('name','asc')->get();
        $this->optionSponsors = ReliefSponsor::orderBy('name','asc')->get();

        //set current year at first
        $this->year = date('Y');
        $this->period = 'Monthly';
        $this->month = strval(intval(date('m')));
        $this->quater = "1st-Quarter";
        $this->semiAnnual = "1st-Semi-Annual";

        //get options for years
        $optionStartYear = 2020;
        $this->optionYears = range($optionStartYear, date('Y'));

        //add All to year options
        $this->optionYears[] = "All";
        //sort array
        rsort($this->optionYears);

        //set values
        $this->optionPeriods = ["Monthly", "Quarterly", "Semi-Annual", "Annual"];
        $this->optionMonths = array(1 => "January", 2 => "February", 3 => "March", 4 => "April", 5 => "May", 6 => "June", 7 => "July", 8 => "August", 9 => "September", 10 => "October", 11 => "November", 12 => "December");
        $this->optionQuarters = array("1st-Quarter", "2nd-Quarter", "3rd-Quarter", "4th-Quarter");
        $this->optionSemiAnnuals = array("1st-Semi-Annual", "2nd-Semi-Annual");

        $this->showOptions = true;
        $this->showMonths = true;
        $this->showQuarters = false;
        $this->showSemiAnnuals = false;
        $this->showMoreFilters = false;

        $this->filterIncludeNonMinistry = true;
        $this->filterIncludeNonSponsor = true;

    }

    public function render()
    {
        return view('livewire.relief.relief-generate-report');
    }

    public function preview()
    {
        $payload = $this->hasRecords();
        if($payload!=false){
            if($payload['parameters']['reportType']=='ceiling'){
                return redirect()->route('reliefs.report.ceiling.preview',encrypt(json_encode($payload)));
            }else{
                return redirect()->route('reliefs.report.preview',encrypt(json_encode($payload)));
            }
            
        }
        
    }

    public function export()
    {
        if (!Gate::allows('relief-generate-report')) {
            abort(403);
        }
        $payload = $this->hasRecords();
        if($payload['parameters']['reportType']!='ceiling'){
           $fileName = 'Relief Applications FROM ' . $payload['dates']['from'] . ' TO ' . $payload['dates']['to'];
        }else{
            $fileName = 'Relief Ceiling Report FROM ' . $payload['dates']['from'] . ' TO ' . $payload['dates']['to']; 
        }
        if($payload!=false){
            $this->customAlert('success', 'Exporting Excel file');
            return Excel::download(new \App\Exports\ReliefExport($payload), $fileName.'.xlsx');
        }
    }

    public function updated($propertyName)
    {
        if ($this->year == "All") {
            $this->showOptions = false;
        } else {
            $this->showOptions = true;
            if ($this->period == "Monthly") {
                $this->showMonths = true;
                $this->showQuarters = false;
                $this->showSemiAnnuals = false;
            } elseif ($this->period == "Quarterly") {
                $this->showMonths = false;
                $this->showQuarters = true;
                $this->showSemiAnnuals = false;
            } elseif ($this->period == "Semi-Annual") {
                $this->showMonths = false;
                $this->showQuarters = false;
                $this->showSemiAnnuals = true;
            } elseif ($this->period == "Annual") {
                $this->showMonths = false;
                $this->showQuarters = false;
                $this->showSemiAnnuals = false;
            }
        }
        $this->selectedDates = $this->getStartEndDate();

        //report type
        if($propertyName=='reportType'){
            $this->resetAllSelects();
            if($this->reportType=='project'){
                $this->showProjectSections = true; 
                $this->projectSectionId = 'all'; 
                $this->projectId = 'all';
            }elseif($this->reportType=='ministry'){
                $this->showMinistries = true;
                $this->ministryId = 'all';
            }elseif($this->reportType=='supplier'){
                $this->showSuppliers = true;
                $this->supplierId = 'all';
            }elseif($this->reportType=='sponsor'){
                $this->showSponsors = true;
                $this->sponsorId = 'all';
            }elseif($this->reportType=='ceiling'){
                $this->isCeilingReport = true;
            }
        }

        //Project Section
        if ($propertyName == 'projectSectionId') {
            $this->resetAllSelects();
            $this->showProjectSections = true; 
            $this->projectId = 'all';
            if ($this->projectSectionId != 'all') {
                $this->optionProjects = ReliefProjectList::orderBy('name','asc')->where('project_id', $this->projectSectionId)->get();
                $this->showProjects = true;
            }else{
                $this->showProjects = false;
            }
        }

        //Supplier
        if ($propertyName == 'supplierId') {
            $this->resetAllSelects();
            $this->showSuppliers = true;
            $this->supplierLocationId = 'all';
            if ($this->supplierId != 'all') {
                $this->optionSupplierLocations = BusinessLocation::orderBy('name','asc')->where('business_id', $this->supplierId)->get();
                $this->showSuppliersLocations = true;
            }else{
                $this->showSuppliersLocations = false;
            }
        }
    }

    public function getStartEndDate()
    {
        if ($this->year == "All") {
            return [
                'startDate' => null,
                'endDate' => null,
            ];
        } elseif ($this->showMonths) {
            $date = \Carbon\Carbon::parse($this->year . "-" . $this->month . "-01");
            $start = $date->startOfMonth()->format('Y-m-d H:i:s');
            $end = $date->endOfMonth()->format('Y-m-d H:i:s');
            $from = $date->startOfMonth()->format('d-M-Y');
            $to = $date->endOfMonth()->format('d-M-Y');
            return ['startDate' => $start, 'endDate' => $end, 'from' => $from, 'to' => $to];
        } elseif ($this->showQuarters) {
            if ($this->quater == '1st-Quarter') {
                $this->startMonth = 1;
                $this->endMonth = 3;
            } elseif ($this->quater == '2nd-Quarter') {
                $this->startMonth = 4;
                $this->endMonth = 6;
            } elseif ($this->quater == '3rd-Quarter') {
                $this->startMonth = 7;
                $this->endMonth = 9;
            } elseif ($this->quater == '4th-Quarter') {
                $this->startMonth = 10;
                $this->endMonth = 12;
            }

            $startDate = \Carbon\Carbon::parse($this->year . "-" . $this->startMonth . "-01");
            $endDate = \Carbon\Carbon::parse($this->year . "-" . $this->endMonth . "-01");
            $start = $startDate->startOfMonth()->format('Y-m-d H:i:s');
            $end = $endDate->endOfMonth()->format('Y-m-d H:i:s');
            $from = $startDate->format('d-M-Y');
            $to = $endDate->format('d-M-Y');
            return ['startDate' => $start, 'endDate' => $end, 'from' => $from, 'to' => $to];
        } elseif ($this->showSemiAnnuals) {
            if ($this->semiAnnual == '1st-Semi-Annual') {
                $this->startMonth = 1;
                $this->endMonth = 6;
            } elseif ($this->semiAnnual == '2nd-Semi-Annual') {
                $this->startMonth = 7;
                $this->endMonth = 12;
            }
            $startDate = \Carbon\Carbon::parse($this->year . "-" . $this->startMonth . "-01");
            $endDate = \Carbon\Carbon::parse($this->year . "-" . $this->endMonth . "-01");
            $start = $startDate->startOfMonth()->format('Y-m-d H:i:s');
            $end = $endDate->endOfMonth()->format('Y-m-d H:i:s');
            $from = $startDate->format('d-M-Y');
            $to = $endDate->format('d-M-Y');
            return ['startDate' => $start, 'endDate' => $end, 'from' => $from, 'to' => $to];
        } else {
            $startDate = \Carbon\Carbon::parse($this->year . "-" . "01" . "-01");
            $endDate = \Carbon\Carbon::parse($this->year . "-" . "12" . "-01");
            $start = $startDate->startOfMonth()->format('Y-m-d H:i:s');
            $end = $endDate->endOfMonth()->format('Y-m-d H:i:s');
            $from = $startDate->format('d-M-Y');
            $to = $endDate->format('d-M-Y');
            return ['startDate' => $start, 'endDate' => $end, 'from' => $from, 'to' => $to];
        }
    }

    public function exportPdf()
    {
        if (!Gate::allows('relief-generate-report')) {
            abort(403);
        }
        $payload = $this->hasRecords();
        if($payload!=false){
            $this->customAlert('success', 'Exporting Excel file');
            return redirect()->route('reliefs.download.report.pdf', [encrypt(json_encode($payload))]);
        }
    }

    public function toggleFilters()
    {
        $this->showMoreFilters = !$this->showMoreFilters;
    }

    public function getParameters()
    {
        if($this->showMinistries){
            return [
                'reportType' => 'ministry',
                'id' => $this->ministryId
            ];
        }elseif($this->showSponsors){
            return [
                'reportType' => 'sponsor',
                'id' => $this->sponsorId
            ];
        }elseif($this->showProjectSections){
            return [
                'reportType' => 'project',
                'sectionId'=>$this->projectSectionId,
                'projectId'=>$this->projectId,
            ];
        }elseif($this->showSuppliers){
            return [
                'reportType' => 'supplier',
                'supplierId' => $this->supplierId,
                'locationId' => $this->supplierLocationId
            ];
        }elseif($this->isCeilingReport){
            return [
                'reportType' => 'ceiling'
            ];
        }
    }

    public function removeItemsOnFalse($items)
    {
        foreach ($items as $key => $item) {
            if ($item == false) {
                unset($items[$key]);
            }
        }
        return $items;
    }

    public function resetAllSelects()
    {
        $this->showSponsors = false;
        $this->showSuppliers = false;
        $this->showSuppliersLocations = false;
        $this->showMinistries = false;
        $this->showProjects = false;
        $this->showProjectSections = false;
        $this->isCeilingReport = false;
    }

    public function hasRecords()
    {
        $dates = $this->getStartEndDate();
        $parameters = $this->getParameters();
        if ($dates == []) {
            $relief = Relief::query()->orderBy('reliefs.created_at', 'desc');
        } elseif ($dates['startDate'] == null || $dates['endDate'] == null) {
            $relief = Relief::query()->orderBy('reliefs.created_at', 'desc');
        } else {
            $relief = Relief::query()->whereBetween('reliefs.created_at', [$dates['startDate'], $dates['endDate']])->orderBy('reliefs.created_at', 'asc');
        }

        if($parameters['reportType']=='project'){
            if($parameters['sectionId']=='all'){
                $relief->whereNotNull('reliefs.project_id');
            }else{
                $relief->where('reliefs.project_id',$parameters['sectionId']);
                if($parameters['projectId']=='all'){
                    $relief->where('reliefs.project_id',$parameters['sectionId'])
                            ->whereNotNull('reliefs.project_list_id');
                }else{
                    $relief->where('reliefs.project_id',$parameters['sectionId'])
                            ->where('reliefs.project_list_id',$parameters['projectId']);
                }
            } 
        }elseif($parameters['reportType']=='supplier'){
            if($parameters['supplierId']=='all'){
                $relief->whereNotNull('reliefs.business_id');
            }else{
                $relief->where('reliefs.business_id',$parameters['supplierId']);
                if($parameters['locationId']=='all'){
                    $relief->where('reliefs.business_id',$parameters['supplierId'])
                            ->whereNotNull('reliefs.location_id');
                }else{
                    $relief->where('reliefs.business_id',$parameters['supplierId'])
                            ->where('reliefs.location_id',$parameters['locationId']);
                }
            } 
        }elseif($parameters['reportType']=='sponsor'){
            if($parameters['id']=='all'){
                $relief->whereHas('project',function(Builder $query){
                    $query->whereNotNull('relief_sponsor_id');
                });
            }elseif($parameters['id']=='without'){
                $relief->whereHas('project',function(Builder $query){
                    $query->whereNull('relief_sponsor_id');
                });
            }else{
                $relief->whereHas('project',function(Builder $query) use ($parameters) {
                    $query->where('relief_sponsor_id', $parameters['id']);
                });
            }
        }elseif($parameters['reportType']=='ministry'){
            if($parameters['id']=='all'){
                $relief->whereHas('project',function(Builder $query){
                    $query->whereNotNull('ministry_id');
                });
            }elseif($parameters['id']=='without'){
                $relief->whereHas('project',function(Builder $query){
                    $query->whereNull('ministry_id');
                });
            }else{
                $relief->whereHas('project',function(Builder $query) use ($parameters) {
                    $query->where('ministry_id', $parameters['id']);
                });
            }
        }

        if($relief->count()<1){
            $this->customAlert('error','No Records found in selected criteria');
            return false;
        }

        return [
            'dates' => $dates,
            'parameters' => $parameters,
        ];
    }
}
