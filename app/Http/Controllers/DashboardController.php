<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Traits\CheckReturnConfigurationTrait;
use App\Traits\VerificationTrait;
use App\Models\Business;
use App\Models\BusinessStatus;
use App\Models\TaxAgent;
use App\Models\TaxAgentStatus;
use App\Models\Taxpayer;
use Illuminate\Support\Facades\Gate;

class DashboardController extends Controller
{
    use CheckReturnConfigurationTrait, VerificationTrait;

    public function index()
    {
        $issues = [];

        if (!Gate::allows('system-check-return-configs')) {
            $issues = $this->getMissingConfigurations();
        }

        $counts = TaxAgent::where('status', TaxAgentStatus::APPROVED)
            ->selectRaw("'taxAgents' AS type, COUNT(*) AS count")
            ->unionAll(Business::where('status', BusinessStatus::APPROVED)->selectRaw("'businesses' AS type, COUNT(*) AS count"))
            ->unionAll(User::selectRaw("'users' AS type, COUNT(*) AS count"))
            ->unionAll(Taxpayer::selectRaw("'taxpayers' AS type, COUNT(*) AS count"))
            ->pluck('count', 'type');

        return view('dashboard', compact('issues', 'counts'));
    }

}
