<?php

namespace App\Http\Livewire\ReportRegister\Settings;

use App\Enum\CustomMessage;
use App\Models\ReportRegister\RgSettings;
use App\Traits\CustomAlert;
use Exception;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class BaseSettings extends Component
{
    use CustomAlert;

    public $daysToBreach;

    public function mount() {
        $breachSetting = RgSettings::select('value')->where('name', RgSettings::DAYS_TO_BREACH)->first();

        if ($breachSetting) {
            $this->daysToBreach = $breachSetting->value;
        } else {
            $setting = RgSettings::create(
                [
                    'name' => RgSettings::DAYS_TO_BREACH,
                    'value' => 1
                ]
            );

            if (!$setting) throw new Exception('Failed to save setting');
        }
    }

    protected function rules()
    {
        return [
            'daysToBreach' => 'required|integer|min:0|max:30',
        ];
    }

    public function submit()
    {
        $this->validate();

        try {

            $setting = RgSettings::updateOrCreate(
                [
                    'name' => RgSettings::DAYS_TO_BREACH,
                ],
                [
                    'name' => RgSettings::DAYS_TO_BREACH,
                    'value' => $this->daysToBreach
                ]
            );

            if (!$setting) throw new Exception('Failed to save setting');

            $this->customAlert('success', 'Settings has been updated');
        } catch (Exception $e) {
            Log::error('REPORT-REGISTER-SETTINGS-BASE-SETTING-CREATE', [$e]);
            $this->customAlert('error', CustomMessage::error());
        }
    }


    public function render()
    {
        return view('livewire.report-register.settings.base');
    }

}
