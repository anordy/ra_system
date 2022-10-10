<?php

namespace App\Http\Livewire\Verification;

use App\Enum\TaxVerificationStatus;
use App\Models\Verification\TaxVerification;
use App\Traits\ReturnFilterTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class VerificationVerifiedTable extends DataTableComponent
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
            ->where('tax_verifications.status', TaxVerificationStatus::APPROVED)
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
            Column::make('Payment Status', 'location.name'),
            Column::make('Tax Type', 'taxType.name'),
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
                ->view('verification.verified.action')
                ->html(true),
        ];
    }
}
