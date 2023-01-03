<?php

namespace App\Http\Livewire\TaxClearance;

use App\Enum\TaxClearanceStatus;
use App\Models\TaxClearanceRequest;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Gate;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class TaxClearanceRequestTable extends DataTableComponent
{

    public $requested = false;
    public $rejected = false;
    public $approved = false;

    public function mount($status){

        if (!Gate::allows('tax-clearance-view')) {
            abort(403);
        }
        
        if($status == TaxClearanceStatus::APPROVED){
            $this->approved = true;
        } elseif($status == TaxClearanceStatus::REJECTED){
            
            $this->rejected = true;
        }
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setTableWrapperAttributes([
            'default' => true,
            'class' => 'table-bordered table-sm',
        ]);

    }

    public function builder(): Builder
    {
        if ($this->requested) {
            return TaxClearanceRequest::with('business')->where('tax_clearance_requests.status', TaxClearanceStatus::REQUESTED)->with('businessLocation')->orderBy('tax_clearance_requests.created_at', 'desc');
        }

        if ($this->approved) {
            return TaxClearanceRequest::with('business')->where('tax_clearance_requests.status', TaxClearanceStatus::APPROVED)->with('businessLocation')->orderBy('tax_clearance_requests.created_at', 'desc');
        }

        if ($this->rejected) {
            return TaxClearanceRequest::with('business')->where('tax_clearance_requests.status', TaxClearanceStatus::REJECTED)->with('businessLocation')->orderBy('tax_clearance_requests.created_at', 'desc');
        }


    }

    public function columns(): array
    {
        return [
            Column::make('Business Name', 'business.name')
                ->sortable()
                ->searchable(),
            Column::make('Branch', 'businessLocation.name')
                ->sortable()
                ->searchable(),
            Column::make('Status', 'status')->view('tax-clearance.includes.status'),
            Column::make('Action', 'id')->view('tax-clearance.includes.view'),
        ];
    }
}
