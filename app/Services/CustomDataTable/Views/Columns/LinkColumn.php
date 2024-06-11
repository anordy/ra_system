<?php

namespace App\Services\CustomDataTable\Views\Columns;

use Illuminate\Database\Eloquent\Model;
use App\Services\CustomDataTable\Exceptions\DataTableConfigurationException;
use App\Services\CustomDataTable\Views\Column;
use App\Services\CustomDataTable\Views\Traits\Configuration\LinkColumnConfiguration;
use App\Services\CustomDataTable\Views\Traits\Helpers\LinkColumnHelpers;

class LinkColumn extends Column
{
    use LinkColumnHelpers,
        LinkColumnConfiguration;

    protected string $view = 'vendor.custom-datatable.includes.columns.link';

    protected $titleCallback;

    protected $locationCallback;

    protected $attributesCallback;

    public function __construct(string $title, string $from = null)
    {
        parent::__construct($title, $from);

        $this->label(fn () => null);
    }

    public function getContents(Model $row)
    {
        if (! $this->hasTitleCallback()) {
            throw new DataTableConfigurationException('You must specify a title callback for an link column.');
        }

        if (! $this->hasLocationCallback()) {
            throw new DataTableConfigurationException('You must specify a location callback for an link column.');
        }

        return view($this->getView())
            ->withColumn($this)
            ->withTitle(app()->call($this->getTitleCallback(), ['row' => $row]))
            ->withPath(app()->call($this->getLocationCallback(), ['row' => $row]))
            ->withAttributes($this->hasAttributesCallback() ? app()->call($this->getAttributesCallback(), ['row' => $row]) : []);
    }
}
