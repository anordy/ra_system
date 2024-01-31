<?php

namespace App\Console\Commands;

use App\Services\Api\ApiAuthenticationService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class LoginVfmsUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vfms:login';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Login Vfms User';

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
     * @return int
     */
    public function handle()
    {
        Log::channel('vfms')->info('Login Vfms User Started!');
        if ($this->confirm('By running this command auth token for VFMSAPI user will be generated and saved to database, so it must be shared to VFMS team?')) {
//            $access_token = (new ApiAuthenticationService)->getVfmsAccessToken();
            $apiVfmsAuth = new ApiAuthenticationService();
            $access_token = $apiVfmsAuth->getVfmsAccessToken();
            $this->line('access Token: '. $access_token);
        }
        Log::channel('vfms')->info('Login Vfms User Ended!');
    }
}
