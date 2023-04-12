<?php

namespace App\Http\Livewire\Assesments;

use App\Models\Waiver;
use App\Models\WaiverObjection;
use App\Models\WaiverObjectionStatus;
use App\Models\WaiverStatus;
use App\Traits\WithSearch;
use Illuminate\Database\Eloquent\Builder;
use App\Traits\CustomAlert;
use Livewire\WithFileUploads;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class WaiverObjectionApprovalTable extends DataTableComponent
{
    use CustomAlert, WithSearch;

    public function builder(): Builder
    {
        return WaiverObjection::query()
            ->where('waiver_objections.status', WaiverObjectionStatus::PENDING)
            ->orderBy('waiver_objections.created_at', 'desc');
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
            Column::make("Business Name", "business.name")
                ->sortable()
                ->searchable(),
            Column::make("Owner", "business.owner_designation")
                ->sortable()
                ->searchable(),
            Column::make("Mobile", "business.mobile")
                ->sortable(),
            Column::make("Tax In Dispute(Tzs)", "tax_in_dispute")
                ->sortable(),
            Column::make("Tax Not in Dispute", "tax_not_in_dispute")
                ->sortable(),
            Column::make('Previous Transition', 'id')
                ->format(function ($value, $row) {
                    $transition = str_replace('_', ' ', $row->pinstancesActive->name ?? '');
                    return <<<HTML
                       <span class="badge badge-info py-1 px-2"  style="border-radius: 1rem; font-size: 85%">
                        <i class="bi bi-clock mr-1"></i>
                        {$transition}
                    </span>
                    HTML;
                })->html(true),
            Column::make('Status', 'payment_status')
                ->view('assesments.waiverobjection.includes.status'),
            Column::make('Action', 'id')
                ->view('assesments.waiverobjection.includes.action'),
        ];
    }
}
