<?php

namespace App\Http\Livewire\Incedent;

use App\Models\RaIncedent;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;

class IncedentTable extends DataTableComponent
{

	public function builder(): Builder
	{
        return RaIncedent::query()->whereIn('ra_incedents.status', [
            RaIncedent::APPROVED,
        ])->orderByDesc('ra_incedents.created_at');
    }


	public function configure(): void
    {
        $this->setPrimaryKey('id');

	    $this->setTableWrapperAttributes([
	      'default' => true,
	      'class' => 'table-bordered table-sm',
	    ]);
    }

    public function columns(): array
    {
        return [
            Column::make(__("Bank Channel"), "channel.name")
            ->searchable(),
        Column::make(__("Incedent"), "name")
            ->searchable(),
            Column::make(__("Real Issue"), "real_issue")
            ->searchable()
            ->format(fn ($value) => $value ? 'Yes' : 'No'),
        Column::make(__("Owner"), "owner.fname")
            ->searchable(),
            // Column::make(__("Revenue Loss"), "revenue.revenue_detected")
            // ->searchable(),
        Column::make(__("Incedent Reported Date"), "incident_reported_date")
            ->format(function ($value, $row) {
                if ($row->incident_reported_date) {
                    return Carbon::create($row->incident_reported_date)->format('d M Y');
                }
                return 'N/A';
            }),
            Column::make(__('Status'), 'status')->view('Incedent.includes.status'),
            Column::make(__('Action'), 'id')->view('incedent.includes.actions'),
        ];
    }


    // public function filters(): array
    // {
        // return [
        //     SelectFilter::make('Region')
        //         ->options([
        //             'all' => ReportStatus::All,
        //             'unguja' => ucfirst(Region::UNGUJA),
        //             'pemba' => ucfirst(Region::PEMBA),
        //         ])
        //         ->filter(function (Builder $builder, string $value) {
        //             if ($value != 'all') {
        //                 $builder->whereHas('region', function ($query) use ($value) {
        //                     $query->where('location', $value);
        //                 });
        //             }
        //         }),
        // ];
    // }

}
