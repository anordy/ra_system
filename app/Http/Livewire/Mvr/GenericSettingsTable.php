<?php

namespace App\Http\Livewire\Mvr;

use App\Enum\GeneralConstant;
use App\Models\DlFee;
use App\Models\DlLicenseClass;
use App\Models\DlLicenseDuration;
use App\Models\GenericSettingModel;
use App\Models\MvrColor;
use App\Models\MvrFee;
use App\Models\MvrModel;
use App\Models\MvrRegistrationType;
use App\Models\MvrTransferFee;
use App\Models\TaxRefund\PortLocation;
use App\Traits\WithSearch;
use App\Traits\CustomAlert;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class GenericSettingsTable extends DataTableComponent
{
	use CustomAlert;
    public $model;
    public $setting_title = '';

    public function builder(): Builder
	{
		return $this->model::query();
	}


    public function mount($model)
    {
        $this->model = $model;

        if (array_search(GenericSettingModel::class,class_parents($model))){
            $this->setting_title = $model::settingTitle();
        }else {
            $this->setting_title = preg_replace('/^.*\\\\Mvr/','',$model);
        }
    }

    protected $listeners = [
        'confirmed'
    ];

    public function configure(): void
    {
        $this->setPrimaryKey('id');

	    $this->setTableWrapperAttributes([
	      'default' => true,
	      'class' => 'table-bordered table-sm',
	    ]);
    }

    public function columns(): array
    {
        $name_column = [];
        if ($this->modelHasNameColumn()){
            $name_column =  [
                Column::make("Name", "name")
                ->sortable()
            ];
        }

        if ($this->model === MvrRegistrationType::class){
            $name_column =  [
                Column::make("Name", "name")
                    ->sortable(),
                Column::make("Initial Plate Number", "initial_plate_number")
                    ->sortable()
            ];
        }
        return array_merge(
            $name_column,
            $this->modelExtraColumns(),[
                Column::make('Action', 'id')
            ->format(function ($value){
                $model = preg_replace('/\\\\/','\\\\\\',$this->model);
                return view('mvr.generic-settings.actions', compact('model'));
            })]);
    }


    public function delete($id)
    {
        $this->customAlert(GeneralConstant::WARNING, 'Are you sure you want to delete ?', [
            'position' => 'center',
            'toast' => false,
            'showConfirmButton' => true,
            'confirmButtonText' => 'Delete',
            'onConfirmed' => 'confirmed',
            'showCancelButton' => true,
            'cancelButtonText' => 'Cancel',
            'timer' => null,
            'data' => [
                'id' => $id
            ],

        ]);
    }

    public function confirmed($value)
    {
        try {
            $data = (object) $value['data'];
            $this->model::find($data->id)->delete();
            $this->flash(GeneralConstant::SUCCESS, 'Record deleted successfully', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            Log::error('GENERIC-SETTING-TABLE', [$e]);
            $this->customAlert(GeneralConstant::WARNING, 'Something went wrong, please contact the administrator for help', ['onConfirmed' => 'confirmed', 'timer' => 2000]);
        }
    }

    private function modelExtraColumns()
    {
        $model_extra_columns = [
            MvrModel::class => [
                Column::make("Motor Vehicle Make", "make.name")->sortable()
            ],
            MvrColor::class => [
                Column::make("Hex Value", "hex_value")->sortable()
            ],
            MvrFee::class => [
                Column::make("Amount", "amount")->sortable()->format(fn($value)=>number_format($value).' TZS'),
                Column::make("GFS Code", "gfs_code")->sortable(),
                Column::make("Fee Category", "fee_type.type")->sortable(),
                Column::make("Registration Type", "registration_type.name")->sortable(),
                Column::make("Class", "class.name")->sortable(),
                Column::make("Status", "status")->sortable(),
            ],
            MvrTransferFee::class => [
                Column::make("Amount", "amount")->sortable()->format(fn($value)=>number_format($value).' TZS'),
                Column::make("GFS Code", "gfs_code")->sortable(),
                Column::make("Transfer Category", "transfer_category.name")->sortable(),
            ],
            DlLicenseClass::class => [
                Column::make("Description", "description")->sortable()
            ],
            DlLicenseDuration::class => [
                Column::make("Years", "number_of_years")->sortable(),
                Column::make("Description", "description")->sortable()
            ],
            DlFee::class => [
                Column::make("Amount", "amount")->sortable()->format(fn($value)=>number_format($value).' TZS'),
                Column::make("GFS Code", "gfs_code")->sortable(),
                Column::make("Type", "type")->sortable(),
                Column::make("Duration", "license_duration.number_of_years")->sortable(),
            ],
            PortLocation::class => [
                Column::make("Region", "region.name")->sortable(),
            ]
        ];

        return $model_extra_columns[$this->model]??[];
    }

    private function modelHasNameColumn(){
        $models_with_no_name_columns = [
            DlLicenseDuration::class => 1,
        ];

        return empty($models_with_no_name_columns[$this->model]);
    }

}
