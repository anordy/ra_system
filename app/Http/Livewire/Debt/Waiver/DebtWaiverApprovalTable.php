<?php

namespace App\Http\Livewire\Debt\Waiver;

use App\Models\Debts\DebtWaiver;
use App\Models\WaiverStatus;
use Illuminate\Database\Eloquent\Builder;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class DebtWaiverApprovalTable extends DataTableComponent
{
    use LivewireAlert;

    public function mount($category)
    {
        $this->category = $category;
    }
    public function builder(): Builder
    {
        return DebtWaiver::query()
            ->where('debt_waivers.status', WaiverStatus::PENDING)
            ->orderBy('debt_waivers.created_at', 'desc');
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setTableWrapperAttributes([
            'default' => true,
            'class' => 'table-bordered table-sm',
        ]);
        $this->setAdditionalSelects(['debt_waivers.tax_return_id']);
    }

    public function columns(): array
    {
        return [
            Column::make("Business Name", "debt.business.name")
                ->sortable()
                ->searchable(),
            Column::make("Location", "debt.location.name")
                ->sortable()
                ->searchable(),
            Column::make("Debt Type", "debt.taxtype.name")
                ->sortable()
                ->searchable(),
            Column::make("Waiver Type", "category")
                ->sortable(),
            Column::make('Status', 'status')
                ->view('debts.waivers.includes.status'),
            Column::make('Action', 'id')
                ->view('debts.waivers.includes.action'),
        ];
    }
}
