<?php

namespace App\Http\Livewire\Business;

use App\Models\Business;
use App\Models\BusinessStatus;
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
        return Business::query()
            ->with('pinstancesActive')
            ->where('status', BusinessStatus::PENDING)
            ->orderBy('created_at', 'desc');
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setTableWrapperAttributes([
            'default' => true,
            'class' => 'table-bordered table-sm',
        ]);
    }

    public function columns(): array
    {
        return [
            Column::make('Business Name', 'name')
                ->sortable()
                ->searchable(),
            Column::make('TIN', 'tin'),
            Column::make('Buss. Reg. No.', 'reg_no'),
            Column::make('Mobile', 'mobile'),
            Column::make('Previous Transition', 'id')
                ->format(function ($value, $row) {
                    $transtion  = str_replace('_', ' ', $row->pinstancesActive->name ?? '');
                    return <<<HTML
                       <span class="badge badge-info py-1 px-2"  style="border-radius: 1rem; font-size: 85%">
                        <i class="bi bi-clock mr-1"></i>
                        {$transtion}
                    </span>
                    HTML;
                })->html(true),
            Column::make('Status', 'status')
                ->view('business.registrations.includes.status'),
            Column::make('Action', 'id')
                ->view('business.registrations.includes.approval')
        ];
    }
}
