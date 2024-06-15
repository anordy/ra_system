<?php

namespace App\Services\CustomDataTable;

use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use App\Services\CustomDataTable\Exceptions\DataTableConfigurationException;
use App\Services\CustomDataTable\Traits\ComponentUtilities;
use App\Services\CustomDataTable\Traits\WithBulkActions;
use App\Services\CustomDataTable\Traits\WithColumns;
use App\Services\CustomDataTable\Traits\WithColumnSelect;
use App\Services\CustomDataTable\Traits\WithData;
use App\Services\CustomDataTable\Traits\WithDebugging;
use App\Services\CustomDataTable\Traits\WithEvents;
use App\Services\CustomDataTable\Traits\WithFilters;
use App\Services\CustomDataTable\Traits\WithFooter;
use App\Services\CustomDataTable\Traits\WithPagination;
use App\Services\CustomDataTable\Traits\WithRefresh;
use App\Services\CustomDataTable\Traits\WithReordering;
use App\Services\CustomDataTable\Traits\WithSearch;
use App\Services\CustomDataTable\Traits\WithSecondaryHeader;
use App\Services\CustomDataTable\Traits\WithSorting;

abstract class DataTableComponent extends Component
{
    use ComponentUtilities,
        WithBulkActions,
        WithColumns,
        WithColumnSelect,
        WithData,
        WithDebugging,
        WithEvents,
        WithFilters,
        WithFooter,
        WithSecondaryHeader,
        WithPagination,
        WithRefresh,
        WithReordering,
        WithSearch,
        WithSorting;

    protected $listeners = [
        'refreshDatatable' => '$refresh',
        'setSort' => 'setSortEvent',
        'clearSorts' => 'clearSortEvent',
        'setFilter' => 'setFilterEvent',
        'clearFilters' => 'clearFilterEvent',
    ];

    /**
     * returns a unique id for the table, used as an alias to identify one table from another session and query string to prevent conflicts
     */
    protected function generateDataTableFingerprint(): string
    {
        $className = str_split(static::class);
        $crc32 = sprintf('%u', crc32(serialize($className)));

        return base_convert($crc32, 10, 36);
    }

    /**
     * Runs on every request, immediately after the component is instantiated, but before any other lifecycle methods are called
     */
    public function boot(): void
    {
        $this->{$this->tableName} = [
            'sorts' => $this->{$this->tableName}['sorts'] ?? [],
            'filters' => $this->{$this->tableName}['filters'] ?? [],
            'columns' => $this->{$this->tableName}['columns'] ?? [],
        ];

    }

    /**
     * Runs on every request, after the component is mounted or hydrated, but before any update methods are called
     */
    public function booted(): void
    {
        // Call the configure() method
        $this->configure();

        // Set the filter defaults based on the filter type
        // Moved to Traits/Helpers/FilterHelpers - mountFilterHelpers
        //$this->setFilterDefaults();

        // Sets the Theme - tailwind/bootstrap
        // Moved to Traits/ComponentUtilities - mountComponentUtilities
        //$this->setTheme();

        //Sets up the Builder Instance
        $this->setBuilder($this->builder());

        // Sets Columns
        $this->setColumns();

        // Make sure a primary key is set
        if (! $this->hasPrimaryKey()) {
            throw new DataTableConfigurationException('You must set a primary key using setPrimaryKey in the configure method.');
        }
    }

    /**
     * Set any configuration options
     */
    abstract public function configure(): void;

    /**
     * The array defining the columns of the table.
     */
    abstract public function columns(): array;

    /**
     * The base query.
     */
    public function builder(): Builder
    {
        if ($this->hasModel()) {
            return $this->getModel()::query()->with($this->getRelationships());
        }

        throw new DataTableConfigurationException('You must either specify a model or implement the builder method.');
    }

    /**
     * The view to add any modals for the table, could also be used for any non-visible html
     */
    public function customView(): string
    {
        return 'vendor.custom-datatable.stubs.custom';
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function render()
    {
        $this->setupColumnSelect();
        $this->setupPagination();
        $this->setupSecondaryHeader();
        $this->setupFooter();
        $this->setupReordering();

        return view('vendor.custom-datatable.datatable')
            ->with([
                'columns' => $this->getColumns(),
                'rows' => $this->getRows(),
                'customView' => $this->customView(),
            ]);
    }
}
