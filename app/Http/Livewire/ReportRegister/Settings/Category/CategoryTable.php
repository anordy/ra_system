<?php

namespace App\Http\Livewire\ReportRegister\Settings\Category;

use App\Enum\CustomMessage;
use App\Models\ReportRegister\RgCategory;
use App\Traits\CustomAlert;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class CategoryTable extends DataTableComponent
{
    use CustomAlert;

    protected $listeners = [
        'confirmed'
    ];

    public function builder(): Builder
    {
        return RgCategory::query()
            ->orderBy('name', 'Asc');
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setTableWrapperAttributes([
            'default' => true,
            'class' => 'table-bordered table-sm',
        ]);
    }


    public function columns(): array
    {
        return [
            Column::make('Name', 'name')
                ->searchable(),
            Column::make('Description', 'description')
                ->searchable(),
            Column::make('Action', 'id')
                ->format(function ($value) {
                    return view('report-register.settings.includes.category-actions', ['value' => $value]);
                })
        ];
    }

    public function confirmPopUpModal($categoryId)
    {
        $this->customAlert('warning', CustomMessage::ARE_YOU_SURE, [
            'position' => 'center',
            'toast' => false,
            'showConfirmButton' => true,
            'confirmButtonText' => 'Confirm',
            'onConfirmed' => 'confirmed',
            'showCancelButton' => true,
            'cancelButtonText' => 'Cancel',
            'timer' => null,
            'data' => [
                'id' => $categoryId
            ],
        ]);
    }


    public function confirmed() {
        dd('erer');
    }


}
