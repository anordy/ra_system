<?php

namespace App\Console\Commands\Env;

use Illuminate\Console\Command;
use App\Services\EncryptEnv\Action\Encrypt;
use Illuminate\Encryption\Encrypter;
use Illuminate\Support\Facades\App;

class EncryptEnvValues extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'env:encrypt {configkey?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Encrypts flagged environment/config variable values in file defined in envsecure.php';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return boolean
     */
    public function handle()
    {
        $encrypter = new Encrypt;

        $configkey = $this->argument('configkey');
        $configkey = !empty($configkey) ? $configkey : $this->ask('Config Key ('.$encrypter->getKeySize().' char key)');

        $generated_key = false;

        if ($configkey === 'generate-key') {
            $cipher = $encrypter->getKeySize() === 16  ? 'AES-128-CBC' : ($encrypter->getKeySize() === 32 ? 'AES-256-CBC' : null);
            if (!empty($cipher)) {

                $configkey = substr(str_replace('/', '', base64_encode(Encrypter::generateKey($cipher))),0, $encrypter->getKeySize());

                if (!empty($configkey)) {
                    $generated_key = true;
                } else {
                    $this->error('Command Failed: An unknown problem occurred trying to generate a new config key!');
                    return false;
                }

            } else {

                $this->error('Command Failed:  An encryption cipher is either not defined in '.config_path('envsecure.php').' or the cipher specified is not supported.');
                return false;

            }
        }

        $encrypter->setConfigKey($configkey);

        $doencrypt = $encrypter->encryptenvfile();

        if (!$doencrypt['result']) {
            $this->error($doencrypt['error']);
            return false;

        } else {
            $this->info('Encryption complete.');
            if ($generated_key) {
                $this->info("Your new generated CONFIGKEY is: $configkey, your .env file was update with this key.");
                $this->writeNewEnvironmentFileWith($configkey);
                $this->warn('DO NOT lose this key if you want to use the encrypted config values you just encrypted!');
            }
            return true;
        }
    }

    /**
     * Write a new environment file with the given key.
     *
     * @param  string  $key
     * @return void
     */
    protected function writeNewEnvironmentFileWith($key)
    {
        if (env('CONFIGKEY')){
            file_put_contents(App::environmentFile(), preg_replace(
                    $this->keyReplacementPattern(),
                    'CONFIGKEY='.$key,
                    file_get_contents(App::environmentFile())
                ));
        } else {
            $contents = file_get_contents(App::environmentFile());
            $contents .= "\nCONFIGKEY=.$key";
            file_put_contents(App::environmentFile(), $contents);
        }
    }

    /**
     * Get a regex pattern that will match env APP_KEY with any random key.
     *
     * @return string
     */
    protected function keyReplacementPattern(): string
    {
        $escaped = preg_quote('='.config('app.configkey'), '/');

        return "/^CONFIGKEY{$escaped}/m";
    }
}
