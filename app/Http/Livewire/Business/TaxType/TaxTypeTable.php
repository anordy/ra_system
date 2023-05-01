<?php

namespace App\Http\Livewire\Business\TaxType;

use App\Traits\WithSearch;
use Carbon\Carbon;
use App\Models\TaxType;
use App\Models\BranchStatus;
use App\Models\BusinessTaxTypeChange;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\DataTableComponent;

class TaxTypeTable extends DataTableComponent
{

    protected $model = BusinessTaxTypeChange::class;
    public $status;

    public function mount($status)
    {
        $this->status = $status;
    }

    public function builder(): Builder
    {
        if ($this->status == BranchStatus::PENDING) {
            return BusinessTaxTypeChange::where('business_tax_type_changes.status', BranchStatus::PENDING)->orderBy('business_tax_type_changes.created_at', 'DESC');
        } else if ($this->status == BranchStatus::APPROVED) {
            return BusinessTaxTypeChange::where('business_tax_type_changes.status', BranchStatus::APPROVED)->orderBy('business_tax_type_changes.created_at', 'DESC');
        } else if ($this->status == BranchStatus::REJECTED) {
            return BusinessTaxTypeChange::where('business_tax_type_changes.status', BranchStatus::REJECTED)->orderBy('business_tax_type_changes.created_at', 'DESC');
        } else if ($this->status != BranchStatus::APPROVED) {
            return BusinessTaxTypeChange::where('business_tax_type_changes.status', '!=', BranchStatus::APPROVED)->orderBy('business_tax_type_changes.created_at', 'DESC');
        }
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');

        $this->setAdditionalSelects(['to_sub_vat_id']);

        $this->setTableWrapperAttributes([
            'default' => true,
            'class' => 'table-bordered table-sm',
        ]);
    }

    public function columns(): array
    {
        return [
            Column::make("Business Name", "business.name")
                ->sortable(),
            Column::make("From Tax Type", "from_tax_type_id")
                ->sortable()
                ->format(function ($value, $row) {
                    return $row->fromTax->name;
                }),
            Column::make("To Tax Type", "to_tax_type_id")
                ->searchable()
                ->format(function ($value, $row) {
                    if ($row->toTax->code == 'vat') {
                        return $row->subvat->name;
                    } else {
                        return $row->toTax->name;
                    }
                }),
            Column::make("Date of Request", "created_at")
                ->format(function ($value, $row) {
                    return Carbon::create($value)->format('M d, Y');
                })
                ->searchable(),
            Column::make('Status', 'status')->view('business.taxtypes.includes.status'),
            Column::make('Action', 'id')->view('business.taxtypes.includes.actions'),
        ];
    }
}
