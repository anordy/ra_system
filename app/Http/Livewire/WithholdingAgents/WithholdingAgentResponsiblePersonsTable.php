<?php

namespace App\Http\Livewire\WithholdingAgents;

use App\Enum\CustomMessage;
use Exception;
use Carbon\Carbon;
use App\Models\WaResponsiblePerson;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Gate;
use Illuminate\Database\Eloquent\Builder;
use App\Traits\CustomAlert;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\DataTableComponent;

class WithholdingAgentResponsiblePersonsTable extends DataTableComponent
{
    use CustomAlert;
    public $withholding_agent_id;

    public function mount($id)
    {
        $this->withholding_agent_id = decrypt($id);
    }


    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setAdditionalSelects(['status', 'responsible_person_id', 'officer_id', 'business_id', 'title', 'position', 'created_at']);
        $this->setTableWrapperAttributes([
            'default' => true,
            'class' => 'table-bordered table-sm',
        ]);
    }

    public function builder(): Builder
    {
        return WaResponsiblePerson::query()->where('withholding_agent_id', $this->withholding_agent_id)->orderBy('wa_responsible_persons.created_at', 'DESC');
    }

    protected $listeners = [
        'confirmed',
    ];

    public function columns(): array
    {
        return [
            Column::make('Responsible Person', 'responsible_person_id')
                ->label(function ($row) {
                    return "{$row->taxpayer->first_name} {$row->taxpayer->last_name}";
                })
                ->sortable()
                ->searchable(),
            Column::make('Title', 'title')
                ->label(function ($row) {
                    return "{$row->title}";
                })
                ->sortable()
                ->searchable(),
            Column::make('Position', 'position')
                ->label(function ($row) {
                    return "{$row->position}";
                })
                ->sortable()
                ->searchable(),
            Column::make('Approved By', 'officer_id')
                ->label(function ($row) {
                    return "{$row->user->fname} {$row->user->lname}";
                })
                ->html(true)
                ->sortable()
                ->searchable(),
            Column::make('Date', 'created_at')
                ->label(function ($row) {
                    return Carbon::create($row->created_at)->format('d-m-Y');
                })
                ->sortable()
                ->searchable(),
            Column::make('Status', 'updated_at')
                ->format(function ($value, $row) {
                    if ($row->status == 'active') {
                        return <<< HTML
                                    <span class="badge badge-success">Active</span>
                            HTML;
                    } else if ($row->status == 'inactive') {
                        return <<< HTML
                            <span class="badge badge-danger">Inactive</span>
                            HTML;
                    }
                })
                ->html(true),
            Column::make('Action', 'id')
                ->view('withholding-agent.responsible-person.actions')
        ];
    }

    public function changeStatus($id)
    {
        if (!Gate::allows('withholding-agents-registration')) {
            abort(403);
        }

        try {
            $responsible_person = WaResponsiblePerson::select('status')->findOrFail(decrypt($id));
            $status = $responsible_person->status == WaResponsiblePerson::ACTIVE ? 'Deactivate' : 'Activate';
            $this->customAlert('warning', "Are you sure you want to {$status} ?", [
                'position' => 'center',
                'toast' => false,
                'showConfirmButton' => true,
                'confirmButtonText' => $status,
                'onConfirmed' => 'confirmed',
                'showCancelButton' => true,
                'cancelButtonText' => 'Cancel',
                'timer' => null,
                'data' => [
                    'id' => $id
                ],
            ]);
        } catch (Exception $exception) {
            Log::error('Error: ' . $exception->getMessage(), [
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'trace' => $exception->getTraceAsString(),
            ]);
            $this->customAlert('warning', CustomMessage::ERROR);
        }

    }


    public function confirmed($value)
    {
        if (!Gate::allows('withholding-agents-registration')) {
            abort(403);
        }
        try {
            $data = (object) $value['data'];
            $responsible_person = WaResponsiblePerson::select('id', 'status')->findOrFail(decrypt($data->id));
            if ($responsible_person->status == 'active') {
                $responsible_person->status = WaResponsiblePerson::INACTIVE;
            } else if ($responsible_person->status == 'inactive') {
                $responsible_person->status = WaResponsiblePerson::ACTIVE;
            }
            $responsible_person->save();
            $this->flash('success', 'Status updated successfully', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            Log::error('Error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
            $this->customAlert('warning', 'Something whent wrong', ['onConfirmed' => 'confirmed', 'timer' => 2000]);
        }
    }
}
