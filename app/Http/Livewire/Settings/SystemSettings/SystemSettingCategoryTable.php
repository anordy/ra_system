<?php

namespace App\Http\Livewire\Settings\SystemSettings;

use App\Models\SystemSettingCategory;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Gate;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class SystemSettingCategoryTable extends DataTableComponent
{
    use LivewireAlert;

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
            Column::make('Action', 'id')->view('settings.system-settings.category.includes.actions'),
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
        ];
    }

    public function delete($id)
    {
        if (!Gate::allows('setting-system-category-delete')) {
            abort(403);
        }
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
                'id' => $id,
            ],
        ]);
    }

    public function confirmed($value)
    {
        try {
            $data = (object) $value['data'];
            TODO: //ADD DUAL CONTROL
            $systemsettingCategory = SystemSettingCategory::findOrFail(decrypt($data->id));
            if (!$systemsettingCategory->system_settings->exists()) {
                $systemsettingCategory->delete();
                $this->alert('success', 'Record deleted successfully');
                $this->flash(
                    'success',
                    'Record deleted successfully',
                    [],
                    redirect()
                        ->back()
                        ->getTargetUrl(),
                );
            } else {
                $this->alert('warning', 'There are system Setting data related to this model!', ['onConfirmed' => 'confirmed', 'timer' => 2000]);
            }
        } catch (Exception $e) {
            report($e);
            $this->alert('warning', 'Something whent wrong!', ['onConfirmed' => 'confirmed', 'timer' => 2000]);
        }
    }
}
