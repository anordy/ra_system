<?php

namespace App\Http\Livewire\Mvr;

use App\Models\Bank;
use App\Models\DlDriversLicenseClass;
use App\Models\DlFee;
use App\Models\DlLicenseClass;
use App\Models\DlLicenseDuration;
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
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class GenericSettingAddModal extends Component
{

    use LivewireAlert;

    private array $relations = [
        MvrModel::class=>[['title'=>'Motor vehicle Make','class'=>MvrMake::class,'field'=>'mvr_make_id']],
        MvrTransferFee::class=>[['title'=>'Transfer Category','class'=>MvrTransferCategory::class,'field'=>'mvr_transfer_category_id']],
        MvrFee::class=>[
            ['title'=>'Motor vehicle Registration Type','class'=>MvrRegistrationType::class,'field'=>'mvr_registration_type_id'],
            ['title'=>'Motor vehicle Class','class'=>MvrClass::class,'field'=>'mvr_class_id'],
            ['title'=>'Fee Type/Category','class'=>MvrFeeType::class,'field'=>'mvr_fee_type_id']]
    ];

    private array $enums = [
        DlFee::class=>[
            ['title'=>'Type','field'=>'type','options'=>['FRESH'=>'Fresh Applicant','RENEW'=>'License Renewal','DUPLICATE'=>'License Copy']]
        ]
    ];

    private $fields = [
        MvrColor::class=>[['title'=>'Hex Value','field'=>'hex_value']],
        MvrFee::class=>[['title'=>'Amount','field'=>'amount'],['title'=>'GFS Code','field'=>'gfs_code']],
        MvrTransferFee::class=>[['title'=>'Amount','field'=>'amount','type'=>'number'],['title'=>'GFS Code','field'=>'gfs_code','type'=>'number']],
        DlLicenseDuration::class=>[['title'=>'Years','field'=>'number_of_years','type'=>'number'],['title'=>'Description','field'=>'description']],
        DlFee::class=>[['title'=>'Amount','field'=>'amount','type'=>'number'],['title'=>'GFS Code','field'=>'gfs_code','type'=>'number']],
        DlLicenseClass::class=>[['title'=>'Description','field'=>'description']],
    ];

    private $no_name_column = [
        DlLicenseDuration::class=>true,
    ];

    private $rules = [
        MvrFee::class=>['data.amount'=>'required|numeric','data.gfs_code'=>'required|numeric'],
        MvrTransferFee::class=>['data.amount'=>'required|numeric','data.gfs_code'=>'required|numeric'],
        DlFee::class=>['data.amount'=>'required|numeric','data.gfs_code'=>'required|numeric']
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
    /**
     * @var array|string|string[]|null
     */
    public $setting_title = '';

    public function mount($model,$id=null)
    {
        $this->model = $model;
        if (!empty($id)){
            $this->instance = $model::query()->find($id);
            $this->name = $this->instance->name;
        }

        if (array_search(GenericSettingModel::class,class_parents($model))){
            $this->setting_title = $model::settingTitle();
        }else {
            $this->setting_title = preg_replace('/^(.*\\\\(Mvr|Dl))/','',$model);
            $this->setting_title = preg_replace('/^(Mvr|Dl)/','',$this->setting_title);
            $this->setting_title = preg_replace('/([a-z]+)([A-Z])/','$1 $2',$this->setting_title);
        }

        $this->prepareRelations();
        $this->prepareData();
        $this->prepareEnums();
    }

    protected function rules()
    {
        Validator::extend('gs_unique', function($attribute, $value, $parameters,$validator) {
            $exist = empty($this->instance) ? $this->model::query()->where([$attribute => $value])->exists()
                : $this->model::query()->where([$attribute => $value])->whereKeyNot($this->instance->id)->exists();
            if ($exist){
                $validator->errors()->add($attribute, "{$attribute} must be unique, {$value} already exist");
            }
            return !$exist;
        });

        if ($this->hasNameColumn()) {
            $rules = ['name' => 'required|gs_unique'];
        }

        if (!empty($this->relations[$this->model])){
            foreach ($this->relations[$this->model] as $foreign){
                $rules['relation_data.'.$foreign['field']] = 'required';
            }
        }
        if (!empty($this->fields[$this->model])){
            foreach ($this->fields[$this->model] as $field){
                $rules['data.'.$field['field']] = 'required';
            }
        }
        if (!empty($this->rules[$this->model])){
            foreach ($this->rules[$this->model] as $field=>$rule){
                $rules[$field] = $rule;
            }
        }
        return $rules;
    }


    public function submit()
    {
        $this->validate();
        try{
            if ($this->hasNameColumn()) {
                $data = ['name' => $this->name];
            }

            if (!empty($this->relations[$this->model])){
                foreach ($this->relations[$this->model] as $foreign){
                    $data[$foreign['field']] = $this->relation_data[$foreign['field']];
                }
            }

            if (!empty($this->fields[$this->model])){
                foreach ($this->fields[$this->model] as $field){
                    $data[$field['field']] = $this->data[$field['field']];
                }
            }

            if (!empty($this->enums[$this->model])){
                foreach ($this->enums[$this->model] as $field){
                    $data[$field['field']] = $this->data[$field['field']];
                }
            }

            if (empty($this->instance)){
                $this->model::query()->create($data);
                $this->flash('success', 'Record added successfully', [], redirect()->back()->getTargetUrl());
            }else{
                $this->instance->update($data);
                $this->flash('success', 'Record updated successfully', [], redirect()->back()->getTargetUrl());
            }

        }catch(Exception $e){
            Log::error($e);
            $this->alert('error', 'Something went wrong');
        }
    }

    public function render()
    {
        return view('livewire.mvr.generic-setting-add-modal');
    }

    private function prepareRelations()
    {
        if (empty($this->relations[$this->model])) return;
        foreach ($this->relations[$this->model] as $foreign){
            $this->relation_options[$foreign['field']] = ['data'=>$foreign['class']::query()->get(),'title'=>$foreign['title']];
            $this->relation_data[$foreign['field']] = $this->instance[$foreign['field']]??null;
        }
    }

    private function prepareData()
    {
        if (empty($this->fields[$this->model])) return;
        foreach ($this->fields[$this->model] as $field){
            $this->field_options[$field['field']] = ['title'=>$field['title']];
            $this->data[$field['field']] = $this->instance[$field['field']]??null;
        }
    }

    private function prepareEnums()
    {
        if (empty($this->enums[$this->model])) return;
        foreach ($this->enums[$this->model] as $enums){
            $this->enum_options[$enums['field']] = ['data'=>$enums['options'],'title'=>$enums['title']];
        }
    }

    private function hasNameColumn(){
        return empty($this->no_name_column[$this->model]);
    }

    private function getFieldInputType($field){
        if (!empty($this->fields[$this->model])) {
            foreach ($this->fields[$this->model] as $_field){
                if($_field['field'] == $field){
                    return $_field['type'] ?? 'text';
                }
            }
        }
        return 'text';
    }
}
