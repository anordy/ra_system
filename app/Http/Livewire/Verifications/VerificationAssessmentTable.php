<?php

namespace App\Http\Livewire\Verifications;

use App\Enum\TaxVerificationStatus;
use App\Enum\VettingStatus;
use App\Models\Region;
use App\Models\Verification\TaxVerification;
use App\Traits\ReturnFilterTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use App\Traits\CustomAlert;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class VerificationAssessmentTable extends DataTableComponent
{
    use CustomAlert, ReturnFilterTrait;

    protected $listeners = ['filterData' => 'filterData', '$refresh'];
    
    public $data = [], $locations = [], $department;

    public $model = TaxVerification::class;

    public function mount($department){

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
        $query = TaxVerification::query()
            ->whereHas('location.taxRegion', function ($query) {
                $query->whereIn('location', $this->locations);
            })
            ->where('tax_verifications.status', TaxVerificationStatus::APPROVED);

        return $this->dataAssessmentFilter($query, $this->data);
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
            Column::make('ZRA No', 'location.zin'),
            Column::make('TIN', 'business.tin'),
            Column::make('Business Name', 'business.name'),
            Column::make('Business Location', 'location.name'),
            Column::make('Tax Type', 'taxtype.name'),
            Column::make('Filled By', 'created_by_id')
                ->format(function ($value, $row) {
                    $user = $row->createdBy()->first();
                    return $user->full_name ?? '';
                }),
            Column::make('Currency', 'assessment.currency'),
            Column::make('Amount', 'assessment.total_amount')
                ->format(function ($value) {
                    return number_format($value ?? 0);
                }),
            Column::make('Added On', 'created_at')
                ->format(fn ($value) => Carbon::create($value)->toDayDateTimeString()),
            Column::make('Payment Status', 'tax_return_id')
                ->view('verification.payment_status'),
            Column::make('Action', 'id')
                ->view('verifications.assessments.action')
                ->html(true),
        ];
    }
}
