<?php

namespace App\Http\Livewire\InternalInfoChange;

use App\Enum\InternalInfoChangeStatus;
use App\Models\InternalBusinessUpdate;
use App\Models\WorkflowTask;
use Illuminate\Database\Eloquent\Builder;
use App\Traits\CustomAlert;
use Illuminate\Support\Facades\DB;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class InternalInfoChangeTable extends DataTableComponent
{

    use CustomAlert;

    public $model = WorkflowTask::class;

    public function builder(): Builder
    {
        return InternalBusinessUpdate::with('staff', 'business', 'location')
            ->where('status', InternalInfoChangeStatus::APPROVED)
            ->orderBy('created_at', 'desc');
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
            Column::make("Business Name", "business_id")
                ->label(fn ($row) => $row->business->name ?? '')
                ->searchable(function (Builder $query, $searchTerm) {
                    return $query->orWhereHas('business', function ($query) use ($searchTerm) {
                        $query->whereRaw(DB::raw("LOWER(name) like '%' || LOWER('$searchTerm') || '%'"));
                    });
                }),
            Column::make("Branch", "location_id")
                ->label(fn ($row) => $row->location->name ?? ''),
            Column::make("Information Type", "type")
                ->label(fn ($row) => ucfirst(str_replace('_', ' ', $row->type))  ?? 'N/A'),
            Column::make("Triggered On", "created_at")
                ->label(fn ($row) => $row->created_at ?? 'N/A'),
            Column::make('Status', 'status')
                ->label(function ($row) {
                    $status = $row->status;
                    return view('internal-info-change.includes.status', compact('status'));
            }),
            Column::make('Actions', 'id')
                ->label(function ($row) {
                    $id = $row->id;
                    return view('internal-info-change.includes.action', compact('row', 'id'));
            }),
        ];
    }
}
