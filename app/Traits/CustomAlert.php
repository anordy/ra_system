<?php

namespace App\Traits;

use App\Enum\AlertConfig;
use App\Enum\GeneralConstant;
use App\Models\MvrPlateNumberStatus;
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

    public function customConfirm($message, $callback, $data){
        $this->customAlert(GeneralConstant::QUESTION, $message, [
            'position' => 'center',
            'toast' => false,
            'showConfirmButton' => true,
            'confirmButtonText' => 'Yes',
            'onConfirmed' => $callback,
            'showCancelButton' => true,
            'cancelButtonText' => 'No, Cancel',
            'timer' => null,
            'data' => $data,
        ]);
    }
}
