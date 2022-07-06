<?php

namespace App\Http\Livewire\Business;

use App\Models\Business;
use Illuminate\Database\Eloquent\Builder;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class RegistrationsApprovalTable extends DataTableComponent
{
    
    use LivewireAlert;

    public function builder(): Builder
    {
        return Business::query()
            ->with('pinstances')
            ->where('verified_at', null)
            ->orderBy('created_at', 'desc');
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
            Column::make('Business Name', 'name')
                ->sortable()
                ->searchable(),
            Column::make('TIN', 'tin'),
            Column::make('Buss. Reg. No.', 'reg_no'),
            Column::make('Mobile', 'mobile'),
            Column::make('Date of Commencing', 'date_of_commencing')
                ->format(function($value){
                    return $value->toFormattedDateString();
                }),
            Column::make('Status', 'verified_at')
                ->view('business.registrations.includes.status'),
            Column::make('Action', 'id')
                ->view('business.registrations.includes.approval')
        ];
    }

}
