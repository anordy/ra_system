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

        $agent = TaxAgent::findOrFail($agentId);
        if ($type == 'csv') {
            return Storage::disk('local')->response($agent->cv);
        }
        if ($type == 'passport_photo') {
            return Storage::disk('local')->response($agent->passport_photo);
        }

        if ($type == 'tin_certificate') {
            return Storage::disk('local')->response($agent->tin_certificate);
        }

        if ($type == 'emp_letter') {
            return Storage::disk('local')->response($agent->emp_letter);
        }

        if ($type == 'approval_letter') {
            return Storage::disk('local')->response($agent->approval_letter);
        }

        return abort(404);
    }

    public function getAgentAcademicFile($agentId, $type)
    {

        $academics = TaxAgentAcademicQualification::findOrFail($agentId);
        if ($type == 'academic_certificate') {
            return Storage::disk('local')->response($academics->certificate);
        }

        if ($type == 'academic_transcript') {
            return Storage::disk('local')->response($academics->transcript);
        }

        return abort(404);
    }

    public function getAgentProfessionalFile($agentId, $type)
    {
        $pro = TaxAgentProfessionals::findOrFail($agentId);
        if ($type == 'pro_certificate') {
            return Storage::disk('local')->response($pro->attachment);
        }

        return abort(404);
    }

    public function getAgentTrainingFile($agentId, $type)
    {

        $training = TaxAgentTrainingExperience::findOrFail($agentId);
        if ($type == 'tra_certificate') {
            return Storage::disk('local')->response($training->attachment);
        }

        return abort(404);
    }
}
