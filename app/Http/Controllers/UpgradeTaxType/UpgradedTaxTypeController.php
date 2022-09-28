<?php

namespace App\Http\Controllers\UpgradeTaxType;

use App\Http\Controllers\Controller;
use App\Models\BusinessTaxTypeChange;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class UpgradedTaxTypeController extends Controller
{
    public function index()
    {
        if (!Gate::allows('upgraded-tax-types-view')) {
            abort(403);
        }
        return view('upgrade-tax-type.upgraded.index');
    }

    public function show($id)
    {
        if (!Gate::allows('upgraded-tax-types-view')) {
            abort(403);
        }
        $tax_type_change = BusinessTaxTypeChange::query()->findOrFail(decrypt($id));
        return view('upgrade-tax-type.upgraded.show', compact('tax_type_change'));
    }
}
