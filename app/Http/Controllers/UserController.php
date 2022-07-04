<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        if (!auth()->user()->can('roles_add')) {
            \abort(503, 'You do not have permission');
        }
        return view('users.index');
    }

}
