<?php

namespace App\Http\Livewire\Verification;


use App\Models\Verification\TaxVerification;
use App\Models\WorkflowTask;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class VerificationApprovalTable extends DataTableComponent
{

    use LivewireAlert;

    public $model = WorkflowTask::class;

    public function builder(): Builder
    {
        return WorkflowTask::query()
            ->with('pinstance', 'user')
            ->where('pinstance_type', TaxVerification::class)
            ->where('status', '!=', 'completed')
            ->where('owner', 'staff')
            ->whereJsonContains('operators', auth()->user()->id);
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setAdditionalSelects('pinstance_type', 'user_type');
        $this->setTableWrapperAttributes([
            'default' => true,
            'class' => 'table-bordered table-sm',
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
