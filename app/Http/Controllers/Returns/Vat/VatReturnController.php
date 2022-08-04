<?php

namespace App\Http\Controllers\Returns\Vat;

use App\Http\Controllers\Controller;
use App\Models\Returns\Vat\VatReturn;
use Illuminate\Http\Request;

class VatReturnController extends Controller
{
    public function show($id)
    {
        $return = VatReturn::query()->findOrFail(decrypt($id));
        return view('returns.vat_returns.show', compact('return'));
    }
}
