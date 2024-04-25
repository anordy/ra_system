<?php

namespace App\Http\Livewire\Taxpayers;

use App\Models\KYC;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class RegistrationsTable extends DataTableComponent
{
    public function mount()
    {
        $this->index = $this->page > 1 ? ($this->page - 1) * $this->perPage : 0;
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setAdditionalSelects(['first_name', 'middle_name', 'last_name']);
        $this->setTableWrapperAttributes([
            'default' => true,
            'class' => 'table-bordered table-sm',
        ]);
    }

    public function builder(): Builder
    {
        return KYC::query()
            ->with('country', 'region','street')
            ->orderBy('created_at', 'desc');
    }

    public function columns(): array
    {
        return [
            Column::make('Name')
                ->label(fn ($row) => $row->fullname())
                ->sortable()
                ->searchable(function (Builder $query, $searchTerm) {
                    $searchTerms = explode(" ", $searchTerm);
                    foreach ($searchTerms as $term) {
                        $query->orWhereRaw(DB::raw("LOWER(first_name) like '%' || LOWER('$term') || '%'"))
                            ->orWhereRaw(DB::raw("LOWER(middle_name) like '%' || LOWER('$term') || '%'"))
                            ->orWhereRaw(DB::raw("LOWER(last_name) like '%' || LOWER('$term') || '%'"));
                    }
                }),
            Column::make('Mobile No', 'mobile')->searchable(),
            Column::make('Email Address', 'email')->searchable(),
            Column::make('Nationality', 'country.nationality')->searchable(),
            Column::make('Location', 'region.name')->searchable(),
            Column::make('Street', 'street.name')->searchable(),
            Column::make('Registered At', 'created_at')->searchable(),
            Column::make('Action', 'id')->view('taxpayers.registrations.actions')
        ];
    }
}
