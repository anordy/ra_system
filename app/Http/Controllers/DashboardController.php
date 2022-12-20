<?php

namespace App\Http\Controllers;

use App\Traits\CheckReturnConfigurationTrait;

class DashboardController extends Controller
{
    use CheckReturnConfigurationTrait;

    public function index()
    {
        $all_issues = [
            ['status' => $this->doesCurrentFinancialMonthExists(), 'description' => 'Current financial month has not been configured', 'route' => 'settings.financial-months'],
            ['status' => $this->doesInterestRateExists(), 'description' => 'Current financial year interest rate has not been configured', 'route' => 'settings.interest-rates.index'],
            ['status' => $this->doesPenaltyRateExists(), 'description' => 'Current penalty rates has not been configured', 'route' => 'settings.interest-rates.index'],
        ];

        $issues = [];

        foreach ($all_issues as $issue) {
            if ($issue['status'] == false) {
                $issues[] = $issue;
            } 
        }

        return view('dashboard', compact('issues'));
    }
}
