<?php

namespace App\Http\Controllers\PublicService;

use App\Enum\CustomMessage;
use App\Http\Controllers\Controller;
use App\Models\PublicService\TemporaryClosure;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class TemporaryClosuresController extends Controller
{
    public function index(){
        if (!Gate::allows('public-service-view-temporary-closures')) {
            abort(403);
        }
        return view('public-service.temporary-closures.index');
    }

    public function show($closureId){
        if (!Gate::allows('public-service-view-temporary-closures')) {
            abort(403);
        }
        try {
            $closure = TemporaryClosure::findOrFail(decrypt($closureId));
            return view('public-service.temporary-closures.show', compact('closure'));
        } catch (\Exception $exception){
            Log::error('PUBLIC-SERVICE-DE-TEMPORARY-CLOSURE', [$exception->getMessage()]);
            session()->flash('error', CustomMessage::error());
            return redirect()->back();
        }
    }
}
