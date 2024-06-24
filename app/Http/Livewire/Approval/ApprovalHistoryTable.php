<?php

namespace App\Http\Livewire\Approval;

use App\Models\WorkflowTask;
use App\Traits\WithSearch;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use App\Traits\CustomAlert;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class ApprovalHistoryTable extends DataTableComponent
{

    use CustomAlert;

    public $model = WorkflowTask::class;
    public $modelId;
    public $modelName;
    public $isSummary;

    public function mount($modelName, $modelId, $isSummary = false)
    {
        $this->modelName = $modelName;
        $this->modelId = decrypt($modelId);
        $this->isSummary = $isSummary;
    }

    public function builder(): Builder
    {
        if ($this->isSummary){
            return WorkflowTask::query()->with('user')
                ->where('pinstance_type', $this->modelName)
                ->where('pinstance_id', $this->modelId)
                ->where(function ($query){
                    $query->where('to_place', 'taxPayer_acceptance')
                        ->orWhere('from_place', 'taxPayer_acceptance')
                        ->orWhere('from_place', 'final_report')
                        ->orWhere('from_place', 'final_report_review')
                        ->orWhere('from_place', 'commissioner');
                })
                ->orderBy('approved_on', 'ASC');
        }
        return WorkflowTask::query()->with('user')
            ->where('pinstance_type', $this->modelName)
            ->where('pinstance_id', $this->modelId)
            ->orderBy('approved_on', 'ASC');
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setSearchStatus(false);
        $this->setAdditionalSelects(['user_type']);
        $this->setTableWrapperAttributes([
            'default' => true,
            'class' => 'table-bordered table-sm',
        ]);
    }

    public function columns(): array
    {
        if ($this->isSummary){
            return [
                Column::make('Commented By', 'user_id')
                    ->format(
                        fn ($value, $row) => $row->user->full_name ?? null
                    ),
                Column::make('Comment', 'remarks'),
                Column::make('Approved On', 'approved_on')
                    ->format(function ($value) {
                        return Carbon::make($value)->toDateTimeString();
                    }),
            ];
        }
        return [
            Column::make('Name', 'name')
                ->format(fn ($value) => ucfirst(str_replace('_', ' ', $value))),
            Column::make('From', 'from_place')
                ->format(fn ($value) => ucfirst(str_replace('_', ' ', $value))),
            Column::make('To', 'to_place')
                ->format(fn ($value) => ucfirst(str_replace('_', ' ', $value))),
            Column::make('Comment', 'remarks'),
            Column::make('Approved By', 'user_id')
                ->format(
                    fn ($value, $row) => $row->user->full_name ?? null
                ),
            Column::make('Approved On', 'approved_on')
                ->format(function ($value) {
                    return Carbon::make($value)->toDateTimeString();
                }),
        ];
    }
}
