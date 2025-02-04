<?php

namespace App\Http\Livewire\Mvr\Blacklist;

use App\Enum\Mvr\MvrBlacklistType;
use App\Models\MvrBlacklist;
use App\Traits\CustomAlert;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Columns\LinkColumn;

class BlackListTable extends DataTableComponent
{
	use CustomAlert;

    public $initiatorType;

    public function mount($initiatorType){
        $this->initiatorType = $initiatorType;
    }

    public function builder(): Builder
	{
        if ($this->initiatorType) {
            return MvrBlacklist::query()->where('initiator_type', $this->initiatorType)->orderBy('id', 'desc');
        } else {
            return MvrBlacklist::query();
        }
    }


	public function configure(): void
    {
        $this->setPrimaryKey('id');

	    $this->setTableWrapperAttributes([
	      'default' => true,
	      'class' => 'table-bordered table-sm',
	    ]);

        $this->setAdditionalSelects(['blacklist_type', 'blacklist_id', 'id', 'is_blocking']);

    }

    public function columns(): array
    {
        return [
            Column::make("Type", "type")
                ->format(fn($value, $row)=> formatEnum($value))
                ->searchable(),
            Column::make("Number", "updated_at")
                ->format(function ($value, $row) {
                    $state = !$row->is_blocking ? 'UNBLOCKING' : 'BLOCKING';
                    if ($row->type === MvrBlacklistType::DL) {
                        return "{$row->blacklist->license_number} {$state}" ?? 'N/A';
                    } else if ($row->type === MvrBlacklistType::MVR) {
                        return "{$row->blacklist->plate_number} {$state}" ?? 'N/A';
                    } else {
                        return 'N/A';
                    }
                })
                ->searchable(),
            Column::make("Initiator", "initiator_type")
                ->format(fn($value, $row)=> formatEnum($value))
                ->searchable(),
            Column::make("Initiator Name", "created_by")
                ->format(fn($value, $row)=> $row->user->fullname())
                ->searchable(),
            Column::make("Initiated Date", "created_at"),
            Column::make("Status", "status"),
            LinkColumn::make('Action', 'id')
                ->title(fn($row) => 'View')
                ->location(fn($row) => route('mvr.blacklist.show', encrypt($row->id)))
                ->attributes(fn($row) => [
                    'class' => 'btn btn-sm btn-primary',
                ]),
        ];
    }






}
