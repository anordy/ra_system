<?php

namespace App\Http\Livewire\Kyc;

use App\Models\Country;
use App\Models\KycAmendmentRequest;
use App\Models\Region;
use App\Models\Street;
use App\Models\TaxpayerAmendmentRequest;
use Illuminate\Database\Eloquent\Builder;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class KycAmendmentRequestTable extends DataTableComponent
{
    use LivewireAlert;


    protected $listeners = [
        'confirmed',
    ];

    public $status;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setTableWrapperAttributes([
            'default' => true,
            'class' => 'table-bordered table-sm',
        ]);
        $this->setAdditionalSelects(['kyc_amendment_requests.status']);

    }

    public function mount($status)
    {
        $this->status = $status;
    }

    public function builder(): Builder
    {
        if ($this->status == KycAmendmentRequest::PENDING) {
            return KycAmendmentRequest::where('kyc_amendment_requests.status', KycAmendmentRequest::PENDING)->orderBy('kyc_amendment_requests.created_at', 'DESC')
                ->with('kyc');
        } else if ($this->status == KycAmendmentRequest::APPROVED) {
            return KycAmendmentRequest::where('kyc_amendment_requests.status', KycAmendmentRequest::APPROVED)->orderBy('kyc_amendment_requests.created_at', 'DESC')
                ->with('kyc');
        } else if ($this->status == KycAmendmentRequest::REJECTED) {
            return KycAmendmentRequest::where('kyc_amendment_requests.status', KycAmendmentRequest::REJECTED)->orderBy('kyc_amendment_requests.created_at', 'DESC')
                ->with('kyc');
        }else if ($this->status == KycAmendmentRequest::TEMPERED) {
            return KycAmendmentRequest::where('kyc_amendment_requests.status', KycAmendmentRequest::TEMPERED)->orderBy('kyc_amendment_requests.created_at', 'DESC')
                ->with('kyc');
        }
        return KycAmendmentRequest::query();
    }

    public function columns(): array
    {
        return [
            Column::make('Name', 'kyc_id')
                ->format(
                    function($value, $row){
                        return $row->kyc->fullname();
                    }
                )
                ->sortable()
                ->searchable(),
            Column::make('Nationality', 'kyc.country_id')
                ->format(function ($value, $row) {
                    return Country::find($value)->nationality ?? '';
                }),
            Column::make('Region', 'kyc.region_id')
                ->format(function ($value, $row) {
                    return Region::find($value)->value('name') ?? '';
                }),
            Column::make('Street', 'kyc.street_id')
                ->format(function ($value, $row) {
                    return Street::find($value)->value('name') ?? '';
                }),
            Column::make('Action', 'id')
                ->view('kyc.amendments.includes.actions'),
        ];
    }
}
