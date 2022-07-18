<?php

namespace App\Http\Livewire\Business\Updates;

use App\Models\BusinessStatus;
use Exception;
use Carbon\Carbon;
use App\Models\BusinessTempClosure;
use App\Models\BusinessUpdate;
use Illuminate\Database\Eloquent\Builder;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\DataTableComponent;

class ChangesRequestTable extends DataTableComponent
{
    use LivewireAlert;


    protected $listeners = [
        'confirmed',
    ];

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        // $this->setAdditionalSelects(['is_extended']);
    }

    public function builder(): Builder
    {
        return BusinessUpdate::query();
    }

    public function columns(): array
    {
        return [
            Column::make('Name', 'business.name')
                ->sortable()
                ->searchable(),
            Column::make('TIN', 'business.tin')
                ->sortable()
                ->searchable(),
            Column::make('Reg No.', 'business.reg_no')
                ->sortable()
                ->searchable(),
            Column::make('Action', 'id')
                ->view('business.updates.action'),
        ];
    }

}
