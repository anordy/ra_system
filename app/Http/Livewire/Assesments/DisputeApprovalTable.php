<?php

namespace App\Http\Livewire\Assesments;

use App\Models\Disputes\Dispute;
use App\Models\WorkflowTask;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class DisputeApprovalTable extends DataTableComponent
{
    use LivewireAlert;

    public $model = WorkflowTask::class;

    public function mount($category)
    {
        $this->category = $category;
    }
    public function builder(): Builder
    {

        $workflow = WorkflowTask::with('pinstance', 'user')
            ->where('pinstance_type', Dispute::class)
            ->where('status', '!=', 'completed')
            ->whereHasMorph('pinstance', Dispute::class, function ($query) {
                $query->where('category', $this->category);
            })
            ->where('owner', 'staff')
            ->whereJsonContains('operators', auth()->user()->id);
        return $workflow;

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
            Column::make('TIN', 'pinstance.business.tin')
                ->label(fn($row) => $row->pinstance->business->tin ?? ''),
            Column::make('Business Name', 'pinstance.business.name')
                ->label(fn($row) => $row->pinstance->business->name ?? ''),
            Column::make('Owner ', 'pinstance.business.owner_designation')
                ->label(fn($row) => $row->pinstance->business->owner_designation ?? ''),
            Column::make('Business Mobile', 'pinstance.business.mobile')
                ->label(fn($row) => $row->pinstance->business->mobile ?? ''),
            Column::make('Category', 'pinstance.category')
                ->label(fn($row) => $row->pinstance->category ?? ''),
            Column::make('Tax In Dispute', 'pinstance.tax_in_dispute')
                ->label(fn($row) => $row->pinstance->tax_in_dispute ?? ''),
            Column::make('Tax Not in Dispute', 'pinstance.tax_not_in_dispute')
                ->label(fn($row) => $row->pinstance->tax_not_in_dispute ?? ''),
            Column::make('Tax Deposit', 'pinstance.tax_deposit')
                ->label(fn($row) => $row->pinstance->tax_deposit ?? ''),
            Column::make('Filled On', 'created_at')
                ->format(fn($value) => Carbon::create($value)->toDayDateTimeString()),
            Column::make('Action', 'pinstance_id')
                ->view('investigation.approval.action')
                ->html(true),
        ];
    }
}
