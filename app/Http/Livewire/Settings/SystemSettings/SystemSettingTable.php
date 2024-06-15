<?php

namespace App\Http\Livewire\Settings\SystemSettings;

use App\Models\DualControl;
use App\Models\SystemSetting;
use App\Traits\DualControlActivityTrait;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Gate;
use App\Traits\CustomAlert;
use Livewire\Component;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class SystemSettingTable extends DataTableComponent
{
    use CustomAlert, DualControlActivityTrait;

    public function builder(): Builder
    {
        return SystemSetting::query()
            ->select('value')
            ->orderBy('created_at', 'Desc');
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setTableWrapperAttributes([
            'default' => true,
            'class' => 'table-bordered table-sm',
        ]);

        $this->setTdAttributes(function (Column $column) {
            if ($column->isField('id')) {
                return [
                    'style' => 'width: 20%;',
                ];
            }

            return [];
        });
    }

    protected $listeners = ['confirmed'];

    public function columns(): array
    {
        return [
            Column::make('Name', 'name')
                ->sortable()
                ->searchable(),
            Column::make('Unit', 'unit')
                ->format(function ($value, $row) {
                    return <<<HTML
                            $row->value  (<span class="font-italic text-muted pr-1"> $row->unit</span>)
                        HTML;
                })
                ->html(),
            Column::make('Description', 'description')
                ->sortable()
                ->searchable(),
            Column::make('Approval Status', 'is_approved')
                ->format(function ($value, $row) {
                    if ($value == 0) {
                        return <<<HTML
                            <span class="badge badge-warning p-2 rounded-0" >Not Approved</span>
                        HTML;
                    } elseif ($value == 1) {
                        return <<<HTML
                            <span class="badge badge-success p-2 rounded-0" >Approved</span>
                        HTML;
                    } elseif ($value == 2) {
                        return <<<HTML
                            <span class="badge badge-danger p-2 rounded-0" >Rejected</span>
                        HTML;
                    }
                })
                ->html(),
            Column::make('Edit Status', 'is_updated')
                ->format(function ($value, $row) {
                    if ($value == 0) {
                        return <<<HTML
                            <span class="badge badge-warning p-2 rounded-0" >Not Updated</span>
                        HTML;
                    } elseif ($value == 1) {
                        return <<<HTML
                            <span class="badge badge-success p-2 rounded-0" >Updated</span>
                        HTML;
                    }
                })
                ->html(),
            Column::make('Action', 'id')
            ->format(function ($value, $row) {
                return view('settings.system-settings.setting-entries.includes.actions', ['row' => $row]);
            })
        ];
    }

    public function delete($id)
    {
        if (!Gate::allows('system-setting-delete')) {
            abort(403);
        }
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
                'id' => $id,
            ],
        ]);
    }

    public function confirmed($value)
    {
        try {
            $data = (object) $value['data'];

            $systemsetting = SystemSetting::findOrFail(decrypt($data->id));
            $this->triggerDualControl(get_class($systemsetting), $systemsetting->id, DualControl::DELETE, 'deleting system setting entry');
            $this->customAlert('success', DualControl::SUCCESS_MESSAGE, ['timer' => 8000]);
            $this->flash(
                'success',
                DualControl::SUCCESS_MESSAGE,
                [],
                redirect()
                    ->back()
                    ->getTargetUrl(),
            );
        } catch (Exception $e) {
            report($e);
            $this->customAlert('warning', DualControl::ERROR_MESSAGE, ['onConfirmed' => 'confirmed', 'timer' => 2000]);
        }
    }
}
