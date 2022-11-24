<?php

namespace App\Http\Controllers\TaxAgents;

use App\Http\Controllers\Controller;
use App\Models\TaxAgent;
use App\Models\TaxAgentAcademicQualification;
use App\Models\TaxAgentProfessionals;
use App\Models\TaxAgentTrainingExperience;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TaxAgentFileController extends Controller
{
    public function getAgentFile($agentId, $type)
    {

        $agent = TaxAgent::find($agentId);
        if ($type == 'csv') {
            return Storage::disk('local-admin')->response($agent->cv);
        }
        if ($type == 'passport_photo') {
            return Storage::disk('local-admin')->response($agent->passport_photo);
        }

        if ($type == 'tin_certificate') {
            return Storage::disk('local-admin')->response($agent->tin_certificate);
        }

        if ($type == 'emp_letter') {
            return Storage::disk('local-admin')->response($agent->emp_letter);
        }

        if ($type == 'approval_letter') {
            return Storage::disk('local-admin')->response($agent->approval_letter);
        }

        return abort(404);
    }

    public function getAgentAcademicFile($agentId, $type)
    {

        $academics = TaxAgentAcademicQualification::find($agentId);
        if ($type == 'academic_certificate') {
            return Storage::disk('local-admin')->response($academics->certificate);
        }

        if ($type == 'academic_transcript') {
            return Storage::disk('local-admin')->response($academics->transcript);
        }

        return abort(404);
    }

    public function getAgentProfessionalFile($agentId, $type)
    {
        $pro = TaxAgentProfessionals::find($agentId);
        if ($type == 'pro_certificate') {
            return Storage::disk('local-admin')->response($pro->attachment);
        }

        return abort(404);
    }

    public function getAgentTrainingFile($agentId, $type)
    {

        $training = TaxAgentTrainingExperience::find($agentId);
        if ($type == 'tra_certificate') {
            return Storage::disk('local-admin')->response($training->attachment);
        }

        return abort(404);
    }
}
