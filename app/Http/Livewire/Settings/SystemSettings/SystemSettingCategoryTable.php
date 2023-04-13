<?php

namespace App\Http\Livewire\Settings\SystemSettings;

use App\Models\DualControl;
use App\Models\SystemSettingCategory;
use App\Traits\DualControlActivityTrait;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Gate;
use App\Traits\CustomAlert;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class SystemSettingCategoryTable extends DataTableComponent
{
    use CustomAlert, DualControlActivityTrait;

    public function builder(): Builder
    {
        return SystemSettingCategory::query()->orderBy('created_at', 'Desc');
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
            Column::make('Description', 'description')
                ->sortable()
                ->searchable(),
            Column::make('Approval Status', 'is_approved')
                ->format(function ($value, $row) {
                    if ($value == 0) {
                        return <<<HTML
                            <span style="border-radius: 0 !important;" class="badge badge-warning p-2" >Not Approved</span>
                        HTML;
                    } elseif ($value == 1) {
                        return <<<HTML
                            <span style="border-radius: 0 !important;" class="badge badge-success p-2" >Approved</span>
                        HTML;
                    } elseif ($value == 2) {
                        return <<<HTML
                            <span style="border-radius: 0 !important;" class="badge badge-danger p-2" >Rejected</span>
                        HTML;
                    }
                })
                ->html(),
            Column::make('Edit Status', 'is_updated')
                ->format(function ($value, $row) {
                    if ($value == 0) {
                        return <<<HTML
                            <span style="border-radius: 0 !important;" class="badge badge-warning p-2" >Not Updated</span>
                        HTML;
                    } elseif ($value == 1) {
                        return <<<HTML
                            <span style="border-radius: 0 !important;" class="badge badge-success p-2" >Updated</span>
                        HTML;
                    }
                })
                ->html(),
            Column::make('Action', 'id')->format(function ($value, $row) {
                return view('settings.system-settings.category.includes.actions', ['row' => $row]);
            }),
        ];
    }

    public function delete($id)
    {
        if (!Gate::allows('setting-system-category-delete')) {
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

            $systemsettingCategory = SystemSettingCategory::findOrFail(decrypt($data->id));
            if (!$systemsettingCategory->system_settings->exists()) {
                $this->triggerDualControl(get_class($systemsettingCategory), $systemsettingCategory->id, DualControl::DELETE, 'deleting system settings category');
                $this->customAlert('success', DualControl::SUCCESS_MESSAGE, ['timer' => 8000]);
                $this->flash(
                    'success',
                    DualControl::SUCCESS_MESSAGE,
                    [],
                    redirect()
                        ->back()
                        ->getTargetUrl(),
                );
            } else {
                $this->customAlert('warning', DualControl::RELATION_MESSAGE, ['onConfirmed' => 'confirmed', 'timer' => 2000]);
            }
        } catch (Exception $e) {
            report($e);
            $this->customAlert('warning', DualControl::ERROR_MESSAGE, ['onConfirmed' => 'confirmed', 'timer' => 2000]);
        }
    }
}
