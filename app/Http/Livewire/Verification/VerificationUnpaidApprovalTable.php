<?php

namespace App\Http\Livewire\Verification;

use App\Enum\ReturnApplicationStatus;
use App\Enum\TaxVerificationStatus;
use App\Models\Returns\ReturnStatus;
use App\Models\Verification\TaxVerification;
use App\Traits\ReturnFilterTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class VerificationUnpaidApprovalTable extends DataTableComponent
{
    use LivewireAlert,ReturnFilterTrait;
    protected $listeners = ['filterData' => 'filterData', '$refresh'];

    public $data = [];

    public $model = TaxVerification::class;

    public function filterData($data)
    {
        $this->data = $data;
        $this->emit('$refresh');
    }

    public function builder(): Builder
    {
        $returnTable = TaxVerification::getTableName();
        $filter      = (new TaxVerification)->newQuery();
        $filter      = $this->dataFilter($filter, $this->data, $returnTable);

        return $filter->with('business', 'location', 'taxType', 'taxReturn')
            ->whereHas('taxReturn', function (Builder $builder) {
                $builder->where('application_status', ReturnApplicationStatus::SUBMITTED)
                    ->whereNotIn('status', [ReturnStatus::COMPLETE, ReturnStatus::PAID_BY_DEBT]);
            })
            ->where('tax_verifications.status', TaxVerificationStatus::PENDING)
            ->orderByDesc('tax_verifications.id');
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setAdditionalSelects(['created_by_type', 'tax_return_type']);
        $this->setTableWrapperAttributes([
            'default' => true,
            'class'   => 'table-bordered table-sm',
        ]);
    }

    public function columns(): array
    {
        return [
            Column::make('ZRB No', 'location.zin'),
            Column::make('TIN', 'business.tin'),
            Column::make('Business Name', 'business.name'),
            Column::make('Business Location', 'location.name'),
            Column::make('Tax Type', 'taxType.name'), 
            Column::make('Control Number', 'tax_return_id')
                ->label(function($row){
                    return $row->taxReturn->tax_return->bill->control_number ?? '';
                }),
            Column::make('Filled By', 'created_by_id')
                ->format(function ($value, $row) {
                    $user = $row->createdBy()->first();

                    return $user->full_name ?? '';
                }),
            Column::make('Filled On', 'created_at')
                ->format(fn ($value) => Carbon::create($value)->toDayDateTimeString()),
            Column::make('Payment Status', 'tax_return_id')
                ->view('verification.payment_status'),
        ];
    }
}
