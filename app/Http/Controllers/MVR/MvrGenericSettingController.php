<?php

namespace App\Http\Controllers\MVR;

use App\Http\Controllers\Controller;
use App\Models\GenericSettingModel;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class MvrGenericSettingController extends Controller
{

    public function index($model): Factory|View|Application
    {
        $class = 'App\Models\\'.$model;
        abort_if(!class_exists($class),404);

        if (array_search(GenericSettingModel::class,class_parents($class))){
            $setting_title = $class::settingTitle();
        }else {
            $setting_title = preg_replace('/^Mvr/','',$model);
            $setting_title = preg_replace('/([a-z]+)([A-Z])/','$1 $2',$setting_title);
        }

        return view('settings.mvr-generic',['model'=>$class,'setting_title'=>$setting_title]);
    }
}
