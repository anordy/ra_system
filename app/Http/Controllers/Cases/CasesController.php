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

class CasesController extends Controller
{
    use WorkflowProcesssingTrait;

    public function index(){

        return view('cases.cases-index');
    }

    public function show($id){
        $id = decrypt($id);
        $case = LegalCase::query()->find($id);
        return view('cases.cases-show',compact('case'));
    }

    public function appealShow($id){
        $id = decrypt($id);
        $appeal = CaseAppeal::query()->find($id);
        $case = $appeal->case;
        return view('cases.appeal-show',compact('case', 'appeal'));
    }

    public function appealsIndex(){

        return view('cases.appeals-index');
    }
}
