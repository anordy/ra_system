<?php

namespace App\Services\CustomDataTable\Views\Filters;

use App\Services\CustomDataTable\DataTableComponent;
use App\Services\CustomDataTable\Views\Filter;

class MultiSelectFilter extends Filter
{
    protected array $options = [];

    public function options(array $options = []): MultiSelectFilter
    {
        $this->options = $options;

        return $this;
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    public function getKeys(): array
    {
        return collect($this->getOptions())
            ->keys()
            ->map(fn ($value) => (string) $value)
            ->filter(fn ($value) => strlen($value))
            ->values()
            ->toArray();
    }

    public function validate($value)
    {
        if (is_array($value)) {
            foreach ($value as $index => $val) {
                // Remove the bad value
                if (! in_array($val, $this->getKeys())) {
                    unset($value[$index]);
                }
            }
        }

        return $value;
    }

    /**
     * Get the filter default options.
     *
     * @return array<mixed>
     */
    public function getDefaultValue()
    {
        return [];
    }

    /**
     * Gets the Default Value for this Filter via the Component
     *
     * @return array<mixed>
     */
    public function getFilterDefaultValue(): array
    {
        return $this->filterDefaultValue ?? [];
    }

    public function getFilterPillValue($value): ?string
    {
        $values = [];

        foreach ($value as $item) {
            $found = $this->getCustomFilterPillValue($item) ?? $this->getOptions()[$item] ?? null;

            if ($found) {
                $values[] = $found;
            }
        }

        return implode(', ', $values);
    }

    public function isEmpty($value): bool
    {
        return ! is_array($value);
    }

    public function render(DataTableComponent $component)
    {
        return view('vendor.custom-datatable.components.tools.filters.multi-select', [
            'component' => $component,
            'filter' => $this,
        ]);
    }
}
