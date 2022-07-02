<?php

namespace App\Http\Livewire\Business\Closure;

use id;
use Carbon\Carbon;
use App\Models\TemporaryBusinessClosure;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\DataTableComponent;

class ApprovedClosuresTable extends DataTableComponent
{


    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setAdditionalSelects(['is_extended', 'status', 'approved_by']);
    }

    public function builder(): Builder
    {
        return TemporaryBusinessClosure::query()->where('status', 'approved')->orderBy('temporary_business_closures.opening_date', 'DESC');
    }

    public function columns(): array
    {
        return [
            Column::make('Name', 'business.name')
                ->sortable()
                ->searchable(),
            Column::make('TIN', 'business.tin')
                ->sortable()
                ->searchable(),
            Column::make('Reg No.', 'business.reg_no')
                ->sortable()
                ->searchable(),
            Column::make('Closing Date', 'closing_date')
                ->format(function($value, $row) { return Carbon::create($row->closing_date)->toFormattedDateString(); })
                ->sortable()
                ->searchable(),
            Column::make('Opening Date', 'opening_date')
                ->format(function($value, $row) { return Carbon::create($row->opening_date)->toFormattedDateString(); })
                ->sortable()
                ->searchable(),
            Column::make('Approved By', 'approved_by')
                ->label(function($row) {
                        return '<span>'.$row->user->fname. ' ' .$row->user->lname.'</span>';
                })
                ->sortable()
                ->searchable()
                ->html(true),
            Column::make('Status', 'status')
                ->format(function ($value, $row) {
                        return <<< HTML
                        <span class="badge badge-success py-1 px-2">Approved & Confirmed</span>
                    HTML; 
                })
                ->html(true),
        ];
    }

    public function approve($id)
    {
        return redirect()->to('/business/closure/' . $id . '/approve');
    }
}
