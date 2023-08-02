<?php

namespace App\Http\Livewire\Business;

use App\Models\Business;
use App\Models\BusinessStatus;
use Illuminate\Database\Eloquent\Builder;
use App\Traits\CustomAlert;
use App\Traits\WithSearch;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class RegistrationsTable extends DataTableComponent
{

    use CustomAlert;

    public $rejected = false;
    public $pending = false;
    public $approved = true;

    public function builder(): Builder
    {
        if ($this->rejected) {
            return Business::with('category')
                ->where('businesses.status', BusinessStatus::REJECTED)
                ->orderBy('businesses.created_at', 'desc');
        }

        if ($this->approved) {
            return Business::with('category')
                ->where('businesses.status', BusinessStatus::APPROVED)
                ->orderBy('businesses.approved_on', 'desc');
        }

        if ($this->pending) {
            return Business::with('category')
                ->where('businesses.status', BusinessStatus::PENDING)
                ->orderBy('businesses.created_at', 'desc');
        }

        return Business::with('category')
            ->where('businesses.status', '!=', BusinessStatus::DRAFT)
            ->orderBy('businesses.approved_on', 'desc');
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
            Column::make('ZTN', 'ztn_number')
                ->format(fn ($value) => $value ?? 'N/A')
                ->sortable()->searchable(),
            Column::make('Business Category', 'category.name')
                ->format(fn ($value) => strtoupper($value ?? 'N/A'))
                ->sortable()->searchable(),
            Column::make('Business Type', 'business_type')
                ->format(fn ($value) => strtoupper($value ?? 'N/A'))
                ->sortable()->searchable(),
            Column::make('Business Name', 'name')->format(function ($value) {
                return $value ? $value : 'N/A';
            })->sortable()->searchable(),
            Column::make('TIN', 'tin')->format(function ($value) {
                return $value ? $value : 'N/A';
            })->sortable()->searchable(),
            Column::make('Buss. Reg. No.', 'reg_no')->format(function ($value) {
                return $value ? $value : 'N/A';
            })->sortable()->searchable(),
            Column::make('Mobile', 'mobile')->format(function ($value) {
                return $value ? $value : 'N/A';
            })->sortable()->searchable(),
            Column::make('Status', 'status')
                ->view('business.registrations.includes.status'),
            Column::make('Action', 'id')
                ->view('business.registrations.includes.actions')
        ];
    }
}
