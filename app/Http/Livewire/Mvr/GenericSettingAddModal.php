<?php

namespace App\Http\Livewire\Mvr;

use App\Models\Bank;
use App\Models\GenericSettingModel;
use App\Models\MvrColor;
use App\Models\MvrFee;
use App\Models\MvrFeeType;
use App\Models\MvrMake;
use App\Models\MvrModel;
use App\Models\MvrRegistrationType;
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
        MvrFee::class=>[
            ['title'=>'Motor vehicle Registration Status','class'=>MvrRegistrationType::class,'field'=>'mvr_registration_type_id'],
            ['title'=>'Fee Type/Category','class'=>MvrFeeType::class,'field'=>'mvr_fee_type_id']]
    ];
    private $fields = [
        MvrColor::class=>[['title'=>'Hex Value','field'=>'hex_value']],
        MvrFee::class=>[['title'=>'Amount','field'=>'amount'],['title'=>'GFS Code','field'=>'gfs_code']]
    ];

    private $rules = [
        MvrFee::class=>['data.amount'=>'required|numeric','data.gfs_code'=>'required']
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
            $this->setting_title = preg_replace('/^.*\\\\Mvr/','',$model);
        }

        $this->prepareRelations();
        $this->prepareData();
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
        $rules = ['name' => 'required|gs_unique'];
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
            $data = ['name' => $this->name];
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
}
