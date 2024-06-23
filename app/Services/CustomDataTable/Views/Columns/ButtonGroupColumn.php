<?php

namespace App\Services\CustomDataTable\Views\Columns;

use Illuminate\Database\Eloquent\Model;
use App\Services\CustomDataTable\Views\Column;
use App\Services\CustomDataTable\Views\Traits\Configuration\ButtonGroupColumnConfiguration;
use App\Services\CustomDataTable\Views\Traits\Helpers\ButtonGroupColumnHelpers;

class ButtonGroupColumn extends Column
{
    use ButtonGroupColumnConfiguration,
        ButtonGroupColumnHelpers;

    protected array $buttons = [];

    protected string $view = 'vendor.custom-datatable.includes.columns.button-group';

    protected $attributesCallback;

    public function __construct(string $title, string $from = null)
    {
        parent::__construct($title, $from);

        $this->label(fn () => null);
    }

    public function getContents(Model $row)
    {
        return view($this->getView())
            ->withColumn($this)
            ->withRow($row)
            ->withButtons($this->getButtons())
            ->withAttributes($this->hasAttributesCallback() ? app()->call($this->getAttributesCallback(), ['row' => $row]) : []);
    }
}
