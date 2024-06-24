<?php

namespace App\Services\CustomDataTable\Traits;

use Illuminate\Support\Collection;
use App\Services\CustomDataTable\Traits\Helpers\ColumnHelpers;

trait WithColumns
{
    use ColumnHelpers;

    protected Collection $columns;

    public function bootWithColumns(): void
    {
        $this->columns = collect();
    }
}
