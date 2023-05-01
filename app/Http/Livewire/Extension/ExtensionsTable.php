<?php

namespace App\Http\Livewire\Extension;

use App\Enum\ExtensionRequestStatus;
use App\Models\Business;
use App\Models\Extension\ExtensionRequest;
use App\Traits\WithSearch;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use App\Traits\CustomAlert;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class ExtensionsTable extends DataTableComponent
{

    use CustomAlert;

    public $rejected;

    public function builder(): Builder
    {
        $builder = ExtensionRequest::query()
            ->with('extensible', 'location')
            ->orderBy('extension_requests.created_at', 'desc');

        if ($this->rejected){
            return $builder->where('extension_requests.status', ExtensionRequestStatus::REJECTED);
        }

        return $builder->where('extension_requests.status', ExtensionRequestStatus::APPROVED);
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setTableWrapperAttributes([
            'default' => true,
            'class' => 'table-bordered table-sm',
        ]);
        $this->setAdditionalSelects('extensible_type', 'extensible_id');
    }

    public function columns(): array
    {
        return [
            Column::make('Extension ID', 'extensible_id')->hideIf(true),
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
            Column::make('Total Amount', 'extensible.total_amount')
                ->label(function ($row){
                    return "{$row->extensible->total_amount} {$row->extensible->currency}";
                }),
            Column::make('Outstanding Amount', 'extensible.outstanding_amount')
                ->label(function ($row){
                    return "{$row->extensible->outstanding_amount} {$row->extensible->currency}";
                }),
            Column::make('Requested At', 'created_at')
                ->searchable()
                ->sortable()
                ->format(function ($value, $row){
                    return $value->toFormattedDateString();
                }),
            Column::make('Status', 'status')
                ->view('extension.includes.status'),
            Column::make('Action', 'id')
                ->view('extension.includes.actions')
        ];
    }
}