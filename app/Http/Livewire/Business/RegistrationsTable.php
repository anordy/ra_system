<?php

namespace App\Http\Livewire\Business;

use App\Models\Business;
use App\Models\BusinessStatus;
use Illuminate\Database\Eloquent\Builder;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class RegistrationsTable extends DataTableComponent
{
    
    use LivewireAlert;

    public $rejected = false;
    public $pending = false;
    public $approved = true;

    public function builder(): Builder
    {
        if ($this->rejected){
            return Business::where('businesses.status', BusinessStatus::REJECTED)->orderBy('businesses.created_at', 'desc');
        }

        if ($this->approved){
            return Business::where('businesses.status', BusinessStatus::APPROVED)->orderBy('businesses.created_at', 'desc');
        }

        if ($this->pending){
            return Business::where('businesses.status', BusinessStatus::PENDING)->orderBy('businesses.created_at', 'desc');
        }

        return Business::where('businesses.status', '!=', BusinessStatus::DRAFT)->orderBy('businesses.created_at', 'desc');
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
            Column::make('ZIN(HQ)', 'id')
                ->format(function ($value, $row){
                    return $row->headquarter->zin;
                }),
            Column::make('Business Name', 'name')
                ->sortable()
                ->searchable(),
            Column::make('TIN', 'tin'),
            Column::make('Buss. Reg. No.', 'reg_no'),
            Column::make('Mobile', 'mobile'),
            Column::make('Status', 'status')
                ->view('business.registrations.includes.status'),
            Column::make('Action', 'id')
                ->view('business.registrations.includes.actions')
        ];
    }

}
