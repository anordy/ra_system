<?php

namespace App\Http\Controllers;

use App\Models\SystemSetting;
use App\Models\SystemSettingCategory;
use App\Models\User;
use App\Traits\CheckReturnConfigurationTrait;
use App\Traits\VerificationTrait;
use App\Models\Business;
use App\Models\BusinessStatus;
use App\Models\TaxAgent;
use App\Models\TaxAgentStatus;
use App\Models\Taxpayer;
use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;

class DashboardController extends Controller
{
    use CheckReturnConfigurationTrait, VerificationTrait;

    public function index()
    {
        $issues = [];

        $setting = SystemSetting::query()
            ->firstOrCreate([
                'code' => SystemSetting::LAST_CONFIGURATIONS_CHECK,
            ], [
                'name' => 'Last configurations check',
                'description' => 'Last configurations check',
                'code' => SystemSetting::LAST_CONFIGURATIONS_CHECK,
                'unit' => 'date',
                'value' => Carbon::now(),
                'system_setting_category_id' => SystemSettingCategory::where('code', SystemSettingCategory::OTHER)->firstOrFail()->id,
            ]);

        if (!Gate::allows('system-check-return-configs')) {
            if (Carbon::now()->greaterThan(Carbon::parse($setting->value)->addHours(12))){
                $issues = $this->getMissingConfigurations();
                $setting->update(['value' => Carbon::now()->toDateTimeString()]);
            }
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
