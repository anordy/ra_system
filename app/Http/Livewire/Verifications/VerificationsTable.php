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

class VerificationsTable extends DataTableComponent
{
    use CustomAlert, ReturnFilterTrait;

    protected $listeners = ['filterData' => 'filterData', '$refresh'];

    public $data = [];

    public $model = WorkflowTask::class;
    public $status, $vetted;

    public function mount($status, $vetted = false){
        $this->status = $status;
        $this->vetted = $vetted;
    }

    public function filterData($data)
    {
        $this->data = $data;
        $this->emit('$refresh');
    }

    public function builder(): Builder
    {
        $query = TaxVerification::query()
            ->with(['taxReturn'])
            ->where('tax_verifications.status', $this->status);

        if ($this->status === TaxVerificationStatus::PENDING && !$this->vetted){
            $query->whereHas('pinstance', function ($query) {
                $query->where('status', '!=', 'completed');
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

        $table = TaxVerification::getTableName();
        return $this->dataFilter($query, $this->data, $table);
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setTableWrapperAttributes([
            'default' => true,
            'class'   => 'table-bordered table-sm',
        ]);
        $this->setAdditionalSelects(['tax_return_type', 'tax_return_id']);
    }

    public function columns(): array
    {
        return [
            Column::make('Control Number', 'business.reg_no')
                ->sortable()
                ->searchable()
                ->label(function ($row, $value) {
                    if (isset($row->taxReturn->tax_return->latestBill->control_number)) {
                        return $row->taxReturn->tax_return->latestBill->control_number;
                    } else {
                        return 'N/A';
                    }
                }),
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
            Column::make('Filled On', 'created_at')
                ->format(fn ($value) => Carbon::create($value)->toDayDateTimeString()),
            Column::make('Action', 'id')
                ->view('verifications.includes.approved-actions'),
        ];
    }
}
