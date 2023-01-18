<?php

namespace App\Http\Livewire\LandLease;

use App\Models\BusinessLocation;
use App\Models\LandLease;
use App\Models\Taxpayer;
use Illuminate\Database\Eloquent\Builder;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class LandLeaseReportTable extends DataTableComponent
{
    use LivewireAlert;

    public $dates = [];
    public $taxpayer_id;

    protected $listeners = ['refreshTable' => 'refreshTable', 'test'];

    public function builder(): Builder
    {
        $dates = $this->dates;
        $taxpayer_id = $this->taxpayer_id;
        
        if ($dates == []) {
            $model = LandLease::query();
        } elseif ($dates['startDate'] == null || $dates['endDate'] == null) {
            $model = LandLease::query();
        } else {
            $model =  LandLease::query()->whereBetween('land_leases.created_at', [$dates['startDate'], $dates['endDate']]);
        }

        if ($taxpayer_id) {
            $model = clone $model->where('taxpayer_id', $taxpayer_id);
        }

        return $model->orderBy('land_leases.created_at', 'asc');
    }

    public function refreshTable($dates)
    {
        $this->dates = $dates['dates'];
        $this->taxpayer_id = $dates['taxpayer_id'];
        $this->builder();
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setTableWrapperAttributes([
            'default' => true,
            'class'   => 'table-bordered table-sm',
        ]);

        $this->setAdditionalSelects(['is_registered', 'business_location_id']);
    }

    public function columns(): array
    {
        return [
            Column::make('Register Date', 'created_at')
                ->format(function ($value, $row) {
                    return date('d/m/Y', strtotime($value));
                })
                ->searchable()
                ->sortable(),
            Column::make('DP Number', 'dp_number')
                ->searchable()
                ->sortable(),
            Column::make('Name', 'category')
                ->format(
                    function ($value, $row) {

                        if ($row['category'] == 'business') {
                            return $this->getBusinessName(encrypt($row->business_location_id));
                        } else {
                            if ($row->is_registered == 1) {
                                return $this->getApplicantName(encrypt($row->taxpayer_id));
                            } else {
                                return $row->name;
                            }
                        }
                    }
                )
                ->sortable(),
            Column::make('Applicant Type', 'updated_at')
                ->format(function ($value, $row) {
                    return ucwords($row['category']);
                })
                ->searchable()
                ->sortable(),
            Column::make('ZRB No./ Zin No.', 'name')
                ->format(
                    function ($value, $row) {
                        if ($row->category == 'business') {
                            return $this->getBusinessZin(encrypt($row->business_location_id));
                        } else {
                            if ($row->is_registered == 1) {
                                return $this->getApplicantNo(encrypt($row->taxpayer_id));
                            } else {
                                return $row->name;
                            }
                        }
                    }
                )
                ->searchable()
                ->sortable(),
            Column::make('Applicant Type', 'email')->view('land-lease.includes.applicant-status'),
            Column::make('Commence Date', 'commence_date')
                ->format(function ($value, $row) {
                    return date('d/m/Y', strtotime($value));
                })
                ->searchable()
                ->sortable(),

            Column::make('Payment Month(USD)', 'payment_month')
                ->format(function ($value, $row) {
                    return $value;
                })
                ->searchable()
                ->sortable(),
            Column::make('Review Shedule', 'review_schedule')
                ->format(function ($value, $row) {
                    return $value . ' years';
                })
                ->searchable()
                ->sortable(),
            Column::make('Contact Person', 'taxpayer_id')
                ->format(
                    function ($value, $row) {
                        if ($row->is_registered == 1) {
                            $taxpayer = Taxpayer::find($row->taxpayer_id);
                            return $taxpayer->first_name .' '. $taxpayer->last_name;
                        } else {
                            return $row->name;
                        }
                    }
                )
                ->searchable()
                ->sortable(),
            Column::make('Phone Number', 'phone')
                ->format(
                    function ($value, $row) {
                        if ($row->is_registered == 1) {
                            $taxpayer = Taxpayer::find($row->taxpayer_id);
                            return $taxpayer->mobile;
                        } else {
                            return $row->phone;
                        }
                    }
                )
                ->searchable()
                ->sortable(),
            Column::make('Actions', 'id')
                ->view('land-lease.includes.actions'),
        ];
    }

    public function getApplicantName($id)
    {
        $taxpayer = Taxpayer::find(decrypt($id)); // todo: encrypt id
        return $taxpayer->first_name . ' ' . $taxpayer->last_name;
    }

    public function getBusinessName($id)
    {
        $businessLocation = BusinessLocation::find(decrypt($id)); // todo: encrypt id

        return $businessLocation->business->name . ' | ' . $businessLocation->name;
    }

    public function getApplicantNo($id)
    {
        $taxpayer = Taxpayer::find(decrypt($id)); // todo: encrypt id

        return $taxpayer->reference_no;
    }

    public function getBusinessZin($id)
    {
        $businessLocation = BusinessLocation::find(decrypt($id)); // todo: encrypt id

        return $businessLocation->zin;
    }
}
