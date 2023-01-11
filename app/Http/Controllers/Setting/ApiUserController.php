<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ApiUserController extends Controller
{
    public function index()
    {
        if (!Gate::allows('setting-api-user-view')) {
            abort(403);
        }

        return view('settings.api-users.index');
    }

}
