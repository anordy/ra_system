<?php

namespace App\Services\CustomDataTable\Views\Columns;

use Illuminate\Database\Eloquent\Model;
use App\Services\CustomDataTable\Exceptions\DataTableConfigurationException;
use App\Services\CustomDataTable\Views\Column;
use App\Services\CustomDataTable\Views\Traits\Configuration\ImageColumnConfiguration;
use App\Services\CustomDataTable\Views\Traits\Helpers\ImageColumnHelpers;

class ImageColumn extends Column
{
    use ImageColumnHelpers,
        ImageColumnConfiguration;

    protected string $view = 'vendor.custom-datatable.includes.columns.image';

    protected $locationCallback;

    protected $attributesCallback;

    public function __construct(string $title, string $from = null)
    {
        parent::__construct($title, $from);

        $this->label(fn () => null);
    }

    public function getContents(Model $row)
    {
        if (! $this->hasLocationCallback()) {
            throw new DataTableConfigurationException('You must specify a location callback for an image column.');
        }

        return view($this->getView())
            ->withColumn($this)
            ->withPath(app()->call($this->getLocationCallback(), ['row' => $row]))
            ->withAttributes($this->hasAttributesCallback() ? app()->call($this->getAttributesCallback(), ['row' => $row]) : []);
    }
}
