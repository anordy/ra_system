<?php

namespace App\Traits;

use App\Enum\AlertConfig;
use Jantinnerezo\LivewireAlert\LivewireAlert;

trait CustomAlert
{
    use LivewireAlert;

    public function customAlert(string $type = 'success', string $message = '', array $options = [])
    {
        $options = array_merge([
            'showCloseButton' => true,
        ], $options);

        if ($type != 'success') {
            $options['timer'] = AlertConfig::ERROR_ALERT_DURATION;
        }

        $this->alert($type, $message, $options);
    }
}
