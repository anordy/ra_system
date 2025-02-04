<?php

namespace App\Http\Controllers\MVR;

use App\Http\Controllers\Controller;
use App\Models\MvrBlacklist;

class BlacklistController extends Controller
{
    public function index()
    {
        return view('mvr.blacklist.index');
    }

    public function show($id)
    {
        $blacklist = MvrBlacklist::findOrFail(decrypt($id), ['id', 'type', 'blacklist_type', 'initiator_type', 'blacklist_id', 'created_by', 'evidence_path', 'reasons', 'status', 'marking', 'created_at', 'is_blocking']);
        return view('mvr.blacklist.show', compact('blacklist'));
    }
}
