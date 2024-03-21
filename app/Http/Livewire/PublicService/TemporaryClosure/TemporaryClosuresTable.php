<?php

namespace App\Http\Livewire\PublicService\TemporaryClosure;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\PublicService\TemporaryClosure as TemporaryClosureModel;

class TemporaryClosuresTable extends DataTableComponent
{

    public $status;

    public function mount($status = '')
    {
        $this->status = $status;
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setTableWrapperAttributes([
            'default' => true,
            'class' => 'table-bordered table-sm',
        ]);
    }

    public function builder(): Builder
    {
        $query = TemporaryClosureModel::query()
            ->where('created_by', Auth::id());

        if($this->status){
            $query->where('public_service_temporary_closures.status', $this->status);
        }

        return $query->orderBy('public_service_temporary_closures.created_at', 'DESC');
    }

    public function columns(): array
    {
        return [
            Column::make('Business Name', 'motor.business.name')
                ->searchable(),
            Column::make(__('Plate Number'), 'motor.mvr.plate_number')
                ->sortable()
                ->searchable(),
            Column::make(__('Closing Date'), 'closing_date')
                ->format(function ($value, $row) {
                    return Carbon::create($row->closing_date)->toFormattedDateString();
                })
                ->sortable()
                ->searchable(),
            Column::make(__('Opening Date'), 'opening_date')
                ->format(function ($value, $row) {
                    return Carbon::create($row->opening_date)->toFormattedDateString();
                })
                ->sortable()
                ->searchable(),
            Column::make(__('Request Status'), 'status')->view('public-service.temporary-closures.includes.status'),
            Column::make(__('Action'), 'id')->view('public-service.temporary-closures.includes.actions')
        ];

    }


}
