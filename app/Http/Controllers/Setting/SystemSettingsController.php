<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class SystemSettingsController extends Controller
{
    public function setting_categories()
    {
         if (!Gate::allows('setting-system-category-view')) {
              abort(403);
         }
         return view('settings.system-settings.category.index');
    }

    public function system_settings ()
    {
         if (!Gate::allows('system-setting-view')) {
              abort(403);
         }
         return view('settings.system-settings.setting-entries.index');
    }
}
