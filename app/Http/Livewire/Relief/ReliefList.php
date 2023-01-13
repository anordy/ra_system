<?php

namespace App\Http\Livewire\Relief;

use App\Models\Relief\Relief;
use App\Models\Business;
use App\Models\BusinessLocation;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class ReliefList extends DataTableComponent
{

    //create builder function
    public function builder(): builder
    {
        //order by created_at desc 
        return Relief::with('business', 'location')->orderBy('reliefs.updated_at', 'desc');
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setTableWrapperAttributes([
            'default' => true,
            'class' => 'table-bordered table-sm',
        ]);
        $this->setAdditionalSelects(['business_id', 'location_id', 'project_id', 'project_list_id']);
    }

    public function columns(): array
    {
        return [
            Column::make("Business", "business.name")
                ->searchable(),
            Column::make("Location", "location.name")
                ->label(fn($row) => $row->location->name ?? ''),
            Column::make("Amount", "total_amount")
                ->format(function ($value) {
                    return number_format($value, 1);
                })
                ->searchable(),
            Column::make("VAT %", "vat")
                ->format(function ($value) {
                    return number_format($value, 1);
                })
                ->searchable(),
            Column::make("VAT Amount", "vat_amount")
                ->format(function ($value) {
                    return number_format($value, 1);
                })
                ->searchable(),
            Column::make("Rate %", "rate")
                ->format(function ($value) {
                    return number_format($value, 1);
                })
                ->searchable(),
            Column::make("Relieved Amount", "relieved_amount")
                ->format(function ($value) {
                    return number_format($value, 1);
                })
                ->searchable(),
            Column::make("Payable Amount", "amount_payable")
                ->format(function ($value) {
                    return number_format($value, 1);
                })
                ->searchable(),
            Column::make("Expiration", "expire")
                ->format(function ($value) {
                    return date('d/m/Y', strtotime($value));
                })
                ->searchable(),
            Column::make("Status")
                ->label(fn ($row) => view("relief.includes.relief-status", compact('row'))),
            Column::make("Actions", "id")->view("relief.includes.actions"),
        ];
    }

    public function deleteRelief($id)
    {
        $relief = Relief::find($id);
        $relief->reliefAttachments()->delete();
        $relief->reliefItems()->delete();
        $relief->delete();
    }
}
