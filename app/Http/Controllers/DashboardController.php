<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\BusinessStatus;
use App\Models\TaxAgent;
use App\Models\TaxAgentStatus;
use App\Models\Taxpayer;
use App\Models\User;
use App\Traits\CheckReturnConfigurationTrait;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    use CheckReturnConfigurationTrait;

    public function index()
    {
        $all_issues = [
            ['status' => $this->doesCurrentFinancialMonthExists(), 'description' => 'Current financial month has not been configured', 'route' => 'settings.financial-months'],
            ['status' => $this->doesInterestRateExists(), 'description' => 'Current financial year interest rate has not been configured', 'route' => 'settings.interest-rates.index'],
            ['status' => $this->doesPenaltyRateExists(), 'description' => 'Current penalty rates has not been configured', 'route' => 'settings.penalty-rates.index'],
        ];

        $temp_issues = [];

        foreach ($all_issues as $issue) {
            if ($issue['status'] == false) {
                $temp_issues[] = $issue;
            }
        }

        $issues = array_merge($temp_issues, $this->doesExchangeRateExists());

        $counts = TaxAgent::where('status', TaxAgentStatus::APPROVED)
            ->selectRaw("'taxAgents' AS type, COUNT(*) AS count")
            ->unionAll(Business::where('status', BusinessStatus::APPROVED)->selectRaw("'businesses' AS type, COUNT(*) AS count"))
            ->unionAll(User::selectRaw("'users' AS type, COUNT(*) AS count"))
            ->unionAll(Taxpayer::selectRaw("'taxpayers' AS type, COUNT(*) AS count"))
            ->pluck('count', 'type');

        return view('dashboard', compact('issues', 'counts'));
    }
}
