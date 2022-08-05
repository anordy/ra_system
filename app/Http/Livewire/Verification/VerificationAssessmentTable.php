<?php

namespace App\Http\Livewire\Verification;

use App\Models\Verification\TaxVerification;
use Illuminate\Database\Eloquent\Builder;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class VerificationAssessmentTable extends DataTableComponent
{

    use LivewireAlert;

    public $model = TaxVerification::class;

    public function builder(): Builder
    {
        return TaxVerification::query()->with('business', 'location', 'taxType', 'taxReturn');
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
                ->view('verification.action')
                ->html(true),

        ];
    }
}
