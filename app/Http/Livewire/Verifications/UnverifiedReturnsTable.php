<?php

namespace App\Http\Livewire\Verifications;

use App\Enum\BillStatus;
use App\Enum\CustomMessage;
use App\Enum\TaxVerificationStatus;
use App\Models\Region;
use App\Models\Returns\TaxReturn;
use App\Models\User;
use App\Models\Verification\TaxVerification;
use App\Models\WorkflowTask;
use App\Traits\CustomAlert;
use App\Traits\ReturnFilterTrait;
use App\Traits\TaxVerificationTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Illuminate\Support\Facades\Gate;

class UnverifiedReturnsTable extends DataTableComponent
{
    use CustomAlert, ReturnFilterTrait, TaxVerificationTrait;

    protected $listeners = ['filterData' => 'filterData', '$refresh', 'performInitiation'];

    public $data = [];

    public $model = WorkflowTask::class;
    public $status, $vetted, $department, $locations;

    public function mount($status, $department, $vetted = false)
    {
        $this->status = $status;
        $this->vetted = $vetted;

        $this->department = $department;

        if ($department === Region::DTD) {
            $this->locations = [Region::DTD];
        } else if ($department === Region::LTD) {
            $this->locations = [Region::LTD, Region::UNGUJA];
        } else if ($department === Region::PEMBA) {
            $this->locations = [Region::PEMBA];
        } else if ($department === Region::NTRD) {
            $this->locations = [Region::NTRD];
        } else {
            $this->locations = [Region::DTD, Region::LTD, Region::PEMBA, Region::NTRD, Region::UNGUJA];
        }

    }

    public function filterData($data)
    {
        $this->data = $data;
        $this->emit('$refresh');
    }

    public function builder(): Builder
    {
        $query = TaxReturn::query()
            ->with('business', 'location', 'taxtype', 'financialMonth', 'location.taxRegion', 'return.verification')
            ->whereIn('payment_status', [BillStatus::COMPLETE, BillStatus::NILL])
            ->whereHas('return', function ($query) {
                $query->whereDoesntHave('verification');
            })
            ->whereHas('location.taxRegion', function ($query) {
                $query->whereIn('location', $this->locations);
            });

        return $this->dataReturnFilter($query, $this->data);
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setTableWrapperAttributes([
            'default' => true,
            'class' => 'table-bordered table-sm',
        ]);
        $this->setAdditionalSelects(['location_id', 'tax_type_id', 'financial_month_id']);
    }

    public function columns(): array
    {
        return [
            Column::make('Taxpayer Name', 'business.taxpayer_name')
                ->format(function ($value, $row) {
                    return $value ?? 'N/A';
                }),
            Column::make('Business Name', 'business.name')
                ->searchable(),
            Column::make('Branch', 'location.name')
                ->format(function ($value, $row) {
                    return $row->location->name ?? '';
                }),
            Column::make('Tax Region', 'location.tax_region_id')
                ->searchable()
                ->format(function ($value, $row) {
                    return $row->location->taxRegion->name ?? '';
                }),
            Column::make('Tax Type', 'taxtype.name')
                ->searchable()
                ->format(function ($value, $row) {
                    return $row->taxtype->name ?? '';
                }),
            Column::make('Financial Month', 'financialMonth.name')
                ->searchable()
                ->format(function ($value, $row) {
                    $month = $row->financialMonth->name ?? '';
                    $year = $row->financialMonth->year->code ?? '';
                    return $month . ' ' . $year;
                }),
            Column::make('Total', 'total_amount')
                ->format(function ($value, $row) {
                    return number_format($value, 2);
                }),
            Column::make('Currency', 'currency'),
            Column::make('Status', 'vetting_status')
                ->view('vetting.includes.status'),
            Column::make('Payment Status', 'payment_status')
                ->view('returns.includes.payment-status'),
            Column::make('Filed On', 'created_at')
                ->format(function ($value, $row) {
                    return Carbon::create($value)->format('M d, Y H:i');
                }),
            Column::make('Action', 'id')
                ->view('verifications.includes.initiate'),
        ];
    }

    public function initiate($id)
    {
        if (!Gate::allows('verification-initiate')) {
            // abort(403);
        }

        $id = decrypt($id);

        $this->customAlert('warning', 'Are you sure you want to Initiate Verification for this Return ?', [
            'position' => 'center',
            'toast' => false,
            'showConfirmButton' => true,
            'confirmButtonText' => 'Initiate',
            'onConfirmed' => 'performInitiation',
            'showCancelButton' => true,
            'cancelButtonText' => 'Cancel',
            'timer' => null,
            'data' => [
                'id' => $id
            ],

        ]);
    }

    public function performInitiation($value)
    {
        try {
            $data = (object)$value['data'];
            $taxReturn = TaxReturn::findOrFail($data->id);
            $childReturn = $taxReturn->return;

            $data = [
                'tax_return_id' => $childReturn->id ?? '',
                'tax_return_type' => get_class($childReturn),
                'business_id' => $childReturn->business_id,
                'location_id' => $childReturn->business_location_id ?? null,
                'tax_type_id' => $childReturn->tax_type_id,
                'created_by_id' => Auth::id() ?? null,
                'created_by_type' => User::class,
                'status' => TaxVerificationStatus::PENDING,
            ];

            TaxVerification::create($data);

            $this->flash('success', 'Return has been added in Verification queue', [], redirect()->back()->getTargetUrl());

        } catch (\Exception $exception) {
            Log::error('VERIFICATIONS-UNVERIFIED-RETURNS-TABLE-PERFORM-INITIATION', [$exception]);
            $this->customAlert('error', CustomMessage::ERROR);
        }
    }
}
