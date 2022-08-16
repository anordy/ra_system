<?php

namespace App\Http\Controllers\Relief;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Relief\ReliefProject;
use App\Models\Relief\ReliefProjectList;
use App\Models\Relief\Relief;
use Carbon\Carbon;
use PDF;

class ReliefGenerateReportController extends Controller
{
    //
    public function index(Request $request)
    {
        return view('relief.reports.index');
    }
    
    
    public function downloadReliefReportPdf($datesJson)
    {
        $dates = decrypt($datesJson);
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
        $pdf = PDF::loadView('Exports.relief-report-pdf',compact('reliefs','months','projectSections','projectSectionsArray','data','dates'));
        $pdf->setPaper('a4', 'portrait');
        $pdf->setOption(['dpi' => 150, 'defaultFont' => 'sans-serif']);
        return $pdf->download('Relief applications FROM ' . $dates['from'] . ' TO ' . $dates['to'] . '.pdf');
    }
}


