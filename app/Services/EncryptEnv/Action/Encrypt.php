<?php

namespace App\Services\EncryptEnv\Action;

use App\Services\EncryptEnv\Entity\ConfigFile;
use App\Services\EncryptEnv\Entity\EncryptPayload;
use App\Services\EncryptEnv\File\ArrayFileWriter;
use App\Services\EncryptEnv\File\EnvFileWriter;
use Illuminate\Support\Facades\Config;

class Encrypt
{
    /**
     * @var string
     */
    protected $configkey;


    /**
     * @var string
     */
    protected $configfile;


    /**
     * @var string
     */
    protected $cipher;


    /**
     * @var string
     */
    protected $encrypt_flag;

    /**
     * @var integer
     */
    protected $keysize;


    public function __construct()
    {
        $cipher = Config::get('envsecure.cipher');
        !empty($cipher) && $this->cipher = $cipher;

        $encrypt_flag = Config::get('envsecure.encrypt_flag');
        !empty($encrypt_flag) && $this->encrypt_flag = $encrypt_flag;

        $this->keysize = !empty($this->cipher) && $this->cipher === 'AES-128-CBC' ? 16 : ($this->cipher === 'AES-256-CBC' ? 32 : 0);

        $this->configfile = new ConfigFile;
    }

    /**
     * Start Config File Encryption Sequence
     *
     * @return array
     */
    public function encryptenvfile()
    {
        if (empty($this->encrypt_flag) || $this->encrypt_flag === 'ENC:') {

            return ['result' => false, 'error' => 'Command Failed:  An encrypt flag is either not defined in ' . config_path('envsecure.php') . ' or it is set to "ENC:" which cannot be used.'];
        } elseif ($this->keysize >= 16) {

            //Check if configkey is valid
            if (
                empty($this->configkey) || ($this->cipher != 'AES-128-CBC' && $this->cipher != 'AES-256-CBC') ||
                (($this->cipher === 'AES-128-CBC' && strlen($this->configkey) !== 16) || ($this->cipher === 'AES-256-CBC' && strlen($this->configkey) !== 32))
            ) {
                return ['result' => false, 'error' => 'Command Failed:  This configkey provided is not valid. You must enter a valid ' . $this->keysize . ' char key.'];
            }

            $envfile_path = $this->configfile->getConfigPath();

            if (!file_exists($envfile_path)) {

                return ['result' => false, 'error' => 'Command Failed:  Config File ' . $this->configfile->getConfigPath() . ' does not exist.'];
            }

            if ($this->configfile->getOutputFormat() == 'env') {

                return (new EnvFileWriter((new EncryptPayload($this->configkey, $this->cipher, $this->encrypt_flag, $this->configfile))))->writeFile();
            } elseif ($this->configfile->getOutputFormat() == 'array') {

                if (empty($this->configfile->getFilename())) {

                    return ['result' => false, 'error' => 'Command Failed:  Config File Output Format is set to array but no custom config file is defined in ' . config_path('envsecure.php')];
                }

                return (new ArrayFileWriter((new EncryptPayload($this->configkey, $this->cipher, $this->encrypt_flag, $this->configfile))))->writeFile();
            } else {

                return ['result' => false, 'error' => 'Command Failed:  Config File Output Format is either not defined in ' . config_path('envsecure.php') . ' or the format specified is not supported.'];
            }
        } else {

            return ['result' => false, 'error' => 'Command Failed:  An encryption cipher is either not defined in ' . config_path('envsecure.php') . ' or the cipher specified is not supported.'];
        }
    }

    /**
     * Set Config Key
     *
     * @return void
     */
    public function setConfigKey($configkey)
    {
        !empty($configkey) && $this->configkey = $configkey;
    }

    /**
     * Get Config Key size
     *
     * @return integer
     */
    public function getKeySize()
    {
        return $this->keysize;
    }
}
