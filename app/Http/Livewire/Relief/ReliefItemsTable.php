<?php

namespace App\Http\Livewire\Relief;

use App\Models\Relief\ReliefItems;
use App\Traits\WithSearch;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class ReliefItemsTable extends DataTableComponent
{

    public $reliefId;

    public function mount($id)
    {
        $this->reliefId = decrypt($id);
    }

    public function builder(): builder
    {
        return ReliefItems::query()->where('relief_id', $this->reliefId);
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setTableWrapperAttributes([
            'default' => true,
            'class' => 'table-bordered table-sm',
        ]);
        $this->setAdditionalSelects(['relief_items.relief_id']);
    }

    public function columns(): array
    {
        return [
            Column::make("Item Name", "item_name")
                ->searchable()
                ->sortable(),
            Column::make("Quantity", "quantity")
                ->format(function ($value) {
                    return number_format($value, 1);
                })
                ->searchable(),
            Column::make("Unit Cost", "amount_per_item")
                ->format(function ($value) {
                    return number_format($value, 1);
                })
                ->searchable(),
            Column::make("Amount", "amount")
                ->format(function ($value) {
                    return number_format($value, 1);
                })
                ->searchable(),

        ];
    }
}
