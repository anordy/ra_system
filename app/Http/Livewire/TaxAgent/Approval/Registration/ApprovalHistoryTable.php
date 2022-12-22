<?php

namespace App\Http\Livewire\TaxAgent\Approval\Registration;

use App\Models\WorkflowTask;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\TaxAgent;

class ApprovalHistoryTable extends DataTableComponent
{
    use LivewireAlert;

    public $model = WorkflowTask::class;
    public $modelId;
    public $modelName;

    public function mount($modelName, $modelId)
    {
        $this->modelName = $modelName;
        $this->modelId = decrypt($modelId);
    }

    public function builder(): Builder
    {
        return WorkflowTask::query()->with('user')
            ->where('pinstance_type', $this->modelName)
            ->where('pinstance_id', $this->modelId);
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
