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
        $builder = InstallmentRequest::with('installable', 'location')
            ->orderBy('installment_requests.created_at', 'desc');

        if ($this->rejected){
            return $builder->where('installment_requests.status', InstallmentRequestStatus::REJECTED);
        }

        return $builder->where('installment_requests.status', InstallmentRequestStatus::APPROVED);
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
            Column::make('Extension ID', 'installable_id')->hideIf(true),
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
                ->view('installment.requests.includes.status'),
            Column::make('Action', 'id')
                ->view('installment.requests.includes.actions')
        ];
    }
}