<?php

namespace App\Http\Livewire\Business;

use App\Models\Business;
use App\Models\BusinessStatus;
use App\Models\Investigation\TaxInvestigation;
use App\Models\WorkflowTask;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class RegistrationsApprovalTable extends DataTableComponent
{

    use LivewireAlert;

    public function builder(): Builder
    {
        return WorkflowTask::with('pinstance', 'user')
            ->where('pinstance_type', Business::class)
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
            Column::make('Business Name', 'pinstance.name')
                ->label(fn ($row) => $row->pinstance->name ?? ''),
            Column::make('TIN', 'pinstance.tin')
                ->label(fn ($row) => $row->pinstance->tin ?? ''),
            Column::make('Buss. Reg. No.', 'pinstance.reg_no')
                ->label(fn ($row) => $row->pinstance->reg_no ?? ''),
            Column::make('Mobile', 'pinstance.mobile')
                ->label(fn ($row) => $row->pinstance->mobile ?? ''),
//            Column::make('Previous Transition', 'id')
//                ->format(function ($value, $row) {
//                    $transtion  = str_replace('_', ' ', $row->pinstancesActive->name ?? '');
//                    return <<<HTML
//                       <span class="badge badge-info py-1 px-2"  style="border-radius: 1rem; font-size: 85%">
//                        <i class="bi bi-clock mr-1"></i>
//                        {$transtion}
//                    </span>
//                    HTML;
//                })->html(true),
            Column::make('Status', 'pinstance.mobile')
                ->label(function ($row){
                    return view('business.registrations.includes.approval_status', compact('row'));
                }),
            Column::make('Action', 'pinstance_id')
                ->view('business.registrations.includes.approval')
        ];
    }
}
