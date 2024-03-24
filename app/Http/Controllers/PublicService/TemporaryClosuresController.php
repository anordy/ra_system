<?php

namespace App\Http\Controllers\PublicService;

use App\Enum\CustomMessage;
use App\Http\Controllers\Controller;
use App\Models\PublicService\TemporaryClosure;
use Illuminate\Support\Facades\Log;

class TemporaryClosuresController extends Controller
{
    // Todo: Add permissions
    public function index(){
        return view('public-service.temporary-closures.index');
    }

    public function show($closureId){
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
