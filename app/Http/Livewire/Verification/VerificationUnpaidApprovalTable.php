<?php

namespace App\Http\Livewire\Verification;

use App\Enum\ReturnApplicationStatus;
use App\Enum\TaxVerificationStatus;
use App\Enum\VettingStatus;
use App\Models\Returns\ReturnStatus;
use App\Models\Verification\TaxVerification;
use App\Traits\ReturnFilterTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use App\Traits\CustomAlert;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class VerificationUnpaidApprovalTable extends DataTableComponent
{
    use CustomAlert, ReturnFilterTrait;
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

        return $filter->with('business', 'location', 'taxtype', 'taxReturn')
            ->whereHas('taxReturn', function (Builder $builder) {
                $builder->where('vetting_status', VettingStatus::VETTED);
            })
            ->where('tax_verifications.status', TaxVerificationStatus::PENDING)
            ->orderByDesc('tax_verifications.id');
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setAdditionalSelects(['created_by_type', 'tax_return_type', 'tax_type_id']);
        $this->setTableWrapperAttributes([
            'default' => true,
            'class'   => 'table-bordered table-sm',
        ]);
    }

    public function columns(): array
    {
        return [
            Column::make('ZRA No', 'location.zin'),
            Column::make('TIN', 'business.tin'),
            Column::make('Business Name', 'business.name'),
            Column::make('Business Location', 'location.name'),
            Column::make('Tax Type')
                ->label(fn ($row) => $row->taxtype->name ?? ''),
            Column::make('Control Number')
                ->label(function ($row) {
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
            Column::make('Action', 'id')
                ->view('verification.approval.unpaid-action')
                ->html(true),
        ];
    }
}
