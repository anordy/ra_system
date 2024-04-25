<?php

namespace App\Http\Livewire\Taxpayers;

use App\Models\Country;
use App\Models\Region;
use App\Models\Street;
use App\Models\TaxpayerAmendmentRequest;
use Illuminate\Database\Eloquent\Builder;
use App\Traits\CustomAlert;
use Livewire\Component;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class DetailsAmendmentRequestTable extends DataTableComponent
{
    use CustomAlert;


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
    }

    public function mount($status)
    {
        $this->status = $status;
    }

    public function builder(): Builder
    {
        $query = TaxpayerAmendmentRequest::query();
        if ($this->status == TaxpayerAmendmentRequest::PENDING) {
            $query->where('taxpayer_amendment_requests.status', TaxpayerAmendmentRequest::PENDING);
        } else if ($this->status == TaxpayerAmendmentRequest::APPROVED) {
            $query->where('taxpayer_amendment_requests.status', TaxpayerAmendmentRequest::APPROVED);
        } else if ($this->status == TaxpayerAmendmentRequest::REJECTED) {
            $query->where('taxpayer_amendment_requests.status', TaxpayerAmendmentRequest::REJECTED);
        }else if ($this->status == TaxpayerAmendmentRequest::TEMPERED) {
            $query->where('taxpayer_amendment_requests.status', TaxpayerAmendmentRequest::TEMPERED);
        }
        return $query
            ->orderBy('taxpayer_amendment_requests.created_at', 'DESC')
            ->with('taxpayer');
    }

    public function columns(): array
    {
        return [
            Column::make('Name', 'taxpayer_id')
                ->format(
                    function($value, $row){
                        return $row->taxpayer->fullname();
                    }
                )
                ->sortable()
                ->searchable(),
            Column::make('Nationality', 'taxpayer.country_id')
                ->format(fn ($value, $row) => Country::find($value)->nationality ?? ''),
            Column::make('Region', 'taxpayer.region_id')
                ->format(function ($value, $row) {
                    return Region::find($value)->value('name') ?? '';
                }),
            Column::make('Street', 'taxpayer.street_id')
                ->format(function ($value, $row) {
                    return Street::find($value)->value('name') ?? '';
                }),
            Column::make('Action', 'id')
                ->view('taxpayers.amendments.includes.actions'),
        ];
    }
}
