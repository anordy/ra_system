<?php

namespace App\Http\Livewire\ReportRegister\Settings\SubCategory;

use App\Models\ReportRegister\RgSubCategory;
use App\Traits\CustomAlert;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class SubCategoryTable extends DataTableComponent
{
    use CustomAlert;

    public $categoryId;

    public function mount($categoryId) {
        $this->categoryId = decrypt($categoryId);
    }

    public function builder(): Builder
    {
        return RgSubCategory::query()
            ->with(['notifiables'])
            ->where('rg_category_id', $this->categoryId)
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
            Column::make('Notifiables', 'rg_category_id')
                ->format(function ($value, $row) {
                    $roles = '';
                    $notifiables = $row->notifiables ?? [];
                    foreach ($notifiables as $i => $notifiable) {
                        $roles .= $notifiable->role->name ?? 'N/A';

                        if ($i < count($notifiables) - 1) {
                            $roles .= ', ';
                        }
                    }
                    return $roles;
                }),
            Column::make('Action', 'id')
                ->format(function ($value) {
                    return view('report-register.settings.includes.sub-category-actions', ['value' => $value]);
                })
        ];
    }


}
