<?php

namespace App\Http\Livewire\Business\TaxType;

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
        }
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    public function getTaxNameById($taxId)
    {
        return TaxType::find($taxId)->name;
    }

    public function columns(): array
    {
        return [
            Column::make("Business Name", "business.name")
                ->sortable(),
            Column::make("Old Tax Types", "old_taxtype")
                ->sortable()
                ->format(function ($value, $row) {
                    $datas = json_decode($value);
                    $taxtypes = "";
                    foreach ($datas as $data) {
                        $taxtypes .= "{$data->name}, ";
                    }
                    return $taxtypes;
                }),
            Column::make("New Tax Types", "new_taxtype")
                ->searchable()
                ->format(function ($value, $row) {
                    $datas = json_decode($value);
                    $taxtypes = "";
                    foreach ($datas as $data) {
                        $taxtypes .= "{$this->getTaxNameById($data->tax_type_id)}, ";
                    }
                    return $taxtypes;
                }),
            Column::make("Date of Request", "created_at")
                ->format(function ($value, $row) {
                    return Carbon::create($value)->format('d-m-Y');
                })
                ->searchable(),
            Column::make('Status', 'status')->view('business.taxtypes.includes.status'),
            Column::make('Action', 'id')->view('business.taxtypes.includes.actions'),
        ];
    }
}
