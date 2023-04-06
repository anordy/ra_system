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
        
        $options['timer']=30000; //30 seconds

        $this->alert($type,$message,$options);
    }
}
