<?php

namespace App\Http\Controllers\Returns\Vat;

use App\Http\Controllers\Controller;
use App\Models\Returns\Vat\VatReturn;
use App\Traits\ReturnCardReport;
use Illuminate\Http\Request;

class VatReturnController extends Controller
{
    use ReturnCardReport;
    
    public function index()
    {
        // $data = $this->returnCardReport(VatReturn::class, 'vat', 'vat_return');

         return view('returns.vat_returns.index');
    }
    public function show($id)
    {
        $return = VatReturn::query()->findOrFail(decrypt($id));
        return view('returns.vat_returns.show', compact('return', 'id'));
    }
}
