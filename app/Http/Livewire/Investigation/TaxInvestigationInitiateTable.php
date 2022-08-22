<?php

namespace App\Http\Livewire\Investigation;

use App\Enum\TaxInvestigationStatus;
use App\Enum\TaxVerificationStatus;
use App\Models\Investigation\TaxInvestigation;
use App\Traits\WorkflowProcesssingTrait;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class TaxInvestigationInitiateTable extends DataTableComponent
{

    use LivewireAlert, WorkflowProcesssingTrait;

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
        $this->setAdditionalSelects(['created_by_id']);
        $this->setTableWrapperAttributes([
            'default' => true,
            'class' => 'table-bordered table-sm',
        ]);
    }

    protected $listeners = [
        'initiateApproval',
        'confirmed',
    ];

    public function columns(): array
    {
        return [
            Column::make('ZRB No', 'location.zin'),
            Column::make('TIN', 'business.tin'),
            Column::make('Business Name', 'business.name'),
            Column::make('Business Location', 'location.name'),
            Column::make('TaxType', 'taxType.name'),
            Column::make('Period From', 'period_from'),
            Column::make('Period To', 'period_to'),
            Column::make('Created By', 'created_by_id')
                ->label(fn ($row) => $row->createdBy->full_name ?? ''),
            Column::make('Created On', 'created_at')
                ->label(fn ($row) => Carbon::create($row->created_at ?? null)->toDayDateTimeString()),
            Column::make('Action', 'id')
                ->format(function ($value) {
                    return <<<HTML
                           <button class="btn btn-info btn-sm" wire:click="initiateApproval($value)"><i class="fa fa-arrow-right"></i> Initiate Approval</button>
                    HTML;
                })
                ->html(true),

        ];
    }


    public function initiateApproval($id)
    {
        $this->alert('warning', 'Are you sure you want to initiate Approvals ?', [
            'position' => 'center',
            'toast' => false,
            'showConfirmButton' => true,
            'confirmButtonText' => 'Yes',
            'onConfirmed' => 'confirmed',
            'showCancelButton' => true,
            'cancelButtonText' => 'Cancel',
            'timer' => null,
            'data' => [
                'id' => $id,
            ],

        ]);
    }

    public function confirmed($value)
    {
        try {
            $data = (object) $value['data'];
            $investigation = TaxInvestigation::find($data->id);
            $this->registerWorkflow(get_class($investigation), $investigation->id);
            $this->doTransition('start', []);
            $investigation->status = TaxVerificationStatus::PENDING;
            $investigation->save();
            $this->flash('success', 'Approval initiated successfully', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            report($e);
            $this->alert('warning', 'Something whent wrong!!!', ['onConfirmed' => 'confirmed', 'timer' => 2000]);
        }
    }
}
