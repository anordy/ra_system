<?php

namespace App\Http\Livewire\Assesments;

use Illuminate\Database\Eloquent\Builder;
use App\Traits\CustomAlert;
use Livewire\Component;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class AssessmentHistoryTable extends DataTableComponent
{
    use CustomAlert;
    public $modelName;
    public $modelId;

    public function mount($modelName, $modelId)
    {
        $this->modelName = $modelName;
        $this->modelId = decrypt($modelId);
    }
    public function builder(): Builder
    {

        $histories = $this->modelName::where('tax_assessment_id', $this->modelId);
        return $histories;
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
            Column::make('Principal Amount', 'principal_amount')
                ->label(fn($row, $value) => $row->principal_amount),
            Column::make('Penalty', 'penalty_amount')
                ->label(fn($row) => number_format($row->penalty_amount,2) ?? ''),
            Column::make('Interest Amount', 'interest_amount')
                ->label(fn($row) => number_format($row->interest_amount,2) ?? ''),
            Column::make('Total Amount', 'total_amount')
                ->label(fn($row) => number_format($row->total_amount,2) ?? '')
        ];
    }
}
