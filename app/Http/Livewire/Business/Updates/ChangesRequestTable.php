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
    public $status;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setTableWrapperAttributes([
            'default' => true,
            'class' => 'table-bordered table-sm',
        ]);
    }

    public function mount($status)
    {
        $this->status = $status;
    }

    public function builder(): Builder
    {
        if ($this->status == BusinessStatus::PENDING) {
            return BusinessUpdate::where('business_updates.status', BusinessStatus::PENDING)
                ->with('business');
        } else if ($this->status == BusinessStatus::APPROVED) {
            return BusinessUpdate::where('business_updates.status', BusinessStatus::APPROVED)
                ->with('business');
        } else if ($this->status == BusinessStatus::REJECTED) {
            return BusinessUpdate::where('business_updates.status', BusinessStatus::REJECTED)
                ->with('business');
        }
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
            Column::make('Date', 'created_at')
                ->format(function($value){
                    return $value->toFormattedDateString();
                }),
            Column::make('Change Type', 'type')
                ->view('business.updates.type'),
            Column::make('Action', 'id')
                ->view('business.updates.action'),
        ];
    }

}
