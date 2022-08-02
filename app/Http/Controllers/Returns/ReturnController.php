<?php

namespace App\Http\Controllers\Returns;

use App\Http\Controllers\Controller;
use App\Models\Returns\Vat\VatReturn;
use Illuminate\Http\Request;

class ReturnController extends Controller
{
    public function index()
    {
        return view('returns.index');
    }

    public function show($id)
    {
        $return = VatReturn::query()->findOrFail(decrypt($id));
//        dd($return->items->config);
//        foreach ($return->items as $item)
//        {
//            $config = $item->config;
//            dd($config);
//        }
        return view('returns.vat_returns.show', compact('return'));
    }
}
