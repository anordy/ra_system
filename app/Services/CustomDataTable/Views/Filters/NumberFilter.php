<?php

namespace App\Services\CustomDataTable\Views\Filters;

use App\Services\CustomDataTable\DataTableComponent;
use App\Services\CustomDataTable\Views\Filter;

class NumberFilter extends Filter
{
    public function validate($value)
    {
        return is_numeric($value) ? $value : false;
    }

    public function isEmpty($value): bool
    {
        return $value === '';
    }

    /**
     * Gets the Default Value for this Filter via the Component
     */
    public function getFilterDefaultValue(): ?string
    {
        return $this->filterDefaultValue ?? null;
    }

    public function render(DataTableComponent $component)
    {
        return view('vendor.custom-datatable.components.tools.filters.number', [
            'component' => $component,
            'filter' => $this,
        ]);
    }
}
