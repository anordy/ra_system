<?php

namespace App\Exports;


use App\Models\Relief\Relief;
use App\Models\Relief\ReliefProject;

use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

// use Maatwebsite\Excel\Concerns\FromCollection;

class ReliefExport implements FromView, WithEvents,ShouldAutoSize
{
    public $dates;
    public $parameters;

    /**
     * __construct
     *
     * @param  mixed $request
     * @return void
     */
    function __construct($payload)
    {
        $this->dates = $payload['dates'];
        $this->parameters = $payload['parameters'];  
    }

    /**
     * registerEvents
     *
     * @return array
     */
    public function registerEvents(): array
    {

        $headerStyle = [
            'font' => [
                'bold' => true,
            ],
            'text-align' => 'center'
        ];


        return [

            AfterSheet::class => function (AfterSheet $event) use ($headerStyle) {
                $event->sheet->getDelegate()->getStyle('A1')->applyFromArray($headerStyle);
            }

        ];
    }

    public function view(): View
    {
        $dates = $this->dates;
        $parameters = $this->parameters;
        if ($dates == []) {
            $relief = Relief::query()->orderBy('eliefs.created_at', 'asc');
        } elseif ($dates['startDate'] == null || $dates['endDate'] == null) {
            $relief = Relief::query()->orderBy('reliefs.created_at', 'asc');
        } else {
            $relief = Relief::query()->whereBetween('reliefs.created_at', [$dates['startDate'], $dates['endDate']])->orderBy('reliefs.created_at', 'asc');
        }

        if($parameters['reportType']=='project'){
            if($parameters['sectionId']=='all'){
                $relief->whereNotNull('reliefs.project_id');
            }else{
                $relief->where('reliefs.project_id',$parameters['sectionId']);
                $projectSections[] = ReliefProject::find($parameters['sectionId']);
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

        //get months of created at from reliefs
        $months = $relief->pluck('created_at')->map(function ($date) {
            return Carbon::parse($date)->format('F Y');
        })->unique()->values()->all();

        $projectSections = $projectSections??ReliefProject::all();
        
        foreach ($projectSections as $projectSection ) {
            $projectSectionsArray[] = [
                'id' => $projectSection->id,
                'name' => $projectSection->name,
                'description' => $projectSection->description,
            ];
        }

        //make relief copy
        $reliefCopy = clone $relief;
        $reliefIds = $reliefCopy->pluck('id')->toArray();
        foreach ($months as $month){
            //data for each project section per month
            foreach ($projectSections as $projectSection){
                $data[$month][$projectSection['id']] = [
                    'name' => $projectSection['name'],
                    'description' => $projectSection['description'],
                    'count' => Relief::whereIn('id',$reliefIds)->where('reliefs.project_id', $projectSection['id'])->whereMonth('reliefs.created_at', Carbon::parse($month)->month)->whereYear('reliefs.created_at', Carbon::parse($month)->year)->get()->count(),
                    'relievedAmount' => Relief::whereIn('id',$reliefIds)->where('reliefs.project_id', $projectSection['id'])->whereMonth('reliefs.created_at', Carbon::parse($month)->month)->whereYear('reliefs.created_at', Carbon::parse($month)->year)->get()->sum('relieved_amount'),
                ];
            }
        }    
    
        $reliefs = $relief->get();
        return view('exports.relief.reports.relief-report',compact('reliefs','months','projectSections','projectSectionsArray','data','dates'));
    }
}