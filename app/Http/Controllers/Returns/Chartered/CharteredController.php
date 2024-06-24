<?php

namespace App\Http\Controllers\Returns\Chartered;

use App\Enum\CustomMessage;
use App\Http\Controllers\Controller;
use App\Models\Returns\Chartered\CharteredReturn;
use App\Models\Returns\EmTransactionReturn;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CharteredController extends Controller
{
    public function create(){
        return view('returns.chartered.create');
    }

    public function indexSea(){
        return view('returns.chartered.index-sea');
    }

    public function indexFlight(){
        return view('returns.chartered.index-flight');
    }

    public function show($return_id, Request $request){
        $request->merge(['return_id' => $return_id]);
        $request->validate(['return_id' => 'required|alpha_num']);
        try {
            $returnId = decrypt($return_id);
            $return = CharteredReturn::findOrFail($returnId);
            return view('returns.chartered.show', compact('return', 'returnId'));
        } catch (\Exception $exception) {
            Log::error('RETURNS-CHARTERED-CHARTERED-CONTROLLER-SHOW-RETURN', [$exception]);
            session()->flash('error', CustomMessage::error());
            return redirect()->back();
        }
    }

    public function edit($return_id, Request $request){
        $request->merge(['return_id' => $return_id]);
        $request->validate(['return_id' => 'required|alpha_num']);
        try {
            return view('returns.chartered.edit', compact('return_id'));
        } catch (\Exception $exception) {
            Log::error('RETURNS-CHARTERED-CHARTERED-CONTROLLER-EDIT', [$exception]);
            session()->flash('error', CustomMessage::error());
            return redirect()->back();
        }
    }
}
