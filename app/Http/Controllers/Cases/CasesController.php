<?php

namespace App\Http\Controllers\Cases;

use App\Http\Controllers\Controller;
use App\Models\CaseAppeal;
use App\Models\DlApplicationStatus;
use App\Models\DlDriversLicense;
use App\Models\DlDriversLicenseClass;
use App\Models\DlFee;
use App\Models\DlLicenseApplication;
use App\Models\LegalCase;
use App\Models\RioRegister;
use App\Services\ZanMalipo\ZmCore;
use App\Services\ZanMalipo\ZmResponse;
use App\Traits\WorkflowProcesssingTrait;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class CasesController extends Controller
{
    use WorkflowProcesssingTrait;

    public function index(){
        if (!Gate::allows('legal-cases-view')) {
            abort(403);
        }
        return view('cases.cases-index');
    }

    public function show($id){
        if (!Gate::allows('legal-cases-view')) {
            abort(403);
        }
        $id = decrypt($id);
        $case = LegalCase::query()->findOrFail($id);
        return view('cases.cases-show',compact('case'));
    }

    public function appealShow($id){
        if (!Gate::allows('legal-cases-appeal')) {
            abort(403);
        }
        $id = decrypt($id);
        $appeal = CaseAppeal::query()->findOrFail($id);
        $case = $appeal->case;
        return view('cases.appeal-show',compact('case', 'appeal'));
    }

    public function appealsIndex(){
        if (!Gate::allows('legal-cases-appeal')) {
            abort(403);
        }
        return view('cases.appeals-index');
    }
}
