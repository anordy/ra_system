<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class RegionController extends Controller
{
   public function index()
   {
      if (!Gate::allows('setting-region-view')) {
         abort(403);
      }

      return view('settings.region');
   }
}
