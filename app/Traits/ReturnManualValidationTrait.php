<?php

namespace App\Traits;

trait ReturnManualValidationTrait
{
    public $configs = [];

    public function manualValidation($key)
    {
        $config = $this->configs[$key];
        $first_character = substr($config['value'], 0, 1);
        $length = strlen($config['value']);
        if ($first_character == ',')
        {
            $config['value'] = 0;
            $this->configs[$key] = $config;
            return $config['value'];
        }
        if ($first_character == 0 && $length > 1)
        {
            $config['value'] = substr(str_replace('.00','0',$config['value']), 1);
            $this->configs[$key] = $config;
            return $config['value'];
        }
        else{
            $config['value'] = str_replace('.00','',$config['value']);
            $this->configs[$key] = $config;
            return $config['value'];
        }
    }

    public function manualValidationPetroleum($key, $quantityCertificateProduct)
    {
        $config = $this->configs[$key];
        $first_character = substr($config['value'], 0, 1);
        $length = strlen($config['value']);
        if ($first_character == ',')
        {
            $config['value'] = $quantityCertificateProduct ? $quantityCertificateProduct['liters_at_20'] : 0;
            $this->configs[$key] = $config;
            return $config['value'];
        }
        if ($first_character == 0 && $length > 1)
        {
            $config['value'] = $quantityCertificateProduct ? $quantityCertificateProduct['liters_at_20'] : substr($config['value'], 1); 
            $this->configs[$key] = $config;
            return $config['value'];
        } else {
            $config['value'] = $quantityCertificateProduct ? $quantityCertificateProduct['liters_at_20'] : 0;
            $this->configs[$key] = $config;
            return $config['value'];
        }
    }
 

}

