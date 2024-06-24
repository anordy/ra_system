<?php

namespace App\Services\CustomDataTable\Traits;

use App\Services\CustomDataTable\Traits\Configuration\SecondaryHeaderConfiguration;
use App\Services\CustomDataTable\Traits\Helpers\SecondaryHeaderHelpers;

trait WithSecondaryHeader
{
    use SecondaryHeaderConfiguration,
        SecondaryHeaderHelpers;

    protected bool $secondaryHeaderStatus = true;

    protected bool $columnsWithSecondaryHeader = false;

    protected $secondaryHeaderTrAttributesCallback;

    protected $secondaryHeaderTdAttributesCallback;

    public function setupSecondaryHeader(): void
    {
        foreach ($this->getColumns() as $column) {
            if ($column->hasSecondaryHeader()) {
                $this->columnsWithSecondaryHeader = true;
            }
        }
    }
}
