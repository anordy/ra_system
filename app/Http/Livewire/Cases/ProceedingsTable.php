<?php

namespace App\Http\Livewire\Cases;

use App\Models\CaseAppeal;
use App\Models\CaseProceeding;
use App\Traits\WithSearch;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use App\Traits\CustomAlert;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class ProceedingsTable extends DataTableComponent
{
	use CustomAlert, WithSearch;

    /**
     * @var mixed
     */
    public $case_id;

    public function builder(): Builder
	{
        return CaseProceeding::query()->where(['case_id'=>$this->case_id]);
    }

    public function mount($case_id)
    {
        $this->case_id = $case_id;
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');

	    $this->setTableWrapperAttributes([
	      'default' => true,
          'search'=>false,
	      'class' => 'table-bordered table-sm',
	    ]);
    }

    public function columns(): array
    {
        return [
            Column::make("date", "date")
                ->format(fn($date)=>Carbon::parse($date)->format('Y-m-d'))
                ->sortable(),
            Column::make("comment", "comment")
                ->sortable(),
            Column::make("Stage", "case_stage.name")
                ->sortable(),
            Column::make("Decision", "case_decision.name")
                ->sortable()
        ];
    }

}
