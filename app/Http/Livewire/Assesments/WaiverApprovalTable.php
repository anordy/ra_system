<?php

namespace App\Http\Livewire\Assesments;

use App\Models\Waiver;
use App\Models\WaiverStatus;
use Illuminate\Database\Eloquent\Builder;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\WithFileUploads;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class WaiverApprovalTable extends DataTableComponent
{
    use LivewireAlert;

    public function builder(): Builder
    {
        return Waiver::query()
            ->where('waivers.status', WaiverStatus::PENDING)
            ->orderBy('waivers.created_at', 'desc');
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
            Column::make("Id", "id")
                ->sortable(),
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
                    $transtion = str_replace('_', ' ', $row->pinstancesActive->name ?? '');
                    return <<<HTML
                       <span class="badge badge-info py-1 px-2"  style="border-radius: 1rem; font-size: 85%">
                        <i class="bi bi-clock mr-1"></i>
                        {$transtion}
                    </span>
                    HTML;
                })->html(true),
            Column::make('Status', 'status')
                ->view('assesments.waiver.includes.status'),
            Column::make('Action', 'id')
                ->view('assesments.waiver.includes.action'),
        ];
    }
}
