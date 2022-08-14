<?php

namespace App\Http\Livewire\Assesments\Waiver;

use App\Models\Disputes\Dispute;
use App\Models\WaiverStatus;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class WaiverTable extends DataTableComponent
{
    public $rejected = false;
    public $pending = false;
    public $approved = true;
    public $category;

    // protected $model = Waiver::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setTableWrapperAttributes([
            'default' => true,
            'class' => 'table-bordered table-sm',
        ]);

    }

    public function mount($category)
    {
        $this->category = $category;
    }

    public function builder(): Builder
    {
        if ($this->rejected) {
            return Dispute::where('disputes.status', WaiverStatus::REJECTED)->where('disputes.category',$this->category)->orderBy('disputes.created_at', 'desc');
        }

        if ($this->approved) {
            return Dispute::where('disputes.status', WaiverStatus::APPROVED)->where('disputes.category',$this->category)->orderBy('disputes.created_at', 'desc');
        }

        if ($this->pending) {
            return Dispute::where('disputes.status', WaiverStatus::PENDING)->where('disputes.category',$this->category)->orderBy('disputes.created_at', 'desc');
        }

        return Dispute::where('disputes.status', '!=', WaiverStatus::DRAFT)->where('disputes.category',$this->category)->orderBy('disputes.created_at', 'desc');

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
            Column::make("Category", "category")
                ->sortable(),
            Column::make("Tax In Dispute(Tzs)", "tax_in_dispute")
                ->sortable(),
            Column::make("Tax Not in Dispute", "tax_not_in_dispute")
                ->sortable(),
            Column::make("Tax Deposit", "tax_deposit")
                ->sortable(),
            Column::make('Status', 'status')
                ->view('assesments.waiver.includes.status'),
            Column::make('Action', 'id')
                ->view('assesments.waiver.includes.action'),
        ];
    }
}
