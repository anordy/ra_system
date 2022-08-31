<?php

namespace App\Http\Livewire\Claims;

use App\Enum\TaxClaimStatus;
use App\Models\Claims\TaxClaim;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class ClaimsTable extends DataTableComponent
{
    public $pending;
    public $rejected;

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
        if ($this->pending){
            return TaxClaim::query()
                ->where('tax_claims.status', TaxClaimStatus::PENDING)
                ->orderBy('tax_claims.created_at', 'DESC');
        }
        if ($this->rejected){
            return TaxClaim::query()
                ->where('tax_claims.status', TaxClaimStatus::REJECTED)
                ->orderBy('tax_claims.created_at', 'DESC');
        }
        return TaxClaim::query()->orderBy('tax_claims.created_at', 'DESC');
    }

    public function columns(): array
    {
        return [
            Column::make("Business Name", "business.name")
                ->sortable()
                ->searchable(),
            Column::make("Claimed Amount", "amount")
                ->format(function ($value, $row){
                    $formattedAmount = number_format($row->amount, 2);
                    return "{$row->currency}. {$formattedAmount}";
                }),
            Column::make("Owner", "business.owner_designation")
                ->sortable()
                ->searchable(),
            Column::make("Mobile", "business.mobile")
                ->sortable(),
            Column::make('Status', 'status')
                ->view('claims.includes.status'),
            Column::make('Action', 'id')
                ->view('claims.includes.actions'),
        ];
    }
}
