<?php

namespace App\Http\Livewire\Assesments\Objection;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Objection;
use App\Models\ObjectionStatus;
use Illuminate\Database\Eloquent\Builder;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class ObjectionApprovalTable extends DataTableComponent
{
    use LivewireAlert;
    // protected $model = Objection::class;

    public function builder(): Builder
    {
        return Objection::query()
            ->where('objections.status', ObjectionStatus::PENDING)
            ->orderBy('objections.created_at', 'desc');
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
            Column::make("Id", "id")
                ->sortable(),
            Column::make("Business Name", "business.name")
                ->sortable()
                ->searchable(),
            Column::make("Owner", "business.owner_designation")
                ->sortable()
                ->searchable(),
            Column::make("Mobile", "business.mobile")
                ->sortable(),
            Column::make("Tax In Dispute(Tzs)", "tax_in_dispute")
                ->sortable(),
            Column::make("Tax Not in Dispute", "tax_not_in_dispute")
                ->sortable(),
            Column::make('Previous Transition', 'id')
                ->format(function ($value, $row) {
                    $transtion = str_replace('_', ' ', $row->pinstancesActive->name ?? '');
                    return <<<HTML
                       <span class="badge badge-info py-1 px-2"  style="border-radius: 1rem; font-size: 85%">
                        <i class="bi bi-clock mr-1"></i>
                        {$transtion}
                    </span>
                    HTML;
                })->html(true),
            Column::make('Status', 'status')
                ->view('assesments.objection.includes.status'),
            Column::make('Action', 'id')
                ->view('assesments.objection.includes.action'),
        ];
    }
}
