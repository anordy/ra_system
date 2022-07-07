<?php

namespace App\Http\Livewire\Business\Closure;

use App\Models\BusinessStatus;
use Exception;
use Carbon\Carbon;
use App\Models\BusinessTempClosure;
use Illuminate\Database\Eloquent\Builder;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\DataTableComponent;

class ApprovedClosuresTable extends DataTableComponent
{
    use LivewireAlert;


    protected $listeners = [
        'confirmed',
    ];

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setAdditionalSelects(['is_extended']);
    }

    public function builder(): Builder
    {
        return BusinessTempClosure::query()->where('business_temp_closures.status', BusinessStatus::APPROVED)->orderBy('business_temp_closures.opening_date', 'DESC');
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
            Column::make('Closing Date', 'closing_date')
                ->format(function($value, $row) { return Carbon::create($row->closing_date)->toFormattedDateString(); })
                ->sortable()
                ->searchable(),
            Column::make('Opening Date', 'opening_date')
                ->format(function($value, $row) { return Carbon::create($row->opening_date)->toFormattedDateString(); })
                ->sortable()
                ->searchable(),
            Column::make('Closure Reason', 'reason')
                ->sortable()
        ];
    }

}
