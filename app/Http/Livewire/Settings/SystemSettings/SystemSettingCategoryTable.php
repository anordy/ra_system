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
        return  SystemSettingCategory::query()
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

    protected $listeners = [
        'confirmed'
    ];

    public function columns(): array
    {
        return [
            Column::make('Name', 'name')
                ->sortable()
                ->searchable(),
            Column::make('Description', 'description')
                ->sortable()
                ->searchable(),
            Column::make('Action', 'id')
                ->view('settings.system-settings.category.includes.actions'),
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
                'id' => $id
            ],

        ]);
    }

    public function confirmed($value)
    {
        try {
            $data = (object) $value['data'];
            $systemsettingCategory = SystemSettingCategory::findOrFail(decrypt($data->id));
            if (!$systemsettingCategory->system_settings->exists()) {
                $systemsettingCategory->delete();
                $this->alert('success', 'Record deleted successfully');
                $this->flash('success', 'Record deleted successfully', [], redirect()->back()->getTargetUrl());
            } else {
                $this->alert('warning', 'There are system Setting data related to this model!', ['onConfirmed' => 'confirmed', 'timer' => 2000]);    
            }
        } catch (Exception $e) {
            report($e);
            $this->alert('warning', 'Something whent wrong!', ['onConfirmed' => 'confirmed', 'timer' => 2000]);
        }
    }
    
}
