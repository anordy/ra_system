<?php

namespace App\Services\CustomDataTable\Traits;

use App\Services\CustomDataTable\Traits\Configuration\DebuggingConfiguration;
use App\Services\CustomDataTable\Traits\Helpers\DebugHelpers;

trait WithDebugging
{
    use DebuggingConfiguration,
        DebugHelpers;

    /**
     * Dump table properties for debugging
     */
    protected bool $debugStatus = false;
}
