<?php

namespace App\Http\Livewire\Audit;

use App\Enum\TaxAuditStatus;
use App\Models\TaxAudit\TaxAudit;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class TaxAuditVerifiedTable extends DataTableComponent
{

    use LivewireAlert;

    public $model = TaxAudit::class;

    public function builder(): Builder
    {
        return TaxAudit::query()->with('business', 'location', 'taxType', 'taxReturn')
            ->where('tax_audits.status', TaxAuditStatus::APPROVED);
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
            Column::make('Z_Number', 'location.zin'),
            Column::make('TIN', 'business.tin'),
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
                ->view('audit.verified.action')
                ->html(true),

        ];
    }
}
