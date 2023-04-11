<?php

namespace App\Http\Livewire\Settings\DualControlActivity;

use App\Models\DualControlHistory;
use App\Traits\DualControlActivityTrait;
use Illuminate\Database\Eloquent\Builder;
use App\Traits\CustomAlert;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\DualControl;

class HistoryTable extends DataTableComponent
{
    use CustomAlert, DualControlActivityTrait;
    public $Id;

    protected $model = DualControlHistory::class;

    public function mount($dualControlId)
    {
        $this->Id = decrypt($dualControlId);
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setTableWrapperAttributes([
            'default' => true,
            'class' => 'table-bordered',
        ]);
    }

    public function builder(): Builder
    {
        return DualControlHistory::where('dual_control_id', $this->Id)->orderBy('id');
    }

    public function columns(): array
    {
        return [
            Column::make("Module", "controllable_type")
                ->sortable()->format(function ($value){
                    return $this->getModule($value);
                }),
            Column::make("Approved By", "approved_by")
                ->sortable()->searchable()->format(function ($value){
                    return getUser($value);
                }),
            Column::make("Approved At", "approved_at")
                ->sortable()->searchable(),
            Column::make("Comment", "comment")
                ->sortable()->searchable(),
            Column::make('Action', 'action')
            ->sortable()->searchable(),
        ];
    }
}
