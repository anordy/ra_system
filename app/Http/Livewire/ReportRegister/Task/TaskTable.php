<?php

namespace App\Http\Livewire\ReportRegister\Task;

use App\Enum\GeneralConstant;
use App\Enum\ReportRegister\RgRegisterType;
use App\Enum\ReportRegister\RgRequestorType;
use App\Models\ReportRegister\RgRegister;
use App\Traits\CustomAlert;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class TaskTable extends DataTableComponent
{
    use CustomAlert;

    protected string $tableName = 'task-table';

    public function builder(): Builder
    {
        return RgRegister::query()
            ->with(['assigned'])
            ->where('requester_type', RgRequestorType::STAFF)
            ->where('requester_id', Auth::id())
            ->orWhere('assigned_to_id', Auth::id())
            ->where('register_type', RgRegisterType::TASK)
            ->orderBy('created_at', 'Desc');
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setAdditionalSelects(['requester_type']);
        $this->setTableWrapperAttributes([
            'default' => true,
            'class' => 'table-bordered table-sm',
        ]);
    }


    public function columns(): array
    {
        return [
            Column::make('Title', 'title')
                ->format(function ($value) {
                    if (strlen($value) > 20){
                        return substr($value, 0, 20) . '...';
                    }
                    return $value;
                })
                ->searchable(),
            Column::make('Created By', 'requester_id')
                ->format(function ($value, $row) {
                    return $row->requester_name ?? 'N/A';
                }),
            Column::make('Assigned To', 'assigned_to_id')
                ->format(function ($value, $row) {
                    if (isset($row->assigned)) {
                        return $row->assigned->fullname;
                    } else {
                        return <<< HTML
                            <span class="badge badge-danger">Unassigned</span>
                        HTML;
                    }
                })->html(),
            Column::make('Status', 'status')
                ->format(function ($value) {
                    return view('report-register.task.includes.status', ['status' => $value]);
                }),
            Column::make('Scheduled', 'is_scheduled')
                ->format(function ($value) {
                    if ($value === GeneralConstant::ONE) {
                        return <<< HTML
                            <span class="badge badge-success">Yes</span>
                        HTML;
                    } else {
                        return <<< HTML
                            <span class="badge badge-info">No</span>
                        HTML;
                    }
                })->html(),
            Column::make('Task Date', 'start_date')
                ->format(function ($value) {
                    $startDate = $value->format('d M, Y');
                    if (now() > $startDate) {
                        return <<< HTML
                            <span class="badge badge-danger">Due on $startDate</span>
                        HTML;
                    } else {
                        return $value->format('d M, Y');
                    }
                })->html(),
            Column::make('Created On', 'created_at')
                ->format(function ($value) {
                    return $value->format('d M, Y');
                }),
            Column::make('Action', 'id')
                ->format(function ($value, $row) {
                    return view('report-register.task.includes.actions', ['value' => $value]);
                })
        ];
    }


}
