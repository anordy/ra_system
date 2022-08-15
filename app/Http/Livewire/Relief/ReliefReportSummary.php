<?php

namespace App\Http\Livewire\Relief;

use App\Models\Relief\Relief;
use App\Models\Relief\ReliefProject;
use Carbon\Carbon;
use Livewire\Component;

class ReliefReportSummary extends Component
{
    public $dates = [];
    public $data = [];
    public $projectSectionsArray = [];

    protected $listeners = ['refreshSummary' => 'refreshSummary'];

    public function render()
    {
        return view('livewire.relief.relief-report-summary');
    }

    public function mount()
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

        $projectSections = ReliefProject::all();
        foreach ($projectSections as $projectSection ) {
            $this->projectSectionsArray[] = [
                'id' => $projectSection->id,
                'name' => $projectSection->name,
                'description' => $projectSection->description,
            ];
        }

        foreach ($months as $month) {
            //data for each project section per month
            foreach ($projectSections as $projectSection) {
                $this->data[$month][$projectSection->id] = [
                    'name' => $projectSection->name,
                    'description' => $projectSection->description,
                    'count' => Relief::where('project_id', $projectSection->id)->whereMonth('created_at', Carbon::parse($month)->month)->whereYear('created_at', Carbon::parse($month)->year)->count(),
                    'relievedAmount' => Relief::where('project_id', $projectSection->id)->whereMonth('created_at', Carbon::parse($month)->month)->whereYear('created_at', Carbon::parse($month)->year)->sum('relieved_amount'),
                ];
            }
        }
       
    }

    public function refreshSummary($dates)
    {
        $this->dates = $dates;
        $this->mount();
    }
}
