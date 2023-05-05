<?php

namespace App\Console\Commands\Sign;

use App\Models\User;
use App\Traits\VerificationTrait;
use Illuminate\Console\Command;

class SignUsers extends Command
{
    use VerificationTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sign:users {user? : The ID of the user to sign, if not specified all users will be signed.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sign users for verification.';

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
        $this->info('Started signing users.');

        if ($this->argument('user')){

            if (!is_numeric($this->argument('user'))){
                $this->error('User ID provided is not a number.');
                return 0;
            }

            $user = User::find($this->argument('user'));

            if (!$user){
                $this->error('User ID not found.');
                return 0;
            }

            $this->line('Signing: ' . $user->full_name);
            if ($this->sign($user)){
                $this->info("Signing {$user->full_name} completed.");
            } else {
                $this->error("Signing {$user->full_name} failed.");
            }

            return 0;
        }

        $this->info('User ID not provided, signing all.');

        foreach (User::all() as $user){
            $this->line('Signing: ' . $user->full_name);
            if ($this->sign($user)){
                $this->info("Signing {$user->full_name} completed.");
            } else {
                $this->error("Signing {$user->full_name} failed.");
            }
        }

        $this->info('Signing complete');

        return 0;
    }
}
