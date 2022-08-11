<?php

namespace App\Http\Livewire\Investigation;

use App\Enum\TaxInvestigationStatus;
use App\Models\Investigation\TaxInvestigation;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class TaxInvestigationApprovalTable extends DataTableComponent
{

    use LivewireAlert;

    public $model = TaxInvestigation::class;

    public function builder(): Builder
    {
        return TaxInvestigation::query()->with('business', 'location', 'taxType', 'taxReturn')
            ->where('tax_investigations.status', TaxInvestigationStatus::PENDING);
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setAdditionalSelects(['created_by_type']);
        $this->setTableWrapperAttributes([
            'default' => true,
            'class' => 'table-bordered table-sm',
        ]);
    }

    public function columns(): array
    {
        return [
            Column::make('Z_Number', 'business.z_no'),
            Column::make('Business Name', 'business.name'),
            Column::make('Business Location', 'location.name'),
            Column::make('Tax Type', 'taxType.name'),
            Column::make('Period From', 'period_from'),
            Column::make('Period To', 'period_to'),
            Column::make('Filled By', 'created_by_id')
                ->format(function ($value, $row) {
                    $user = $row->createdBy()->first();
                    return $user->full_name ?? '';
                }),
            Column::make('Filled On', 'created_at')
                ->format(fn ($value) => Carbon::create($value)->toDayDateTimeString()),
            Column::make('Action', 'id')
                ->view('investigation.approval.action')
                ->html(true),

        ];
    }
}
