<?php

namespace App\Http\Controllers\Relief;

use App\Http\Controllers\Controller;
use App\Models\Relief\Relief;
use App\Models\Relief\ReliefProject;
use App\Models\Relief\ReliefProjectList;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use PDF;

use Illuminate\Support\Facades\Gate;

class ReliefGenerateReportController extends Controller
{
    //
    public function index(Request $request)
    {
        return view('relief.reports.index');
    }

    public function downloadReliefReportPdf($payload)
    {
        if (!Gate::allows('relief-generate-report')) {
            abort(403);
        }
        $values = json_decode(decrypt($payload), true);
        $dates = $values['dates'];
        $parameters = $values['parameters'];
        if ($dates == []) {
            $relief = Relief::query()->orderBy('eliefs.created_at', 'asc');
        } elseif ($dates['startDate'] == null || $dates['endDate'] == null) {
            $relief = Relief::query()->orderBy('reliefs.created_at', 'asc');
        } else {
            $relief = Relief::query()->whereBetween('reliefs.created_at', [$dates['startDate'], $dates['endDate']])->orderBy('reliefs.created_at', 'asc');
        }

        if ($parameters['reportType'] == 'project') {
            if ($parameters['sectionId'] == 'all') {
                $relief->whereNotNull('reliefs.project_id');
            } else {
                $relief->where('reliefs.project_id', $parameters['sectionId']);
                $projectSections[] = ReliefProject::findOrFail($parameters['sectionId']);
                if ($parameters['projectId'] == 'all') {
                    $relief->where('reliefs.project_id', $parameters['sectionId'])
                        ->whereNotNull('reliefs.project_list_id');
                } else {
                    $relief->where('reliefs.project_id', $parameters['sectionId'])
                        ->where('reliefs.project_list_id', $parameters['projectId']);
                }
            }
        } elseif ($parameters['reportType'] == 'supplier') {
            if ($parameters['supplierId'] == 'all') {
                $relief->whereNotNull('reliefs.business_id');
            } else {
                $relief->where('reliefs.business_id', $parameters['supplierId']);
                if ($parameters['locationId'] == 'all') {
                    $relief->where('reliefs.business_id', $parameters['supplierId'])
                        ->whereNotNull('reliefs.location_id');
                } else {
                    $relief->where('reliefs.business_id', $parameters['supplierId'])
                        ->where('reliefs.location_id', $parameters['locationId']);
                }
            }
        } elseif ($parameters['reportType'] == 'sponsor') {
            if ($parameters['id'] == 'all') {
                $relief->whereHas('project', function (Builder $query) {
                    $query->whereNotNull('relief_sponsor_id');
                });
            } elseif ($parameters['id'] == 'without') {
                $relief->whereHas('project', function (Builder $query) {
                    $query->whereNull('relief_sponsor_id');
                });
            } else {
                $relief->whereHas('project', function (Builder $query) use ($parameters) {
                    $query->where('relief_sponsor_id', $parameters['id']);
                });
            }
        } elseif ($parameters['reportType'] == 'ministry') {
            if ($parameters['id'] == 'all') {
                $relief->whereHas('project', function (Builder $query) {
                    $query->whereNotNull('ministry_id');
                });
            } elseif ($parameters['id'] == 'without') {
                $relief->whereHas('project', function (Builder $query) {
                    $query->whereNull('ministry_id');
                });
            } else {
                $relief->whereHas('project', function (Builder $query) use ($parameters) {
                    $query->where('ministry_id', $parameters['id']);
                });
            }
        } elseif ($parameters['reportType'] == 'ceiling') {
            $projectGroups = $relief->get()->groupBy('project_list_id');
            $projectSections = [];
            $total = 0;
            foreach ($projectGroups as $projectId => $projectRecords) {
                $project = ReliefProjectList::findOrFail($projectId);
                $sum = $this->calculateIndexSum($projectRecords->toArray(), 'relieved_amount');
                $total += $sum;
                if (array_key_exists($project->project_id, $projectSections)) {
                    $projectSections[$project->project_id]['subTotal'] += $sum;
                    $projectSections[$project->project_id]['projects'][] = ['id' => $projectId,
                        'name' => $project->name,
                        'sponsor' => $project->sponsor->name ?? '-',
                        'relievedAmount' => $sum];
                } else {
                    $projectSections[$project->project_id] = [
                        'name' => $project->reliefProject->name,
                        'subTotal' => $sum,
                        'projects' => [
                            ['id' => $projectId,
                                'name' => $project->name,
                                'sponsor' => $project->sponsor->name ?? '-',
                                'relievedAmount' => $sum],
                        ],
                    ];
                }
            }
            $reliefs = $relief->get();
            $pdf = PDF::loadView('exports.relief.reports.relief-report-ceiling-pdf', compact('projectSections', 'dates'));
            $pdf->setPaper('a4', 'portrait');
            $pdf->setOption(['dpi' => 150, 'defaultFont' => 'sans-serif']);
            return $pdf->download('Relief applications FROM ' . $dates['from'] . ' TO ' . $dates['to'] . '.pdf');
        }

        //get months of created at from reliefs
        $months = $relief->pluck('created_at')->map(function ($date) {
            return Carbon::parse($date)->format('F Y');
        })->unique()->values()->all();

        $projectSections = $projectSections ?? ReliefProject::all();

        foreach ($projectSections as $projectSection) {
            $projectSectionsArray[] = [
                'id' => $projectSection->id,
                'name' => $projectSection->name,
                'description' => $projectSection->description,
            ];
        }

        //make relief copy
        $reliefCopy = clone $relief;
        $reliefIds = $reliefCopy->pluck('id')->toArray();
        foreach ($months as $month) {
            //data for each project section per month
            foreach ($projectSections as $projectSection) {
                $data[$month][$projectSection['id']] = [
                    'name' => $projectSection['name'],
                    'description' => $projectSection['description'],
                    'count' => Relief::whereIn('id', $reliefIds)->where('reliefs.project_id', $projectSection['id'])->whereMonth('reliefs.created_at', Carbon::parse($month)->month)->whereYear('reliefs.created_at', Carbon::parse($month)->year)->get()->count(),
                    'relievedAmount' => Relief::whereIn('id', $reliefIds)->where('reliefs.project_id', $projectSection['id'])->whereMonth('reliefs.created_at', Carbon::parse($month)->month)->whereYear('reliefs.created_at', Carbon::parse($month)->year)->get()->sum('relieved_amount'),
                ];
            }
        }

        $reliefs = $relief->get();
        $pdf = PDF::loadView('exports.relief.reports.relief-report-pdf', compact('reliefs', 'months', 'projectSections', 'projectSectionsArray', 'data', 'dates'));
        $pdf->setPaper('a4', 'portrait');
        $pdf->setOption(['dpi' => 150, 'defaultFont' => 'sans-serif']);
        return $pdf->download('Relief applications FROM ' . $dates['from'] . ' TO ' . $dates['to'] . '.pdf');
    }

    public function reportPreview($payload)
    {
        if (!Gate::allows('relief-generate-report-view')) {
            abort(403);
        }
        return view('relief.reports.preview', ['payload' => $payload]);
    }

    public function calculateIndexSum($projectRecords, $index)
    {
        $sum = 0;
        foreach ($projectRecords as $project) {
            $sum += $project[$index];
        }
        return $sum;
    }

    public function ceilingReport($payload)
    {
        if (!Gate::allows('relief-generate-report-view')) {
            abort(403);
        }
        $values = json_decode(decrypt($payload), true);
        $dates = $values['dates'];
        $parameters = $values['parameters'];
        if ($dates == []) {
            $relief = Relief::query()->orderBy('eliefs.created_at', 'asc');
        } elseif ($dates['startDate'] == null || $dates['endDate'] == null) {
            $relief = Relief::query()->orderBy('reliefs.created_at', 'asc');
        } else {
            $relief = Relief::query()->whereBetween('reliefs.created_at', [$dates['startDate'], $dates['endDate']])->orderBy('reliefs.created_at', 'asc');
        }
        $projectGroups = $relief->get()->groupBy('project_list_id');
        $projectSections = [];
        $total = 0;
        foreach ($projectGroups as $projectId => $projectRecords) {
            $project = ReliefProjectList::findOrFail($projectId);
            $sum = $this->calculateIndexSum($projectRecords->toArray(), 'relieved_amount');
            $total += $sum;
            if (array_key_exists($project->project_id, $projectSections)) {
                $projectSections[$project->project_id]['subTotal'] += $sum;
                $projectSections[$project->project_id]['projects'][] = ['id' => $projectId,
                    'name' => $project->name,
                    'sponsor' => $project->sponsor->name ?? '-',
                    'relievedAmount' => $sum];
            } else {
                $projectSections[$project->project_id] = [
                    'name' => $project->reliefProject->name,
                    'subTotal' => $sum,
                    'projects' => [
                        ['id' => $projectId,
                            'name' => $project->name,
                            'sponsor' => $project->sponsor->name ?? '-',
                            'relievedAmount' => $sum],
                    ],
                ];
            }
        }

        return view('livewire.relief.relief-report-ceiling', compact('dates', 'projectSections'));
    }
}
