<?php

namespace App\Http\Livewire\Verification;

use App\Models\Verification\TaxVerification;
use App\Models\WorkflowTask;
use App\Traits\ReturnFilterTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class VerificationApprovalTable extends DataTableComponent
{
    use LivewireAlert, ReturnFilterTrait;

    protected $listeners = ['filterData' => 'filterData', '$refresh'];
    
    public $data = [];

    public $model = WorkflowTask::class;

    public function filterData($data)
    {
        $this->data = $data;
        $this->emit('$refresh');
    }

    public function builder(): Builder
    {
        $returnTable = WorkflowTask::getTableName();
        $filter      = (new WorkflowTask)->newQuery();
        $filter      = $this->dataFilter($filter, $this->data, $returnTable);

        return $filter->with('pinstance', 'user')
            ->where('pinstance_type', TaxVerification::class)
            ->where('status', '!=', 'completed')
            ->where('owner', 'staff')
            ->whereHas('operators', function($query){
                $query->where('user_id', auth()->id());
            });
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setAdditionalSelects('pinstance_type', 'user_type');
        $this->setTableWrapperAttributes([
            'default' => true,
            'class'   => 'table-bordered table-sm',
        ]);
    }

    public function columns(): array
    {
        return [
            Column::make('pinstance_id', 'pinstance_id')->hideIf(true),
            Column::make('user_type', 'user_id')->hideIf(true),
            Column::make('Z_Number', 'pinstance.location.zin')
                ->label(fn ($row) => $row->pinstance->location->zin ?? '')
                ->searchable(),
            Column::make('TIN', 'pinstance.business.tin')
                ->label(fn ($row) => $row->pinstance->business->tin ?? ''),
            Column::make('Business Name', 'pinstance.business.name')
                ->label(fn ($row) => $row->pinstance->business->name ?? ''),
            Column::make('Business Location', 'pinstance.location.name')
                ->label(fn ($row) => $row->pinstance->location->name ?? ''),
            Column::make('Tax Type', 'pinstance.taxType.name')
                ->label(fn ($row) => $row->pinstance->taxType->name ?? ''),
            Column::make('Filled By', 'pinstance.created_by_id')
                ->label(function ($row) {
                    $user = $row->pinstance->createdBy;

                    return $user->full_name ?? '';
                }),
            Column::make('Filled On', 'created_at')
                ->format(fn ($value) => Carbon::create($value)->toDayDateTimeString()),
            Column::make('Action', 'pinstance_id')
                ->view('verification.approval.action')
                ->html(true),
        ];
    }
}
