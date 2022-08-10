<?php

namespace App\Http\Livewire\Assesments\Waiver;

use App\Models\Waiver;
use App\Models\WaiverStatus;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class WaiverTable extends DataTableComponent
{
    public $rejected = false;
    public $pending = false;
    public $approved = true;

    // protected $model = Waiver::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setTableWrapperAttributes([
            'default' => true,
            'class' => 'table-bordered table-sm',
        ]);

    }

    public function builder(): Builder
    {
        if ($this->rejected) {
            return Waiver::where('waivers.status', WaiverStatus::REJECTED)->orderBy('waivers.created_at', 'desc');
        }

        if ($this->approved) {
            return Waiver::where('waivers.status', WaiverStatus::APPROVED)->orderBy('waivers.created_at', 'desc');
        }

        if ($this->pending) {
            return Waiver::where('waivers.status', WaiverStatus::PENDING)->orderBy('waivers.created_at', 'desc');
        }

        return Waiver::where('waivers.status', '!=', WaiverStatus::DRAFT)->orderBy('waivers.created_at', 'desc');

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
            Column::make("Weaver Requirements", "waiver_requirement")
                ->sortable(),
            Column::make('Status', 'status')
                ->view('assesments.waiver.includes.status'),
            Column::make('Action', 'id')
                ->view('assesments.waiver.includes.action'),
        ];
    }
}
