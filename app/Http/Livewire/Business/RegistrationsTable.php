<?php

namespace App\Http\Livewire\Business;

use App\Models\Business;
use Livewire\Component;
use Exception;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class RegistrationsTable extends DataTableComponent
{
    
    use LivewireAlert;

    protected $model = Business::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
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
                ->view('business.registrations.includes.actions')
        ];
    }

}
