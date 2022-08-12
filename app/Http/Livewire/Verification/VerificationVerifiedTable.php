<?php

namespace App\Http\Livewire\Verification;

use App\Enum\TaxVerificationStatus;
use App\Models\Verification\TaxVerification;
use Illuminate\Database\Eloquent\Builder;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class VerificationVerifiedTable extends DataTableComponent
{

    use LivewireAlert;

    public $model = TaxVerification::class;

    public function builder(): Builder
    {
        return TaxVerification::query()->with('business', 'location', 'taxType', 'taxReturn')
            ->where('tax_verifications.status', TaxVerificationStatus::APPROVED);
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
            Column::make('Z_Number', 'business.zin'),
            Column::make('Business Name', 'business.name'),
            Column::make('Business Location', 'location.name'),
            Column::make('Tax Type', 'taxType.name'),
            Column::make('Action', 'id')
                ->view('verification.verified.action')
                ->html(true),

        ];
    }
}
