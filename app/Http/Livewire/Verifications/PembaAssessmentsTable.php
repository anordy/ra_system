<?php

namespace App\Http\Livewire\Verifications;

use App\Enum\TaxVerificationStatus;
use App\Enum\VettingStatus;
use App\Models\Region;
use App\Models\Verification\TaxVerification;
use App\Models\WorkflowTask;
use App\Traits\CustomAlert;
use App\Traits\ReturnFilterTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class PembaAssessmentsTable extends DataTableComponent
{
    use CustomAlert, ReturnFilterTrait;

    protected $listeners = ['filterData' => 'filterData', '$refresh'];

    public $data = [];

    public $model = WorkflowTask::class;
    public $status;

    public function mount($status){
        $this->status = $status;
    }

    public function filterData($data)
    {
        $this->data = $data;
        $this->emit('$refresh');
    }

    public function builder(): Builder
    {

        $query = TaxVerification::query()
            ->whereHas('assessment')
            ->whereHas('location.taxRegion', function ($query) {
                $query->where('location', Region::PEMBA);
            })
            ->where('tax_verifications.status', $this->status);

        $table = TaxVerification::getTableName();
        $query = $this->dataFilter($query, $this->data, $table);
        return $query;
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
                ->sortable()
                ->searchable(),
            Column::make('TIN', 'business.tin')
                ->sortable()
                ->searchable(),
            Column::make('Business Name', 'business.name')
                ->sortable()
                ->searchable(),
            Column::make('Business Location', 'location.name')
                ->sortable()
                ->searchable(),
            Column::make('Tax Type', 'taxtype.name')
                ->sortable()
                ->searchable(),
            Column::make('Payment Status', 'tax_return_id')
                ->view('verification.payment_status'),
            Column::make('Filled On', 'created_at')
                ->format(fn ($value) => Carbon::create($value)->toDayDateTimeString()),
            Column::make('Action', 'id')
                ->view('verifications.assessments.includes.actions'),
        ];
    }
}
