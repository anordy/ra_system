<?php

namespace App\Services\CustomDataTable\Views\Traits\Helpers;

use App\Services\CustomDataTable\Views\Columns\LinkColumn;

trait ButtonGroupColumnHelpers
{
    public function getView(): string
    {
        return $this->view;
    }

    public function getButtons(): array
    {
        return collect($this->buttons)
            ->reject(fn ($button) => ! $button instanceof LinkColumn)
            ->toArray();
    }

    public function getAttributesCallback()
    {
        return $this->attributesCallback;
    }

    public function hasAttributesCallback()
    {
        return $this->attributesCallback !== null;
    }
}
