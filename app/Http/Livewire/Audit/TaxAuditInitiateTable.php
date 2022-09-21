<?php

namespace App\Http\Livewire\Audit;

use App\Enum\TaxAuditStatus;
use App\Enum\TaxVerificationStatus;
use App\Models\Investigation\TaxInvestigation;
use App\Models\TaxAudit\TaxAudit;
use App\Models\TaxAudit\TaxAuditLocation;
use App\Models\TaxAudit\TaxAuditTaxType;
use App\Traits\WorkflowProcesssingTrait;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class TaxAuditInitiateTable extends DataTableComponent
{

    use LivewireAlert, WorkflowProcesssingTrait;

    public $model = TaxAudit::class;

    public function builder(): Builder
    {
        return TaxAudit::query()
            ->with('business', 'location', 'taxType', 'createdBy')
            ->where('tax_audits.status', TaxAuditStatus::DRAFT);
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setAdditionalSelects(['created_by_type', 'created_by_id']);
        $this->setTableWrapperAttributes([
            'default' => true,
            'class' => 'table-bordered table-sm',
        ]);
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
            // Column::make('ZRB No', 'location.zin'),
            Column::make('TIN', 'business.tin'),
            Column::make('Business Name', 'business.name'),
            // Column::make('Business Location', 'id')
            // ->label(fn ($row) => $row->location_id != 0 ? $row->location->name : ''),
            // Column::make('TaxType', 'id')
            // ->label(fn ($row) => $row->tax_type_id != 0 ? $row->taxType->name : ''),
            Column::make('Period From', 'period_from'),
            Column::make('Period To', 'period_to'),
            Column::make('Created By', 'created_by_id')
                ->label(fn ($row) => $row->createdBy->full_name ?? ''),
            Column::make('Created On', 'created_at')
                ->label(fn ($row) => Carbon::create($row->created_at ?? null)->toDayDateTimeString()),
            Column::make('Action', 'id')
                ->format(function ($value) {
                    return <<<HTML
                           <button class="btn btn-info btn-sm" wire:click="approve($value)"><i class="fa fa-arrow-right"></i> Initiate Approval</button>
                           <button class="btn btn-danger btn-sm" wire:click="delete($value)"><i class="fa fa-trash"></i> </button>
                    HTML;
                })
                ->html(true),

        ];
    }


    public function approve($id)
    {
        $this->alert('warning', 'Are you sure you want to initiate Approvals ?', [
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
            $this->flash('success', 'Approval initiated successfully', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            report($e);
            $this->alert('warning', 'Something whent wrong!!!', ['onConfirmed' => 'confirmed', 'timer' => 2000]);
        }
    }

    public function delete($id)
    {
        $this->alert('warning', 'Are you sure you want to delete ?', [
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
            TaxAudit::find($data->id)->delete();
            $this->flash('success', 'Record deleted successfully', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            report($e);
            $this->alert('warning', 'Something whent wrong!!!', ['onConfirmed' => 'confirmed', 'timer' => 2000]);
        }
    }
}
