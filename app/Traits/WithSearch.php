<?php

namespace App\Traits;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Traits\Helpers\SearchHelpers;
use Rappasoft\LaravelLivewireTables\Traits\Configuration\SearchConfiguration;

trait WithSearch
{
    use SearchConfiguration,
        SearchHelpers;

    public ?string $search = null;
    public bool $searchStatus = true;
    public bool $searchVisibilityStatus = true;
    public ?int $searchFilterDebounce = null;
    public ?bool $searchFilterDefer = null;
    public ?bool $searchFilterLazy = null;

    // TODO
    public function applySearch(): Builder
    {
        if ($this->searchIsEnabled() && $this->hasSearch()) {
            $searchableColumns = $this->getSearchableColumns();

            if ($searchableColumns->count()) {
                $this->setBuilder($this->getBuilder()->where(function ($query) use ($searchableColumns) {
                    foreach ($searchableColumns as $index => $column) {
                        if ($column->hasSearchCallback()) {
                            ($column->getSearchCallback())($query, $this->getSearch());
                        } else {
                            $query->{$index === 0 ? 'where' : 'orWhere'}(DB::raw('LOWER(' . $column->getColumn() . ')'), 'like', '%' . strtolower($this->getSearch()) . '%');
                        }
                    }
                }));
            }
        }

        return $this->getBuilder();
    }
}
