<?php

namespace App\Http\Livewire\Investigation;

use App\Enum\TaxInvestigationStatus;
use App\Enum\TaxVerificationStatus;
use App\Models\Investigation\TaxInvestigation;
use App\Models\WorkflowTask;
use App\Traits\WithSearch;
use App\Traits\WorkflowProcesssingTrait;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use App\Traits\CustomAlert;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class TaxInvestigationInitiateTable extends DataTableComponent
{

    use CustomAlert, WorkflowProcesssingTrait;

    public $model = TaxInvestigation::class;

    public function builder(): Builder
    {
        return TaxInvestigation::query()
            ->with('business', 'location', 'taxType', 'createdBy')
            ->where('tax_investigations.status', TaxInvestigationStatus::DRAFT);
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
                ->label(fn ($row) => $row->taxInvestigationLocationNames() ?? 'N/A'),
            Column::make('Tax Types')
                ->label(fn ($row) => $row->taxInvestigationTaxTypeNames() ?? 'N/A'),
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
                    $url = route('tax_investigation.approvals.show', encrypt($value));
                    return <<<HTML
                           <button class="btn btn-info btn-sm" wire:click="approve($value)"><i class="bi bi-arrow-right"></i> Initiate Approval</button>
                           <button class="btn btn-danger btn-sm" wire:click="delete($value)"><i class="bi bi-trash-fill"></i> Delete </button>
                           <a href="{$url}" class="btn btn-outline-info btn-sm" data-toggle="tooltip" data-placement="right" title="View"> View More </a>
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
            $investigation = TaxInvestigation::find($data->id);
            if (is_null($investigation)) {
                abort(404);
            }
            $this->registerWorkflow(get_class($investigation), $investigation->id);
            $this->doTransition('start', []);
            $investigation->status = TaxVerificationStatus::PENDING;
            $investigation->save();
            $this->flash('success', 'Approval initiated successfully', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            report($e);
            $this->customAlert('warning', 'Something went wrong', ['onConfirmed' => 'confirmed', 'timer' => 2000]);
        }
    }

    public function delete($id)
    {
        $this->customAlert('warning', __('Are you sure you want to delete ?'), [
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
            $investigation = TaxInvestigation::find($data->id);
            if (is_null($investigation)) {
                abort(404);
            }

            WorkflowTask::query()
                ->where('pinstance_type', TaxInvestigation::class)
                ->where('pinstance_id', $investigation->id)
                ->delete();

            $investigation->delete();

            $this->flash('success', 'Record deleted successfully', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            report($e);
            $this->customAlert('warning', 'Something went wrong', ['onConfirmed' => 'confirmed', 'timer' => 2000]);
        }
    }
}
