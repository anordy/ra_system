<?php

namespace App\Http\Livewire\Audit;

use App\Enum\TaxAuditStatus;
use App\Models\TaxAudit\TaxAudit;
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
            Column::make('Action', 'id')
                ->view('audit.verified.action')
                ->html(true),

        ];
    }
}
