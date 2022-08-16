<?php

namespace App\Http\Livewire\Assesments\Objection;

use App\Models\Objection;
use App\Models\ObjectionStatus;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class ObjectionTable extends DataTableComponent
{
    // protected $model = Objection::class;
    public $rejected = false;
    public $pending = false;
    public $approved = true;

    // protected $model = Objection::class;

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
            return Objection::where('Objections.status', ObjectionStatus::REJECTED)->orderBy('Objections.created_at', 'desc');
        }

        if ($this->approved) {
            return Objection::where('Objections.status', ObjectionStatus::APPROVED)->orderBy('Objections.created_at', 'desc');
        }

        if ($this->pending) {
            return Objection::where('Objections.status', ObjectionStatus::PENDING)->orderBy('Objections.created_at', 'desc');
        }

        return Objection::where('Objections.status', '!=', ObjectionStatus::DRAFT)->orderBy('Objections.created_at', 'desc');

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
            Column::make("Objection Requirements", "objection_requirement")
                ->sortable(),
            Column::make('Status', 'status')
                ->view('assesments.objection.includes.status'),
            Column::make('Action', 'id')
                ->view('assesments.objection.includes.action'),
        ];
    }
}
