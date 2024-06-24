<?php

namespace App\Services\CustomDataTable\Views\Columns;

use Illuminate\Database\Eloquent\Model;
use App\Services\CustomDataTable\Exceptions\DataTableConfigurationException;
use App\Services\CustomDataTable\Views\Column;
use App\Services\CustomDataTable\Views\Traits\Configuration\BooleanColumnConfiguration;
use App\Services\CustomDataTable\Views\Traits\Helpers\BooleanColumnHelpers;

class BooleanColumn extends Column
{
    use BooleanColumnConfiguration,
        BooleanColumnHelpers;

    protected string $type = 'icons';

    protected bool $successValue = true;

    protected string $view = 'vendor.custom-datatable.includes.columns.boolean';

    protected $callback;

    public function getContents(Model $row)
    {
        if ($this->isLabel()) {
            throw new DataTableConfigurationException('You can not specify a boolean column as a label.');
        }

        $value = $this->getValue($row);

        return view($this->getView())
            ->withComponent($this->getComponent())
            ->withSuccessValue($this->getSuccessValue())
            ->withType($this->getType())
            ->withStatus($this->hasCallback() ? call_user_func($this->getCallback(), $value, $row) : (bool) $value === true);
    }
}
