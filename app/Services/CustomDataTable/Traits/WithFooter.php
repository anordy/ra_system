<?php

namespace App\Services\CustomDataTable\Traits;

use App\Services\CustomDataTable\Traits\Configuration\FooterConfiguration;
use App\Services\CustomDataTable\Traits\Helpers\FooterHelpers;

trait WithFooter
{
    use FooterConfiguration,
        FooterHelpers;

    protected bool $footerStatus = true;

    protected bool $useHeaderAsFooterStatus = false;

    protected bool $columnsWithFooter = false;

    protected $footerTrAttributesCallback;

    protected $footerTdAttributesCallback;

    public function setupFooter(): void
    {
        foreach ($this->getColumns() as $column) {
            if ($column->hasFooter()) {
                $this->columnsWithFooter = true;
            }
        }
    }
}
