<?php

namespace App\Console\Commands\Sign;

use App\Models\Taxpayer;
use App\Traits\VerificationTrait;
use Illuminate\Console\Command;

class SignTaxpayers extends Command
{
    use VerificationTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sign:taxpayers {taxpayer? : The ID of the taxpayer to sign, if not specified all taxpayers will be signed.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sign taxpayers.';

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
        $this->info('Signing taxpayers.');

        if ($this->argument('taxpayer')){

            if (!is_numeric($this->argument('taxpayer'))){
                $this->error('User ID provided is not a number.');
                return 0;
            }

            $taxpayer = Taxpayer::find($this->argument('taxpayer'));

            if (!$taxpayer){
                $this->error('User ID not found.');
                return 0;
            }

            $this->sign($taxpayer);
            return 0;
        }

        $this->info('Has no taxpayer, signing all.');

        foreach (Taxpayer::all() as $taxpayer){
            $this->info('Signing: ' . $taxpayer->email);
            $this->sign($taxpayer);
        }

        $this->info('Signing complete');

        return 0;
    }
}
