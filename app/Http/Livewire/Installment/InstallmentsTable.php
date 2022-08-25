<?php

namespace App\Http\Livewire\Installment;

use App\Enum\InstallmentStatus;
use App\Models\Business;
use App\Models\Installment\Installment;
use App\Models\Installment\InstallmentRequest;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class InstallmentsTable extends DataTableComponent
{

    use LivewireAlert;

    public $active;
    public $cancelled;

    public function builder(): Builder
    {
        $builder = Installment::query()->orderBy('installments.created_at', 'desc');

        if ($this->active){
            return $builder->where('installments.status', InstallmentStatus::ACTIVE);
        }

        if ($this->cancelled){
            return $builder->where('installments.status', InstallmentStatus::CANCELLED);
        }

        return $builder;
    }

    public function columns(): array
    {
        return [
            Column::make('Business Name', 'business.name')
                ->sortable()
                ->searchable(),
            Column::make('Branch Name', 'location.name')
                ->sortable()
                ->searchable(),
            Column::make('Outstanding Amount', 'debt.outstanding_amount')
                ->searchable(),
            Column::make('Total Amount', 'debt.total_amount')
                ->sortable()
                ->searchable(),
            Column::make('Requested At', 'created_at')
                ->searchable()
                ->sortable()
                ->format(function ($value, $row){
                    return $value->toDateString();
                }),
            Column::make('Status', 'status')
                ->view('installment.includes.status'),
            Column::make('Action', 'id')
                ->view('installment.includes.actions')
        ];
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setTableWrapperAttributes([
            'default' => true,
            'class' => 'table-bordered table-sm',
        ]);
    }
}