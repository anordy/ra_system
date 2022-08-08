<?php

namespace App\Http\Livewire\Verification;

use App\Enum\TaxVerificationStatus;
use App\Models\Verification\TaxVerification;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class VerificationApprovalTable extends DataTableComponent
{

    use LivewireAlert;

    public $model = TaxVerification::class;

    public function builder(): Builder
    {
        return TaxVerification::query()->with('business', 'location', 'taxType', 'taxReturn')
            ->where('tax_verifications.status', TaxVerificationStatus::PENDING);
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
            Column::make('ZRB No', 'business.z_no'),
            Column::make('TIN', 'business.tin'),
            Column::make('Business Name', 'business.name'),
            Column::make('Business Location', 'location.name'),
            Column::make('Tax Type', 'taxType.name'),
            Column::make('Filled By', 'created_by_id')
                ->format(function($value, $row){
                    $user = $row->createdBy()->first();
                    return $user->full_name ?? '';
                }),
            Column::make('Filled On', 'created_at')
                ->format(fn($value) => Carbon::create($value)->toDayDateTimeString()),
            Column::make('Action', 'id')
                ->view('verification.approval.action')
                ->html(true),

        ];
    }
}
