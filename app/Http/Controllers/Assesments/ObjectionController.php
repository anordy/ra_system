<?php

namespace App\Http\Controllers\Assesments;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\Objection;
use App\Models\ObjectionAttachment;
use App\Models\WaiverAttachment;

class ObjectionController extends Controller
{
    public function index()
    {
        return view('assesments.objection.index');
    }

    public function edit()
    {
        return view('assesments.objection.edit');
    }
}
