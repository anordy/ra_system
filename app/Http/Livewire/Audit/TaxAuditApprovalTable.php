<?php

namespace App\Http\Livewire\Audit;

use App\Enum\TaxAuditStatus;
use App\Models\Region;
use App\Models\TaxAudit\TaxAudit;
use App\Models\TaxAudit\TaxAuditLocation;
use App\Models\TaxAudit\TaxAuditTaxType;
use App\Models\WorkflowTask;
use App\Traits\WithSearch;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use App\Traits\CustomAlert;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class TaxAuditApprovalTable extends DataTableComponent
{
    use CustomAlert;

    public $taxRegion;
    public $orderBy;

    public $model = WorkflowTask::class;

    /**
     * Mount the component.
     *
     * @param  mixed  $taxRegion The tax region for the component.
     * @return void
     */
    public function mount($taxRegion = null)
    {
        // if (!Gate::allows('tax-returns-vetting-view')) {
        //     abort(403);
        // }

        $this->taxRegion = $taxRegion;
    }

    /**
     * Get the query builder for the table.
     *
     * @return \Illuminate\Database\Eloquent\Builder The query builder for the table.
     */
    public function builder(): Builder
    {
        $query = TaxAudit::query()
            ->with('business', 'location', 'taxType', 'createdBy', 'pinstance')
            ->where('tax_audits.forwarded_to_investigation', false)
            ->whereHas('pinstance', function ($query) {
                $query->where('status', '!=', 'completed');
                $query->whereHas('actors', function ($query) {
                    $query->where('user_id', auth()->id());
                });
            })
            ->whereHas('location.taxRegion', function ($query) {
                if ($this->taxRegion == Region::LTD) {
                    $query->whereIn('location', [Region::LTD, Region::UNGUJA]); //this is filter by department
                } else {
                    $query->where('location', $this->taxRegion); //this is filter by department
                }
            });

        return $query;
    }

    /**
     * Configure the table component.
     */
    public function configure(): void
    {
        $this->setPrimaryKey('id');
        // $this->setAdditionalSelects('pinstance_type', 'user_type');

        $this->setTableWrapperAttributes([
            'default' => true,
            'class' => 'table-bordered table-sm',
        ]);
    }

    public function columns(): array
    {
        return [
            Column::make('ZTN No', 'business.ztn_number'),
            Column::make('TIN', 'business.tin'),
            Column::make('Business Name', 'business.name'),
            Column::make('Location', 'location_id')->format(function ($value, $row){
                return $row->taxAuditLocationNames();
            }),
            Column::make('Tax Types', 'tax_type_id')->format(function ($value, $row){
                return $row->taxAuditTaxTypeNames();
            }),
            Column::make('Period From', 'period_from')->format(function ($value, $row){
                return $row->period_from ? $row->period_from->toFormattedDateString() : 'N/A';
            }),
            Column::make('Period To', 'period_to')->format(function ($value, $row){
                return $row->period_to ? $row->period_to->toFormattedDateString() : 'N/A';
            }),
            Column::make('Action', 'id')
                ->view('audit.approval.action')
        ];
    }
}
