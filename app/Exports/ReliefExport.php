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

// use Maatwebsite\Excel\Concerns\FromCollection;

class ReliefExport implements FromView, WithEvents,ShouldAutoSize
{
    public $dates;

    /**
     * __construct
     *
     * @param  mixed $request
     * @return void
     */
    function __construct($dates)
    {
        $this->dates = $dates;
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
        if ($dates == []) {
            $reliefs = Relief::query()->orderBy('reliefs.created_at', 'asc');
        } elseif ($dates['startDate'] == null || $dates['endDate'] == null) {
            $reliefs = Relief::query()->orderBy('reliefs.created_at', 'asc');
        } else {
            $reliefs = Relief::query()->whereBetween('reliefs.created_at', [$dates['startDate'], $dates['endDate']])->orderBy('reliefs.created_at', 'asc');
        }

        //get months of created at from reliefs
        $months = $reliefs->pluck('created_at')->map(function ($date) {
            return Carbon::parse($date)->format('F Y');
        })->unique()->values()->all();

        //get project sections
        // $projectSectionsArray = null;
        $projectSections = ReliefProject::all();
        foreach ($projectSections as $projectSection ) {
            $projectSectionsArray[] = [
                'id' => $projectSection->id,
                'name' => $projectSection->name,
                'description' => $projectSection->description,
            ];
        }

        //data for each month
        // $data= null;
        foreach ($months as $month) {
            //data for each project section per month
            foreach ($projectSections as $projectSection) {
                $data[$month][$projectSection->id] = [
                    'name' => $projectSection->name,
                    'description' => $projectSection->description,
                    'count' => Relief::where('project_id', $projectSection->id)->whereMonth('created_at', Carbon::parse($month)->month)->whereYear('created_at', Carbon::parse($month)->year)->count(),
                    'relievedAmount' => Relief::where('project_id', $projectSection->id)->whereMonth('created_at', Carbon::parse($month)->month)->whereYear('created_at', Carbon::parse($month)->year)->sum('relieved_amount'),
                ];
            }
        }
        $reliefs = $reliefs->get();
        return view('Exports.relief-report',compact('reliefs','months','projectSections','projectSectionsArray','data','dates'));
       
    }
}