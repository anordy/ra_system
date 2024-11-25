<?php

namespace App\Http\Livewire\Claims;

use App\Enum\TaxClaimStatus;
use App\Models\Claims\TaxClaim;
use App\Models\TaxType;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class UncategorizedClaimsTable extends DataTableComponent
{

    public $pending;
    public $rejected;
    public $status;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setTableWrapperAttributes([
            'default' => true,
            'class' => 'table-bordered table-sm',
        ]);
    }

    public function mount($status) {
        $this->status = $status;
    }

    public function builder(): Builder
    {
        if ($this->status === TaxClaimStatus::PENDING){
            return TaxClaim::with('business', 'location', 'taxType')
                ->where('tax_claims.status', $this->status)
                ->whereHas('pinstance', function ($query) {
                    $query->where('workflow_tasks.status', '!=', 'completed');
                    $query->whereHas('actors', function ($query) {
                        $query->where('user_id', auth()->id());
                    });
                })
                ->orderBy('tax_claims.created_at');
        }

        return TaxClaim::with('business', 'location', 'taxType')
            ->where('tax_claims.status', $this->status)
            ->orderBy('tax_claims.created_at');
    }

    public function columns(): array
    {
        return [
            Column::make('Business Name', 'business.name')
                ->sortable()
                ->searchable(),
            Column::make('Branch', 'location.name')
                ->sortable()
                ->searchable()
                ->format(function ($value, $row) {
                    return $value ?? 'N/A';
                }),
            Column::make('Claimed Amount', 'amount')
                ->sortable()
                ->format(function ($value, $row) {
                    return number_format($value, 2);
                })
                ->searchable(),
            Column::make('Currency', 'currency')
                ->sortable()
                ->searchable(),
            Column::make('Tax Type', 'taxType.name')
                ->sortable()
                ->searchable()
                ->format(function($value, $row){
                    $t = 'taxtype.name';
                    return $row->$t;
                }),
            Column::make('Mobile', 'business.mobile')
                ->sortable()
                ->searchable()
                ->format(function ($value){
                    return $value ?? 'N/A';
                }),
            Column::make('Status', 'status')
                ->format(function ($vlaue, $row){
                    return view('claims.includes.status', ['row' => $row]);
                }),
            Column::make('Created On', 'created_at'),
            Column::make('Action', 'id')
                ->format(function ($value, $row){
                    return view('claims.includes.actions', ['value' => $row->id]);
                }),
        ];
    }
}
