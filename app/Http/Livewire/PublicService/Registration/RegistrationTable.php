<?php

namespace App\Http\Livewire\PublicService\Registration;

use App\Models\PublicService\PublicServiceMotor;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class RegistrationTable extends DataTableComponent
{
    public $status;

    public function mount($status = '')
    {
        $this->status = $status;
    }

    public function builder(): Builder
    {
        $query = PublicServiceMotor::query();

        if ($this->status){
            $query->where('public_service_motors.status', $this->status);
        }

        return $query->orderByDesc('public_service_motors.created_at');
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
            Column::make('Business Name', 'business.name')
                ->searchable(),
            Column::make('Plate Number', 'mvr.plate_number')
                ->searchable(),
            Column::make('Registration Type', 'mvr.regtype.name')
                ->searchable(),
            Column::make('Vehicle Class', 'mvr.class.name')
                ->searchable(),
            Column::make('Status', 'status')
                ->view('public-service.includes.status'),
            Column::make('Actions', 'id')
                ->view('public-service.includes.actions'),

        ];
    }

}
