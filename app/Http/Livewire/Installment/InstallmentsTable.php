<?php

namespace App\Http\Livewire\Installment;

use App\Enum\InstallmentStatus;
use App\Models\Business;
use App\Models\Installment\Installment;
use App\Models\Installment\InstallmentRequest;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use App\Traits\CustomAlert;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class InstallmentsTable extends DataTableComponent
{

    use CustomAlert;

    public $active;
    public $cancelled;

    public function builder(): Builder
    {
        $builder = Installment::query()
            ->with('installable', 'location')
            ->orderBy('installments.created_at', 'desc');

        if ($this->active){
            return $builder->where('installments.status', InstallmentStatus::ACTIVE);
        }

        if ($this->cancelled){
            return $builder->where('installments.status', InstallmentStatus::CANCELLED);
        }

        return $builder;
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setTableWrapperAttributes([
            'default' => true,
            'class' => 'table-bordered table-sm',
        ]);
        $this->setAdditionalSelects('installable_type', 'installable_id');
    }

    public function columns(): array
    {
        return [
            Column::make('Installable ID', 'installable_id')->hideIf(true),
            Column::make('ZIN', 'location.zin'),
            Column::make('Business Name', 'business.name')
                ->sortable()
                ->searchable(),
            Column::make('Branch Name', 'location.name')
                ->sortable()
                ->searchable(),
            Column::make('Type', 'taxType.name')
                ->sortable()
                ->searchable(),
            Column::make('Total Amount', 'installable.total_amount')
                ->label(function ($row){
                    return "{$row->installable->total_amount} {$row->installable->currency}";
                }),
            Column::make('Outstanding Amount', 'installable.outstanding_amount')
                ->label(function ($row){
                    return "{$row->installable->outstanding_amount} {$row->installable->currency}";
                }),
            Column::make('Requested At', 'created_at')
                ->searchable()
                ->sortable()
                ->format(function ($value, $row){
                    return $value->toFormattedDateString();
                }),
            Column::make('Status', 'status')
                ->view('installment.includes.status'),
            Column::make('Action', 'id')
                ->view('installment.includes.actions')
        ];
    }
}