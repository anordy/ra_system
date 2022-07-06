<?php

namespace App\Http\Livewire\Business\Closure;

use Exception;
use Carbon\Carbon;
use App\Models\BusinessTempClosure;
use Illuminate\Database\Eloquent\Builder;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\DataTableComponent;

class PendingClosuresTable extends DataTableComponent
{
    use LivewireAlert;


    protected $listeners = [
        'confirmed',
    ];

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setAdditionalSelects(['is_extended', 'status']);
    }

    public function builder(): Builder
    {
        return BusinessTempClosure::query()->orderBy('business_temp_closures.opening_date', 'DESC');
    }

    public function columns(): array
    {
        return [
            Column::make('Name', 'business.name')
                ->sortable()
                ->searchable(),
            Column::make('TIN', 'business.tin')
                ->sortable()
                ->searchable(),
            Column::make('Reg No.', 'business.reg_no')
                ->sortable()
                ->searchable(),
            Column::make('Closing Date', 'closing_date')
                ->format(function($value, $row) { return Carbon::create($row->closing_date)->toFormattedDateString(); })
                ->sortable()
                ->searchable(),
            Column::make('Opening Date', 'opening_date')
                ->format(function($value, $row) { return Carbon::create($row->opening_date)->toFormattedDateString(); })
                ->sortable()
                ->searchable(),
            Column::make('Is Extended', 'is_extended')
                ->format(function($value, $row) { 
                    if ($row->is_extended == false) {
                        return <<< HTML
                        <span class="badge badge-info py-1 px-2">No</span>
                    HTML;
                    } else {
                        return <<< HTML
                        <span class="badge badge-success py-1 px-2">Yes</span>
                    HTML;
                    }
                 })
                ->sortable()
                ->searchable()
                ->html(true), 
            Column::make('Closure Reason', 'reason')
                ->sortable(),
            // Column::make('Action', 'id')
            //     ->format(function ($value, $row) {
            //             return <<< HTML
            //             <button class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="top" title="Approve" wire:click="changeStatus($value, 'approved')"><i class="fa fa-check"></i> </button>
            //             <button class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Reject" wire:click="changeStatus($value, 'rejected')"><i class="fa fa-times"></i> </button>
            //         HTML;
            //     })
            //     ->html(true),
        ];
    }

    
    public function changeStatus($id, $status)
    {
        $status_text = $status == 'approved' ? 'Approve' : 'Reject';
        $this->alert('warning', 'Are you sure you want to '. $status_text .' ?', [
            'position' => 'center',
            'toast' => false,
            'showConfirmButton' => true,
            'confirmButtonText' => $status_text,
            'onConfirmed' => 'confirmed',
            'showCancelButton' => true,
            'cancelButtonText' => 'Cancel',
            'timer' => null,
            'data' => [
                'id' => $id,
                'status' => $status
            ],

        ]);
    }


    public function confirmed($value)
    {
        try {
            $data = (object) $value['data'];
            $temporary_business_closure = BusinessTempClosure::find($data->id);
            if ($data->status == 'approved') {
                $temporary_business_closure->update([
                    'approved_by' => auth()->user()->id,
                    'approved_on' => date('Y-m-d H:i:s'),
                    'status' => $data->status
                ]);
                $this->flash('success', 'Business '. $data->status . ' successfully', [], redirect()->back()->getTargetUrl());
            } else if ($data->status == 'rejected') {
                $temporary_business_closure->update([
                    'rejected_by' => auth()->user()->id,
                    'rejected_on' => date('Y-m-d H:i:s'),
                    'status' => $data->status
                ]);
                $this->flash('success', 'Business '. $data->status . ' successfully', [], redirect()->back()->getTargetUrl());
            }
        } catch (Exception $e) {
            report($e);
            $this->alert('warning', 'Something whent wrong!!!', ['onConfirmed' => 'confirmed', 'timer' => 2000]);
        }
    }

}
