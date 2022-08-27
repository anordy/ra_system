<?php

namespace App\Http\Livewire\Assesments\Waiver;

use App\Enum\DisputeStatus;
use App\Models\Disputes\Dispute;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class WaiverTable extends DataTableComponent
{
    public $rejected = 'rejected';
    public $pending = 'pending';
    public $approved = 'approved';
    public $category;
    public $status;

    // protected $model = Waiver::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setTableWrapperAttributes([
            'default' => true,
            'class' => 'table-bordered table-sm',
        ]);

    }

    public function mount($category,$status)
    {
        $this->category = $category;
    }

    public function builder(): Builder
    {
        if ($this->rejected == $this->status) {
            return Dispute::where('disputes.app_status', DisputeStatus::REJECTED)->where('disputes.category', $this->category)->orderBy('disputes.created_at', 'desc');
        }

        if ($this->approved == $this->status) {
            return Dispute::where('disputes.app_status', DisputeStatus::APPROVED)->where('disputes.category', $this->category)->orderBy('disputes.created_at', 'desc');
        }

        if ($this->pending == $this->status) {
            return Dispute::where('disputes.app_status', DisputeStatus::PENDING)->where('disputes.category', $this->category)->orderBy('disputes.created_at', 'desc');
        }

        return Dispute::where('disputes.app_status', '!=', DisputeStatus::DRAFT)->where('disputes.category', $this->category)->orderBy('disputes.created_at', 'desc');

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
            Column::make('Status', 'app_status')
                ->view('assesments.waiver.includes.status'),
            // Column::make('Payment Status', 'status')
            //     ->view('assesments.waiver.includes.payment_status'),
            Column::make('Action', 'id')
                ->view('assesments.waiver.verified.action'),
        ];
    }
}
