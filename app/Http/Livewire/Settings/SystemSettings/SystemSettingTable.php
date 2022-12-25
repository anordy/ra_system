<?php

namespace App\Http\Livewire\Settings\SystemSettings;

use App\Models\SystemSetting;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Gate;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class SystemSettingTable extends DataTableComponent
{
    use LivewireAlert;

    public function builder(): Builder
    {
        return SystemSetting::query()->select('value')->orderBy('created_at', 'Desc');
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
            Column::make('Unit', 'unit')->format(
                function($value, $row){
                    return $row->value .' '. $row->unit;
                })
                ->sortable()
                ->searchable(),
            Column::make('Description', 'description')
                ->sortable()
                ->searchable(),
            Column::make('Action', 'id')->view('settings.system-settings.setting-entries.includes.actions'),
        ];
    }

    public function delete($id)
    {
        if (!Gate::allows('system-setting-delete')) {
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
                'id' => $id
            ],

        ]);
    }

    public function confirmed($value)
    {
        try {
            $data = (object) $value['data'];
            $systemsettingCategory = SystemSetting::findOrFail(decrypt($data->id));
            $systemsettingCategory->delete();
            $this->alert('success', 'Record deleted successfully');
            $this->flash('success', 'Record deleted successfully', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            report($e);
            $this->alert('warning', 'Something whent wrong!', ['onConfirmed' => 'confirmed', 'timer' => 2000]);
        }
    }
}
