<?php

namespace App\Http\Controllers\Returns\Port;

use App\Http\Controllers\Controller;
use App\Models\Returns\Port\PortReturn;

class PortReturnController extends Controller
{
    public function index()
    {
        return view('returns.port.index');
    }

      public function show($return_id)
    {
        $returnId = decrypt($return_id);
        $return = PortReturn::findOrFail($returnId);
        return view('returns.port.show', compact('return'));
    }
}
