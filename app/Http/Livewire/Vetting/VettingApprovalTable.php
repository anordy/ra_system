<?php

namespace App\Http\Livewire\Vetting;

use Carbon\Carbon;
use App\Models\TaxType;
use App\Enum\VettingStatus;
use App\Models\Returns\TaxReturn;
use App\Traits\ReturnFilterTrait;
use Illuminate\Support\Facades\Gate;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Returns\LumpSum\LumpSumReturn;
use App\Models\Returns\Petroleum\PetroleumReturn;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;

class VettingApprovalTable extends DataTableComponent
{
    use  ReturnFilterTrait;

    protected $model     = TaxReturn::class;

    public $vettingStatus, $orderBy;

    public function mount($vettingStatus)
    {
        if (!Gate::allows('tax-returns-vetting-view')) {
            abort(403);
        }

        $this->vettingStatus = $vettingStatus;

        if ($this->vettingStatus == VettingStatus::VETTED) {
            $this->orderBy = 'DESC';
        } else {
            $this->orderBy = 'ASC';
        }
    }

    public function filters(): array
    {
        return [
            SelectFilter::make('Tax Region')
                ->options([
                    'all'    => 'All',
                    'Headquarter' => 'Head Quarter',
                    'Mjini'  => 'Mjini',
                    'Kaskazini Unguja'  => 'Kaskazini Unguja',
                    'Kusini Unguja'  => 'Kusini Unguja',
                    'Kaskazini Pemba'  => 'Kaskazini Pemba',
                    'Kusini Pemba'  => 'Kusini Pemba',
                ])
                ->filter(function(Builder $builder, string $value) {
                    if ($value != 'all') {
                        $builder->whereHas('location.taxRegion', function($query) use($value) {
                            $query->where('name', $value);
                        });
                    }
                }),
        ];
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setFilterLayoutSlideDown();
        $this->setAdditionalSelects(['location_id', 'tax_type_id', 'financial_month_id']);
        $this->setTableWrapperAttributes([
            'default' => true,
            'class'   => 'table-bordered table-sm',
        ]);
        
    }

    public function builder(): Builder
    {
        return TaxReturn::with('business', 'location', 'taxtype', 'financialMonth', 'location.taxRegion')
            ->whereNotIn('return_type', [PetroleumReturn::class, LumpSumReturn::class])
            ->whereNotIn('code', [
                TaxType::AIRPORT_SERVICE_CHARGE,
                TaxType::SEAPORT_TRANSPORT_CHARGE,
                TaxType::AIRPORT_SAFETY_FEE,
                TaxType::SEAPORT_SERVICE_CHARGE,
                TaxType::ROAD_LICENSE_FEE,
                TaxType::INFRASTRUCTURE, 
                TaxType::RDF
            ])
            ->where('parent',0)
            ->where('is_business_lto',false)
            ->where('vetting_status', $this->vettingStatus)
            ->whereHas('pinstance', function ($query) {
                $query->where('status', '!=', 'completed');
                $query->whereHas('actors', function ($query) {
                    $query->where('user_id', auth()->id());
                });
            })
            ->orderBy('created_at', $this->orderBy);
    }

    public function columns(): array
    {
        return [
            Column::make('Business Name', 'business.name')
                ->sortable()
                ->searchable(),
            Column::make('Branch', 'location.name')
                ->sortable()
                ->searchable()
                ->format(function ($value, $row) {
                    return "{$row->location->name}";
                }),
                Column::make('Tax Payer', 'taxpayer.first_name')
                ->sortable()
                ->searchable()
                ->format(function ($value, $row) {
                    $firstName = $row->taxpayer->first_name ?? "";
                    $lastName = $row->taxpayer->last_name ?? "";
                    return "{$firstName} {$lastName}";
                }),
            Column::make('Tax Region', 'location.tax_region_id')
                ->sortable()
                ->searchable()
                ->format(function ($value, $row) {
                    return "{$row->location->taxRegion->name}";
                }),
            Column::make('Tax Type', 'taxtype.name')
                ->sortable()
                ->searchable()
                ->format(function ($value, $row) {
                    return "{$row->taxtype->name}";
                }),
            Column::make('Financial Month', 'financialMonth.name')
                ->sortable()
                ->searchable()
                ->format(function ($value, $row) {
                    return "{$row->financialMonth->name} {$row->financialMonth->year->code}";
                }),
            Column::make('Total', 'total_amount')
                ->sortable()
                ->format(function ($value, $row) {
                    return number_format($value, 2);
                })
                ->searchable(),
            Column::make('Currency', 'currency')
                ->sortable()
                ->searchable(),
            Column::make('Status', 'vetting_status')
                ->view('vetting.includes.status')
                ->searchable()
                ->sortable(),
            Column::make('Payment Status', 'payment_status')
                ->view('returns.includes.payment-status')
                ->searchable()
                ->sortable()
                ->hideIf($this->vettingStatus != VettingStatus::VETTED),
            Column::make('Filed On', 'created_at')
                ->sortable()
                ->format(function ($value, $row) {
                    return Carbon::create($value)->format('M d, Y H:i');
                })
                ->searchable(),
            Column::make('Action', 'id')->view('vetting.includes.actions'),
        ];
    }
}
