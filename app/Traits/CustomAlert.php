<?php

namespace App\Traits;

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
            $options['timer'] = 60000; //60 seconds
        }

        $this->alert($type, $message, $options);
    }
}
