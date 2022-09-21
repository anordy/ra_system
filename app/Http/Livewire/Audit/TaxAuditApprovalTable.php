<?php

namespace App\Http\Livewire\Audit;

use App\Models\TaxAudit\TaxAudit;
use App\Models\TaxAudit\TaxAuditLocation;
use App\Models\TaxAudit\TaxAuditTaxType;
use App\Models\WorkflowTask;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class TaxAuditApprovalTable extends DataTableComponent
{
    use LivewireAlert;

    public $model = WorkflowTask::class;

    public function builder(): Builder
    {
        return WorkflowTask::with('pinstance', 'pinstance.location', 'pinstance.business', 'user')
            ->where('pinstance_type', TaxAudit::class)
            ->where('status', '!=', 'completed')
            ->where('owner', 'staff')
            ->whereJsonContains('operators', auth()->user()->id);
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setAdditionalSelects('pinstance_type', 'user_type');
        $this->setTableWrapperAttributes([
            'default' => true,
            'class' => 'table-bordered table-sm',
        ]);
    }

    public function columns(): array
    {
        return [
            Column::make('pinstance_id', 'pinstance_id')->hideIf(true),
            Column::make('user_type', 'user_id')->hideIf(true),
            // Column::make('Z_Number', 'pinstance.location.zin')
            //     ->label(fn ($row) => $row->pinstance->location_id != 0 ? $row->pinstance->location->zin : '')
            //     ->searchable(),
            Column::make('TIN', 'pinstance.business.tin')
                ->label(fn ($row) => $row->pinstance->business->tin ?? ''),
            Column::make('Business Name', 'pinstance.business.name')
                ->label(fn ($row) => $row->pinstance->business->name ?? ''),
            // Column::make('Business Location', 'location.name')
            //     ->label(fn ($row) => $row->pinstance->location_id != 0 ? $row->pinstance->location->name : ''),
            // Column::make('Tax Type', 'pinstance.taxType.name')
            //     ->label(fn ($row) => $row->pinstance->tax_type_id != 0 ? $row->pinstance->taxType->name : ''),
            // Column::make('Business Location', 'id')
            //     ->sortable()
            //     ->format(
            //         function($row,$value){
            //             return $row;
            //             $taxAuditLocations = TaxAuditLocation::where('tax_audit_id', $value)
            //             ->get();
            //             $locations = null;
            //             foreach ($taxAuditLocations as $key => $taxAuditLocation) {
            //                 if($key!=0){
            //                     $locations .= ', '; 
            //                 }
            //                 $locations .= $taxAuditLocation->businessLocation->name;
            //             }
            //             return $locations;
            //         }
            //     ),
            // Column::make('TaxType', 'id')
            // ->sortable()
            //     ->format(
            //         function($value){
            //             $taxAuditTaxTypes = TaxAuditTaxType::where('tax_audit_id', $value)
            //             ->get();
            //             $taxTypes = null;
            //             foreach ($taxAuditTaxTypes as $key => $taxAuditTaxType) {
            //                 if($key!=0){
            //                     $taxTypes .= ', '; 
            //                 }
            //                 $taxTypes .= $taxAuditTaxType->taxType->name;
            //             }
            //             return $taxTypes;
            //         }
            //     ),
            Column::make('Period From', 'pinstance.period_from')
                ->label(fn ($row) => $row->pinstance->period_from ?? ''),
            Column::make('Period To', 'pinstance.period_to')
                ->label(fn ($row) => $row->pinstance->period_to ?? ''),
            Column::make('Filled By', 'pinstance.created_by_id')
                ->label(function ($row) {
                    $user = $row->pinstance->createdBy;
                    return $user->full_name ?? '';
                }),
            Column::make('Filled On', 'created_at')
                ->format(fn ($value) => Carbon::create($value)->toDayDateTimeString()),
            Column::make('Action', 'pinstance_id')
                ->view('audit.approval.action')
                ->html(true),

        ];
    }
}
