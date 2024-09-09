<?php

namespace App\Http\Livewire\ReportRegister\Incident;

use App\Enum\ReportRegister\RgRegisterType;
use App\Enum\ReportRegister\RgRequestorType;
use App\Models\ReportRegister\RgRegister;
use App\Traits\CustomAlert;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;

class IncidentTable extends DataTableComponent
{
    use CustomAlert;

    protected string $tableName = 'incident-table';

    public function builder(): Builder
    {
        return RgRegister::query()
            ->with(['assigned'])
            ->where('requester_type', RgRequestorType::TAXPAYER)
            ->where('register_type', RgRegisterType::INCIDENT)
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
                    if (strlen($value) > 10) {
                        return substr($value, 0, 10) . '...';
                    }
                    return $value;
                })
                ->searchable(),
            Column::make('Requested By', 'requester_id')
                ->format(function ($value, $row) {
                    return $row->requester_name;
                }),
            Column::make('Category', 'rg_category_id')
                ->format(function ($value, $row) {
                    $category = $row->category->name ?? 'N/A';
                    return <<< HTML
                            <span class="badge badge-info">$category</span>
                        HTML;
                })->html(),
            Column::make('Sub Category', 'rg_sub_category_id')
                ->format(function ($value, $row) {
                    $category = $row->subcategory->name ?? 'N/A';
                    return <<< HTML
                            <span class="badge badge-info">$category</span>
                        HTML;
                })->html(),
            Column::make('Assigned To', 'assigned_to_id')
                ->format(function ($value, $row) {
                    if (isset($row->assigned->fullname)) {
                        return $row->assigned->fullname;
                    } else {
                        return <<< HTML
                            <span class="badge badge-danger">Unassigned</span>
                        HTML;
                    }
                })->html(),
            Column::make('Status', 'status')
                ->format(function ($value) {
                    return view('report-register.incident.includes.status', ['status' => $value]);
                }),
            Column::make('Breached', 'is_breached')
                ->format(function ($value) {
                    if ($value) {
                        return <<< HTML
                            <span class="badge badge-danger">Breached</span>
                        HTML;
                    } else {
                        return <<< HTML
                            <span class="badge badge-success">Not Breached</span>
                        HTML;
                    }
                })->html(),
            Column::make('Reported On', 'created_at')
                ->format(function ($value) {
                    return $value->format('d M, Y H:i');
                }),
            Column::make('Action', 'id')
                ->format(function ($value, $row) {
                    return view('report-register.incident.includes.actions', ['value' => $value]);
                })
        ];
    }



}
