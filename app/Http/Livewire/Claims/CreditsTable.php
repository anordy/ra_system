<?php

namespace App\Http\Livewire\Claims;

use App\Models\Claims\TaxClaim;
use App\Models\WaiverObjection;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class CreditsTable extends DataTableComponent
{
    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setTableWrapperAttributes([
            'default' => true,
            'class' => 'table-bordered table-sm',
        ]);

        $this->additionalSelects = ['currency'];
    }

    public function builder(): Builder
    {
        return TaxClaim::query()->where('created_by_id', auth()->user()->id)->orderBy('tax_claims.created_at', 'DESC');
    }

    public function columns(): array
    {
        return [
            Column::make("Business Name", "business.name")
                ->sortable()
                ->searchable(),
            Column::make("Owner", "business.owner_designation")
                ->sortable()
                ->searchable(),
            Column::make("Claimed Amount", "amount")
                ->format(function ($value, $row){
                    $formattedAmount = number_format($row->amount, 2);
                    return "{$row->currency}. {$formattedAmount}";
                }),
            Column::make("Mobile", "business.mobile")
                ->sortable(),
//            Column::make('Status', 'status')
//                ->view('claims.includes.status'),
            Column::make('Action', 'id')
                ->view('claims.credits.includes.actions'),
        ];
    }
}
