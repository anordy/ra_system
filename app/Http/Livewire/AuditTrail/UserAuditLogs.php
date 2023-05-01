<?php

namespace App\Http\Livewire\AuditTrail;

use Carbon\Carbon;
use App\Models\Audit;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Gate;
use App\Traits\CustomAlert;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\DataTableComponent;

class UserAuditLogs extends DataTableComponent
{
    use CustomAlert;

    public $userId;

    public function mount($userId) {
        $this->userId = decrypt($userId);
    }

    public function builder(): Builder {
        return Audit::query()->with('user')->where('user_id', $this->userId)->orderBy('created_at', 'DESC');
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setAdditionalSelects(['auditable_type', 'tags', 'new_values', 'auditable_id', 'user_type']);
    
        $this->setTableWrapperAttributes([
            'default' => true,
            'class' => 'table-bordered table-sm',
        ]);
    }

    public function columns(): array
    {
        return [
            Column::make('Log', 'user_id')
            ->sortable()
            ->searchable()
            ->format(function($value, $row){
                $name = $row->user->full_name ?? '';
                $model_name = explode('\\',$row->auditable_type);
                return $name .' - '. end($model_name);
            }),
            Column::make('Action', 'event')
                ->sortable()
                ->searchable(),
            Column::make('IP Address', 'ip_address')
                ->sortable()
                ->searchable(),
            Column::make('Time', 'created_at')
                ->sortable()
                ->searchable()
                ->format(fn($value, $row) => Carbon::create($row->created_at)->diffForHumans()),
            Column::make('Action', 'id')
                ->format(function ($value){ 
                    if(Gate::allows('system-audit-trail-view')) {
                        $value = "'".encrypt($value)."'";
                        return <<< HTML
                            <button class="btn btn-info btn-sm" onclick="Livewire.emit('showModal', 'audit-view-modal',$value)"><i class="fa fa-eye"></i> </button>
                        HTML;
                    }
                })
                ->html(true)
        ];
    }


}
