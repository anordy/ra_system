<?php

namespace App\Http\Livewire\Cases;

use App\Models\CaseAppeal;
use App\Models\LegalCase;
use App\Models\Taxpayer;
use App\Traits\WithSearch;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use App\Traits\CustomAlert;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class AppealsTable extends DataTableComponent
{
	use CustomAlert;

    public function builder(): Builder
	{
        return CaseAppeal::query();
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
            Column::make("Case #", "case.case_number")
                ->sortable(),
            Column::make("Date Opened", "date_opened")
                ->sortable(),
            Column::make("Business Name", "case.tax_investigation.business.name")
                ->sortable(),
            Column::make("Date Closed", "date_closed")
                ->sortable(),
            Column::make("Appeal Outcome", "case_outcome.name")
                ->sortable(),
            Column::make('Action', 'id')
                ->format(function ($value) {
                    $url = route('cases.appeal.show',encrypt($value));
                    return <<< HTML
                    <a class="btn btn-outline-primary btn-sm" href="$url"><i class="fa fa-eye"></i>View</a>
                HTML;
                })
                ->html()
        ];
    }



}
