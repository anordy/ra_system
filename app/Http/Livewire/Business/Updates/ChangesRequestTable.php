<?php

namespace App\Http\Livewire\Business\Updates;

use App\Models\BusinessStatus;
use App\Models\BusinessUpdate;
use App\Traits\WithSearch;
use Illuminate\Database\Eloquent\Builder;
use App\Traits\CustomAlert;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\DataTableComponent;

class ChangesRequestTable extends DataTableComponent
{
    use CustomAlert, WithSearch;

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
        $this->setAdditionalSelects(['business_updates.status']);

    }

    public function mount($status)
    {
        $this->status = $status;
    }

    public function builder(): Builder
    {
        if ($this->status == BusinessStatus::PENDING) {
            return BusinessUpdate::where('business_updates.status', BusinessStatus::PENDING)->orderBy('business_updates.created_at', 'DESC')
                ->with('business');
        } else if ($this->status == BusinessStatus::APPROVED) {
            return BusinessUpdate::where('business_updates.status', BusinessStatus::APPROVED)->orderBy('business_updates.created_at', 'DESC')
                ->with('business');
        } else if ($this->status == BusinessStatus::REJECTED) {
            return BusinessUpdate::where('business_updates.status', BusinessStatus::REJECTED)->orderBy('business_updates.created_at', 'DESC')
                ->with('business');
        }else if ($this->status == BusinessStatus::CORRECTION) {
            return BusinessUpdate::where('business_updates.status', BusinessStatus::CORRECTION)->orderBy('business_updates.created_at', 'DESC')
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
