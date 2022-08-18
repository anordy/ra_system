<?php

namespace App\Http\Livewire\Assesments;

use App\Enum\BillStatus;
use App\Enum\DisputeStatus;
use App\Enum\PaymentStatus;
use App\Models\Disputes\Dispute;
use Illuminate\Database\Eloquent\Builder;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class WaiverApprovalTable extends DataTableComponent
{
    use LivewireAlert;
    public $paymentStatus;

    public function mount($category, $payment)
    {
        $this->paymentStatus = $payment;
        $this->category = $category;
    }
    public function builder(): Builder
    {

        if ($this->paymentStatus == 'complete') {
            return Dispute::query()
                ->where('disputes.status', BillStatus::COMPLETE)
                ->where('disputes.category', $this->category)
                ->whereNotIn('disputes.app_status', [DisputeStatus::APPROVED])
                ->with('pinstancesActive')
                ->orderBy('disputes.created_at', 'desc');

        } elseif ($this->paymentStatus == 'unpaid') {
            return Dispute::query()
                ->where('disputes.category', $this->category)
                ->whereNotIn('disputes.status', [BillStatus::COMPLETE])
                ->whereNotIn('disputes.app_status', [DisputeStatus::APPROVED])
                ->with('pinstancesActive')
                ->orderBy('disputes.created_at', 'desc');
        } else {
            return [];
        }

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
            Column::make("Category", "category")
                ->sortable(),
            Column::make("Tax In Dispute(Tzs)", "tax_in_dispute")
                ->sortable(),
            Column::make("Tax Not in Dispute", "tax_not_in_dispute")
                ->sortable(),
            Column::make("Tax Deposit", "tax_deposit")
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
            Column::make('Status', 'app_status')
                ->view('assesments.waiver.includes.status'),
            Column::make('Payment Status', 'status')
                ->hideIf($this->category == 'waiver-and-objection')
                ->view('assesments.waiver.includes.payment_status')
                ->html(true),
            Column::make('Action', 'id')
                ->hideIf($this->paymentStatus != 'complete')
                ->view('assesments.waiver.includes.action')
                ->html(true),
        ];
    }
}
