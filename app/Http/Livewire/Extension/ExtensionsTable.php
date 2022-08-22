<?php

namespace App\Http\Livewire\Extension;

use App\Models\Business;
use App\Models\Extension\ExtensionRequest;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class ExtensionsTable extends DataTableComponent
{

    use LivewireAlert;

    public function builder(): Builder
    {
        return ExtensionRequest::query()->orderBy('extension_requests.created_at', 'desc');
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
                ->view('extension.includes.status'),
            Column::make('Action', 'id')
                ->view('extension.includes.actions')
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