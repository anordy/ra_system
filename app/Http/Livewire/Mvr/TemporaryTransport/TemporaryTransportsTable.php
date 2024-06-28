<?php

namespace App\Http\Livewire\Mvr\TemporaryTransport;

use App\Models\MvrTemporaryTransport;
use App\Traits\CustomAlert;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class TemporaryTransportsTable extends DataTableComponent
{
    use CustomAlert;

    private $status;

    public function mount($status = null){
        $this->status = $status;
    }

    public function builder(): Builder
    {
        $query =  MvrTemporaryTransport::query()
            ->orderBy('mvr_temporary_transports.created_at', 'desc');

        if ($this->status !== null) {
            $query->where('mvr_temporary_transports.status', $this->status);
        }

        return $query;
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
            Column::make(__("Chassis No"), "mvr.chassis.chassis_number")
                ->searchable(),
            Column::make(__("Registration No"), "mvr.plate_number")
                ->format(function ($value, $row) {
                    return $value ?? 'PENDING';
                })
                ->searchable(),
            Column::make(__("Serial No"), "mvr.registration_number")
                ->format(function ($value, $row) {
                    return $value ?? 'N/A';
                })
                ->searchable(),
            Column::make(__("Registration Type"), "mvr.regtype.name")
                ->searchable(),
            Column::make(__("Date of Travel"), "date_of_travel")
                ->format(function ($value, $row) {
                    if ($value) {
                        return Carbon::create($value)->format('d M Y');
                    }
                    return 'N/A';
                }),
            Column::make(__("Date of Return"), "date_of_return")
                ->format(function ($value, $row) {
                    if ($value) {
                        return Carbon::create($value)->format('d M Y');
                    }
                    return 'N/A';
                }),
            Column::make(__('Status'), 'status')->view('mvr.temporary-transports.includes.status'),
            Column::make(__('Action'), 'id')->view('mvr.temporary-transports.includes.actions'),
        ];
    }
}
