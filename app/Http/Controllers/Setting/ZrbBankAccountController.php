<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ZrbBankAccountController extends Controller
{
    //
    public function index()
    {
         if (!Gate::allows('zrb-bank-account-view')) {
              abort(403);
         }

         return view('settings.zrb-bank-accounts.index');
    }
}
