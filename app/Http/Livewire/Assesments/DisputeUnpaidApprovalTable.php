<?php

namespace App\Http\Livewire\Assesments;

use App\Enum\BillStatus;
use App\Models\Disputes\Dispute;
use Illuminate\Database\Eloquent\Builder;
use App\Traits\CustomAlert;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class DisputeUnpaidApprovalTable extends DataTableComponent
{
    use CustomAlert;
    public $paymentStatus;
    public $model = Dispute::class;

    public function mount($category)
    {
        $this->category = $category;
    }
    public function builder(): Builder
    {

        $dispute = Dispute::query()
            ->where('disputes.category', $this->category)
            ->whereNotIn('disputes.payment_status', [BillStatus::COMPLETE])
            ->orderBy('disputes.created_at', 'desc');
        return $dispute;
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        // $this->setAdditionalSelects('pinstance_type', 'user_type');
        $this->setTableWrapperAttributes([
            'default' => true,
            'class' => 'table-bordered table-sm',
        ]);
    }

    public function columns(): array
    {
        return [
            // Column::make("Id", "id")
            //     ->sortable(),
            Column::make("TIN", "business.tin")
                ->sortable()
                ->searchable(),
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
            Column::make('Payment Status', 'payment_status')
                ->hideIf($this->category == 'waiver-and-objection')
                ->view('assesments.waiver.includes.payment_status')
                ->html(true),
            Column::make('Action', 'id')
                // ->hideIf($this->paymentStatus != 'complete')
                ->view('assesments.waiver.includes.approval_progress_action')
                ->html(true),
        ];
    }
}
