<?php

namespace App\Http\Livewire\Mvr;

use App\Models\DlFee;
use App\Models\DlLicenseClass;
use App\Models\DlLicenseDuration;
use App\Models\DlRestriction;
use App\Models\GenericSettingModel;
use App\Models\MvrClass;
use App\Models\MvrColor;
use App\Models\MvrFee;
use App\Models\MvrFeeType;
use App\Models\MvrMake;
use App\Models\MvrModel;
use App\Models\MvrRegistrationType;
use App\Models\MvrTransferCategory;
use App\Models\MvrTransferFee;
use App\Models\Parameter;
use App\Models\Report;
use App\Models\ReportParameter;
use App\Models\ReportType;
use App\Models\Region;
use App\Models\TaxRefund\PortLocation;
use App\Models\TaxType;
use App\Traits\CustomAlert;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;

class GenericSettingAddModal extends Component
{

    use CustomAlert;

    private array $relations = [
        MvrModel::class => [['title' => 'Motor vehicle Make', 'class' => MvrMake::class, 'field' => 'mvr_make_id']],
        MvrTransferFee::class => [['title' => 'Transfer Category', 'class' => MvrTransferCategory::class, 'field' => 'mvr_transfer_category_id']],
        MvrFee::class => [
            ['title' => 'Fee Type/Category', 'class' => MvrFeeType::class, 'field' => 'mvr_fee_type_id'],
            ['title' => 'Motor vehicle Registration Type', 'class' => MvrRegistrationType::class, 'field' => 'mvr_registration_type_id'],
            ['title' => 'Motor vehicle Class', 'class' => MvrClass::class, 'field' => 'mvr_class_id'],
            ['title' => 'Motor vehicle Class', 'class' => MvrClass::class, 'field' => 'mvr_class_id',
                'dy_data' => "\$relation['data']=App\Models\MvrRegistrationType::query()->where(['name'=>App\Models\MvrRegistrationType::TYPE_PRIVATE_GOLDEN,'id'=>\$relation_data['mvr_registration_type_id']])->exists()?
                             App\Models\MvrClass::query()->whereNotIn('category',['C'])->get():App\Models\MvrClass::query()->get();"
            ],
        ],
        DlFee::class=>[['title'=>'License Duration','field'=>'dl_license_duration_id', 'class'=>DlLicenseDuration::class,'value_field'=>'number_of_years']],
        Report::class => [
            ['title'=>'Report Module','class'=>ReportType::class,'field'=>'report_type_id'],
        ],
        MvrColor::class => [
            ['title' => 'Registration Type', 'class' => MvrRegistrationType::class, 'field' => 'mvr_registration_type_id']
        ]
    ];

    private array $enums = [
        DlFee::class=>[
            ['title'=>'Type','field'=>'type','options'=>['FRESH'=>'Fresh Applicant','RENEW'=>'License Renewal','DUPLICATE'=>'License Copy']]
        ],
        Parameter::class => [
            ['title' => 'Input Type', 'field' => 'input_type', 'options' => ['date' => 'Date', 'text' => 'Text', 'select' => 'Select']]
        ],
    ];

    private array $checkboxs = [
        Report::class=>[
            ['title' => 'Parameters', 'class' => Parameter::class, 'field' => 'parameter'],
        ]
    ];

    private $fields = [
        MvrFee::class => [['title' => 'Amount', 'field' => 'amount']],
        MvrTransferFee::class => [['title' => 'Amount', 'field' => 'amount', 'type' => 'number']],
        DlLicenseDuration::class => [['title' => 'Years', 'field' => 'number_of_years', 'type' => 'number'], ['title' => 'Description', 'field' => 'description']],
        DlFee::class => [['title' => 'Amount', 'field' => 'amount', 'type' => 'number']],
        DlRestriction::class => [
            ['title' => 'Code', 'field' => 'code', 'type' => 'number'],
            ['title' => 'Description', 'field' => 'description', 'type' => 'string'],
            ['title' => 'Symbol', 'field' => 'symbol', 'type' => 'string'],
        ],
        DlLicenseClass::class => [['title' => 'Description', 'field' => 'description']],
        MvrRegistrationType::class => [['title' => 'Initial Plate Number', 'field' => 'initial_plate_number']],
        MvrColor::class => [['title' => 'Color', 'field' => 'color', 'placeholder' => 'e.g. White/Black']],
        MvrClass::class => [['title' => 'Code', 'field' => 'code'], ['title' => 'Category', 'field' => 'category']],
        Parameter::class => [
            ['title'=>'Code','field'=>'code'],
            ['title'=>'Model','field'=>'model_name'],
            ['title'=>'Description','field'=>'description'],
        ],
        Report::class => [
            ['title'=>'URL','field'=>'report_url'],
        ]
    ];

    private $no_name_column = [
        DlLicenseDuration::class => true,
        DlRestriction::class => true,
        MvrColor::class => true,
        MvrFee::class => true
    ];

    private $rules = [
        MvrFee::class => ['data.amount' => 'required|numeric'],
        MvrTransferFee::class => ['data.amount' => 'required|numeric'],
        DlFee::class => ['data.amount' => 'required|numeric', 'relation_data.mvr_plate_number_type_id' => 'nullable'],
        MvrRegistrationType::class => ['data.initial_plate_number' => 'required|alpha_num'],
        MvrColor::class => ['data.color' => 'required', 'relation_data.mvr_registration_type_id' => 'required|gs_relation_unique'],
        DlRestriction::class => [
            'data.code' => ['required', 'gs_unique'],
            'data.symbol' => ['required', 'gs_unique'],
            'data.description' => ['required']
        ],
        MvrClass::class => [
            'data.code' => ['required', 'gs_unique', 'alpha', 'max:5'],
            'data.category' => ['required', 'alpha', 'max:1'],
        ],
        PortLocation::class => ['relation_data.region_id' => 'required|exists:regions,id', 'name' => 'alpha_num_space']
    ];

    /**
     * @var Model|string
     */
    public $model;
    public $instance;
    public $name;
    public $data = [];
    public $relation_data = [];
    public $relation_options = [];
    public $field_options = [];
    public $enum_options = [];
    public $check_options = [];

    /**
     * @var array|string|string[]|null
     */
    public $setting_title = '';
    /**
     * @var mixed
     */
    public $plateNoType;

    public function mount($model, $id = null)
    {
        $this->model = $model;
        if (!empty($id)) {
            $this->instance = $model::query()->find(decrypt($id));
            $this->name = $this->instance->name;
        }

        if (array_search(GenericSettingModel::class, class_parents($model))) {
            $this->setting_title = $model::settingTitle();
        } else {
            $this->setting_title = preg_replace('/^(.*\\\\(Mvr|Dl))/', '', $model);
            $this->setting_title = preg_replace('/^(Mvr|Dl)/', '', $this->setting_title);
            $this->setting_title = preg_replace('/([a-z]+)([A-Z])/', '$1 $2', $this->setting_title);
            $this->setting_title = preg_replace('/App\\\\Models\\\\/', '', $this->setting_title);
        }

        $this->prepareRelations();
        $this->prepareData();
        $this->prepareEnums();
        $this->prepareCheckbox();
    }

    protected function messages()
    {
        return [
            '*.*.required' => 'This field is required.',
            '*.required' => 'This field is required.',
            'required' => 'This field is required.'
        ];
    }

    protected function rules()
    {
        Validator::extend('gs_unique', function ($attribute, $value, $parameters, $validator) {
            $exist = empty($this->instance) ? $this->model::query()->where([str_replace('data.', '', $attribute) => $value])->exists()
                : $this->model::query()->where([str_replace('data.', '', $attribute) => $value])->whereKeyNot($this->instance->id)->exists();
            if ($exist) {
                $validator->errors()->add($attribute, "This field must be unique, a duplicate record with this value already exist");
            }
            return !$exist;
        });

        Validator::extend('gs_relation_unique', function ($attribute, $value, $parameters, $validator) {
            $exist = empty($this->instance) ? $this->model::query()->where([str_replace('relation_data.', '', $attribute) => $value])->exists()
                : $this->model::query()->where([str_replace('relation_data.', '', $attribute) => $value])->whereKeyNot($this->instance->id)->exists();
            if ($exist) {
                $validator->errors()->add($attribute, "This field must be unique, a duplicate record with this value already exist");
            }
            return !$exist;
        });

        $rules = [];
        if ($this->hasNameColumn()) {
            $rules['name'] = 'required|string|gs_unique';
        }

        if (!empty($this->relations[$this->model])) {
            foreach ($this->relations[$this->model] as $foreign) {
                $rules['relation_data.' . $foreign['field']] = 'required';
            }
        }

        if (!empty($this->fields[$this->model])) {
            foreach ($this->fields[$this->model] as $field) {
                $rules['data.' . $field['field']] = 'required';
            }
        }

        if (!empty($this->rules[$this->model])) {
            foreach ($this->rules[$this->model] as $field => $rule) {
                $rules[$field] = $rule;
            }
        }

        return $rules;
    }


    public function submit()
    {
        $this->validate();
        try{
            $data = [];
            if ($this->hasNameColumn()) {
                $data['name'] = $this->name;
            }

            if (!empty($this->relations[$this->model])) {
                foreach ($this->relations[$this->model] as $foreign) {
                    $data[$foreign['field']] = $this->relation_data[$foreign['field']];
                }
            }

            if (!empty($this->fields[$this->model])) {
                foreach ($this->fields[$this->model] as $field) {
                    $data[$field['field']] = $this->data[$field['field']];
                }
            }

            if (!empty($this->enums[$this->model])) {
                foreach ($this->enums[$this->model] as $field) {
                    $data[$field['field']] = $this->data[$field['field']];
                }
            }

            if ($this->model == \App\Models\MvrFee::class) {
                $data['mvr_plate_number_type_id'] = $this->plateNoType;
                $data['gfs_code'] = TaxType::where('code', TaxType::PUBLIC_SERVICE)->first()->gfs_code;
            }

            if (empty($this->instance)){
                $setting = $this->model::query()->create($data);
                $this->flash('success', 'Record added successfully', [], redirect()->back()->getTargetUrl());
            }else{
                $setting = $this->instance;
                $this->instance->update($data);
                $this->flash('success', 'Record updated successfully', [], redirect()->back()->getTargetUrl());
            }


            if (!empty($this->check_options[$this->model])) {
                foreach ($this->data['checkboxes'] as $checkbox) {
                    //$data[$checkbox['field']] = $this->data[$checkbox['field']];
                    $test[] = $checkbox;

                    if($checkbox){
                        ReportParameter::create([
                            'report_id' => $setting->id,
                            'parameter_id' => $checkbox
                        ]);
                    }
                }

            }

        }catch(Exception $e){
            Log::error('GENERIC-SETTING-ADD-MODAL', [$e]);
            $this->customAlert('error', 'Something went wrong, please contact the administrator for help');
        }
    }

    public function render()
    {
        return view('livewire.mvr.generic-setting-add-modal');
    }

    private function prepareRelations()
    {
        if (empty($this->relations[$this->model])) return;
        foreach ($this->relations[$this->model] as $foreign) {
            $this->relation_options[$foreign['field']] = ['data' => $foreign['dy_data'] ?? $foreign['class']::query()->get(), 'title' => $foreign['title']];
            $this->relation_data[$foreign['field']] = $this->instance[$foreign['field']] ?? null;
            if (isset($foreign['value_field'])) {
                $this->relation_options[$foreign['field']]['value_field'] = $foreign['value_field'];
            }
        }
    }

    private function prepareData()
    {
        if (empty($this->fields[$this->model])) return;
        foreach ($this->fields[$this->model] as $field) {
            $this->field_options[$field['field']] = ['title' => $field['title'], 'placeholder' => $field['placeholder'] ?? ''];
            $this->data[$field['field']] = $this->instance[$field['field']] ?? null;
        }
    }

    private function prepareEnums()
    {
        if (empty($this->enums[$this->model])) return;
        foreach ($this->enums[$this->model] as $enums) {
            $this->enum_options[$enums['field']] = ['data' => $enums['options'], 'title' => $enums['title']];
        }
    }

    private function prepareCheckbox()
    {
        if (empty($this->checkboxs[$this->model])) return;
        // foreach ($this->checkboxs[$this->model] as $checkbox) {
        //     $this->check_options[$checkbox['field']] = ['data'=>$checkbox['options'], 'title'=>$checkbox['title']];
        //     $this->data[$checkbox['field']] = $this->instance[$checkbox['field']] ?? [];
        // }

        foreach ($this->checkboxs[$this->model] as $checkbox) {
            //$this->check_options[$checkbox['field']] = ['data'=>$checkbox['dy_data'] ?? $checkbox['class']::query()->get(), 'title'=>$checkbox['title']];
            //$this->data[$checkbox['field']] = $this->instance[$checkbox['field']] ?? null;
            //if (isset($checkbox['value_field'])) {
            //    $this->relation_options[$checkbox['field']]['value_field'] = $checkbox['value_field'];
            //}

            // options
            $this->check_options[$this->model] =  $checkbox['class']::query()->get();
        }
    }

    private function hasNameColumn()
    {
        return empty($this->no_name_column[$this->model]);
    }

    private function getFieldInputType($field)
    {
        if (!empty($this->fields[$this->model])) {
            foreach ($this->fields[$this->model] as $_field) {
                if ($_field['field'] == $field) {
                    return $_field['type'] ?? 'text';
                }
            }
        }
        return 'text';
    }
}
