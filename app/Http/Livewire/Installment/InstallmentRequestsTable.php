<?php

namespace App\Http\Livewire\Installment;

use App\Enum\InstallmentRequestStatus;
use App\Models\Installment\InstallmentRequest;
use Illuminate\Database\Eloquent\Builder;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class InstallmentRequestsTable extends DataTableComponent
{

    use LivewireAlert;

    public $pending;
    public $rejected;

    public function builder(): Builder
    {
        $builder = InstallmentRequest::orderBy('installment_requests.created_at', 'desc');

        if ($this->pending){
            return $builder->where('installment_requests.status', InstallmentRequestStatus::PENDING);
        }

        if ($this->rejected){
            return $builder->where('installment_requests.status', InstallmentRequestStatus::REJECTED);
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
                ->view('installment.requests.includes.status'),
            Column::make('Action', 'id')
                ->view('installment.requests.includes.actions')
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