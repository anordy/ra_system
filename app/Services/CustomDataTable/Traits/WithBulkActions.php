<?php

namespace App\Services\CustomDataTable\Traits;

use App\Services\CustomDataTable\Traits\Configuration\BulkActionsConfiguration;
use App\Services\CustomDataTable\Traits\Helpers\BulkActionsHelpers;

trait WithBulkActions
{
    use BulkActionsConfiguration,
        BulkActionsHelpers;

    public bool $bulkActionsStatus = true;

    public bool $selectAll = false;

    public array $bulkActions = [];

    public array $selected = [];

    public bool $hideBulkActionsWhenEmpty = false;

    public function bulkActions(): array
    {
        if (property_exists($this, 'bulkActions')) {
            return $this->bulkActions;
        }

        return [];
    }
}
