<?php

namespace App\Http\Livewire\Verifications;

use App\Enum\TaxVerificationStatus;
use App\Enum\VettingStatus;
use App\Models\Region;
use App\Models\Verification\TaxVerification;
use App\Models\WorkflowTask;
use App\Traits\ReturnFilterTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use App\Traits\CustomAlert;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class VerificationsTable extends DataTableComponent
{
    use CustomAlert, ReturnFilterTrait;

    protected $listeners = ['filterData' => 'filterData', '$refresh'];

    public $data = [];

    public $model = WorkflowTask::class;
    public $status, $vetted, $department, $locations;

    public function mount($status, $department, $vetted = false){
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
        $query = TaxVerification::query()
            ->whereHas('location.taxRegion', function ($query) {
                $query->whereIn('location', $this->locations);
            })
            ->where('tax_verifications.status', $this->status);

        if ($this->status === TaxVerificationStatus::PENDING && !$this->vetted){
            $query->whereHas('pinstance', function ($query) {
                $query->whereHas('actors', function ($query) {
                    $query->where('user_id', auth()->id());
                });
            });
        }

        if ($this->vetted){
            $query->whereHas('taxReturn', function (Builder $builder) {
                $builder->where('vetting_status', VettingStatus::VETTED);
            });
        }

        return $this->dataAssessmentFilter($query, $this->data);
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setTableWrapperAttributes([
            'default' => true,
            'class'   => 'table-bordered table-sm',
        ]);
    }

    public function columns(): array
    {
        return [
            Column::make('Z_Number', 'location.zin')
                ->searchable(),
            Column::make('TIN', 'business.tin')
                ->searchable(),
            Column::make('Business Name', 'business.name')
                ->searchable(),
            Column::make('Business Location', 'location.name'),
            Column::make('Tax Type', 'taxtype.name'),
            Column::make('Filled On', 'created_at')
                ->format(fn ($value) => Carbon::create($value)->toDayDateTimeString()),
            Column::make('Action', 'id')
                ->view('verifications.includes.approved-actions'),
        ];
    }
}
