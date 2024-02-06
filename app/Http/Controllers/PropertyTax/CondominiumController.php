<?php

namespace App\Http\Controllers\PropertyTax;

use App\Http\Controllers\Controller;
use App\Models\PropertyTax\Condominium;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CondominiumController extends Controller
{

    public function register() {
        if (!Gate::allows('condominium-registration')) {
            abort(403);
        }
        return view('property-tax.condominium.register');
    }

    public function index() {
        if (!Gate::allows('condominium-registration')) {
            abort(403);
        }
        return view('property-tax.condominium.index');
    }

    public function show(string $id) {
        if (!Gate::allows('condominium-registration')) {
            abort(403);
        }
        $condominium = Condominium::findOrFail(decrypt($id));
        return view('property-tax.condominium.show', compact('condominium'));
    }

    public function edit(string $id) {
        if (!Gate::allows('condominium-registration')) {
            abort(403);
        }
        return view('property-tax.condominium.edit', compact('id'));
    }
}
