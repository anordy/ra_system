<?php

namespace App\Http\Livewire\Cases;

use App\Models\CaseAppeal;
use App\Models\LegalCase;
use App\Models\SysModule;
use App\Models\Taxpayer;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class ModulesTable extends DataTableComponent
{
	use LivewireAlert;

    public function builder(): Builder
	{
        return SysModule::query();
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
            Column::make("MODULE CODE", "MODULECODE")
                ->sortable(),
            Column::make("MODULE NAME", "MODULENAME")
                ->sortable(),
            Column::make("ROLE CODE", "ROLECODE")
                ->sortable(),
            Column::make("MOD GROUP", "MODGROUP")
                ->sortable(),
            Column::make("ACTION", "ACTION")
                ->sortable(),
            Column::make("ACTIVE", "ACTIVE")
                ->sortable()
        ];
    }



}
