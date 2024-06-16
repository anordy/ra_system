<?php

namespace App\Services\CustomDataTable\Views\Filters;

use DateTime;
use App\Services\CustomDataTable\DataTableComponent;
use App\Services\CustomDataTable\Views\Filter;

class DateFilter extends Filter
{
    public function validate($value)
    {
        if (DateTime::createFromFormat('Y-m-d', $value) === false) {
            return false;
        }

        return $value;
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
        return view('vendor.custom-datatable.components.tools.filters.date', [
            'component' => $component,
            'filter' => $this,
        ]);
    }
}
