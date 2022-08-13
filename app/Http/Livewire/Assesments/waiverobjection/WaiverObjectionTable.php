<?php

namespace App\Http\Livewire\Assesments\Waiverobjection;

use App\Models\WaiverObjection;
use App\Models\WaiverObjectionStatus;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class WaiverObjectionTable extends DataTableComponent
{
    public $rejected = false;
    public $pending = false;
    public $approved = true;

    protected $model = WaiverObjection::class;

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
            return WaiverObjection::where('waiver_objections.status', WaiverObjectionStatus::REJECTED)->where('waiver_objections.type', 'both')->orderBy('waiver_objections.created_at', 'desc');
        }

        if ($this->approved) {
            return WaiverObjection::where('waiver_objections.status', WaiverObjectionStatus::APPROVED)->where('waiver_objections.type', 'both')->orderBy('waiver_objections.created_at', 'desc');
        }

        if ($this->pending) {
            return WaiverObjection::where('waiver_objections.status', WaiverObjectionStatus::PENDING)->where('waiver_objections.type', 'both')->orderBy('waiver_objections.created_at', 'desc');
        }

        return WaiverObjection::where('waiver_objections.status', '!=', WaiverObjectionStatus::DRAFT)->where('waiver_objections.type', 'both')->orderBy('waiver_objections.created_at', 'desc');

        // return WaiverObjection::query()->where('filed_by_id', auth()->user()->id)->where('type', 'both');

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
            Column::make('Status', 'status')
                ->view('assesments.waiverobjection.includes.status'),
            Column::make('Action', 'id')
                ->view('assesments.waiverobjection.includes.action'),
        ];
    }
}
