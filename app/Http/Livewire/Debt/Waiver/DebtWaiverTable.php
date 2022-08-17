<?php

namespace App\Http\Livewire\Debt\Waiver;

use App\Models\WaiverStatus;
use App\Models\Debts\DebtWaiver;
use Illuminate\Database\Eloquent\Builder;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\DataTableComponent;

class DebtWaiverTable extends DataTableComponent
{
    use LivewireAlert;

    public function mount($category)
    {
        $this->category = $category;
    }
    public function builder(): Builder
    {
        return DebtWaiver::query()
        ->where('debt_waivers.status', WaiverStatus::APPROVED)
        ->orderBy('debt_waivers.created_at', 'desc');
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setTableWrapperAttributes([
            'default' => true,
            'class' => 'table-bordered table-sm',
        ]);
        $this->setAdditionalSelects(['assessment_id']);

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
            Column::make("Assesment Type", "assesment.assessment_type")
                ->sortable()
                ->format(function($value, $row) {
                    return preg_split('[\\\]', $value)[2];
                }),
            Column::make("Waiver Category", "category")
                ->sortable(),
            Column::make('Status', 'status')
                ->view('debts.waivers.includes.status'),
            Column::make('Action', 'id')
                ->view('debts.waivers.includes.action'),
        ];
    }
}
