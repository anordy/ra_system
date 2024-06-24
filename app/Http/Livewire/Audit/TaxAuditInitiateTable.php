<?php

namespace App\Http\Livewire\Audit;

use App\Enum\TaxAuditStatus;
use App\Events\SendMail;
use App\Events\SendSms;
use App\Models\Region;
use App\Models\TaxAudit\TaxAudit;
use App\Traits\WithSearch;
use App\Traits\WorkflowProcesssingTrait;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use App\Traits\CustomAlert;
use Illuminate\Support\Facades\Gate;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class TaxAuditInitiateTable extends DataTableComponent
{
    public $taxRegion;
    public $orderBy;

    use CustomAlert, WorkflowProcesssingTrait;

    public $model = TaxAudit::class;

    public function mount($taxRegion)
    {
        // if (!Gate::allows('tax-returns-vetting-view')) {
        //     abort(403);
        // }

        $this->taxRegion = $taxRegion;
    }


    public function builder(): Builder
    {
        return TaxAudit::query()
            ->with('business', 'location', 'taxType', 'createdBy')
            ->where('tax_audits.status', TaxAuditStatus::DRAFT)
            ->where('tax_audits.forwarded_to_investigation', false)
            ->whereHas('location.taxRegion', function ($query) {
                if ($this->taxRegion == Region::LTD) {
                    $query->whereIn('location', [Region::LTD, Region::UNGUJA]); //this is filter by department
                } else {
                    $query->where('location', $this->taxRegion); //this is filter by department
                }
            });
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setAdditionalSelects(['created_by_type', 'created_by_id']);
        $this->setTableWrapperAttributes([
            'default' => true,
            'class' => 'table-bordered table-sm',
        ]);

        $this->setThAttributes(function (Column $column) {
            if ($column->getTitle() == 'Tax Types') {
                return [
                    'style' => 'width: 20%;',
                ];
            }
            return [];
        });
    }

    protected $listeners = [
        'approve',
        'confirmed',
        'initiateApproval',
        'delete',
    ];

    public function columns(): array
    {
        return [
            Column::make('ZTN No', 'business.ztn_number'),
            Column::make('TIN', 'business.tin'),
            Column::make('Business Name', 'business.name'),
            Column::make('Business Location')
                ->label(fn ($row) => $row->taxAuditLocationNames()),
            Column::make('Tax Types')
                ->label(fn ($row) => $row->taxAuditTaxTypeNames()),
            Column::make('Period From', 'period_from')
                ->format(fn ($value) => Carbon::create($value)->format('d-m-Y')),
            Column::make('Period To', 'period_to')
                ->format(fn ($value) => Carbon::create($value)->format('d-m-Y')),
            Column::make('Created By', 'created_by_id')
                ->label(fn ($row) => $row->createdBy->full_name ?? ''),
            Column::make('Created On', 'created_at')
                ->label(fn ($row) => Carbon::create($row->created_at ?? null)->format('d-m-Y')),
            Column::make('Action', 'id')
                ->format(function ($value) {
                    $url = route('tax_auditing.approvals.show', encrypt($value));
                    return <<<HTML
                        <div class="d-flex justify-content-between">
                            <button class="btn btn-info btn-sm" wire:click="approve($value)"><i class="bi bi-check-circle-fill"></i> Initiate Approval</button>
                            <a href="{$url}" class="btn btn-outline-info btn-sm" title="View"><i class="fa fa-eye"></i> View More </a>
                            <button class="btn btn-danger btn-sm" wire:click="delete($value)"><i class="bi bi-trash-fill"></i> Delete</button>
                        </div>
                    HTML;
                })
                ->html(true),

        ];
    }


    public function approve($id)
    {
        $this->customAlert('warning', 'Are you sure you want to initiate Approvals ?', [
            'position' => 'center',
            'toast' => false,
            'showConfirmButton' => true,
            'confirmButtonText' => 'Yes',
            'onConfirmed' => 'initiateApproval',
            'showCancelButton' => true,
            'cancelButtonText' => 'Cancel',
            'timer' => null,
            'data' => [
                'id' => $id,
            ],

        ]);
    }

    public function initiateApproval($value)
    {
        try {
            $data = (object) $value['data'];
            $audit = TaxAudit::find($data->id);
            $this->registerWorkflow(get_class($audit), $audit->id);
            $this->doTransition('start', []);
            $audit->status = TaxAuditStatus::PENDING;
            $audit->save();

            $taxpayer = $audit->business->taxpayer;
            event(new SendMail('audit-notification-to-taxpayer', $taxpayer));
            event(new SendSms('audit-notification-to-taxpayer', $taxpayer));

            $this->flash('success', 'Approval initiated successfully', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            report($e);
            $this->customAlert('warning', 'Something whent wrong', ['onConfirmed' => 'confirmed', 'timer' => 2000]);
        }
    }

    public function delete($id)
    {
        $this->customAlert('warning', 'Are you sure you want to delete ?', [
            'position' => 'center',
            'toast' => false,
            'showConfirmButton' => true,
            'confirmButtonText' => 'Delete',
            'onConfirmed' => 'confirmed',
            'showCancelButton' => true,
            'cancelButtonText' => 'Cancel',
            'timer' => null,
            'data' => [
                'id' => $id
            ],

        ]);
    }

    public function confirmed($value)
    {
        try {
            $data = (object) $value['data'];
            TaxAudit::findOrFail($data->id)->delete();
            $this->flash('success', 'Record deleted successfully', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            report($e);
            $this->customAlert('warning', 'Something went wrong', ['onConfirmed' => 'confirmed', 'timer' => 2000]);
        }
    }
}
