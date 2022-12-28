<?php

namespace App\Console\Commands\Sign;

use App\Models\Returns\TaxReturn;
use App\Traits\VerificationTrait;
use Illuminate\Console\Command;

class SignReturns extends Command
{
    use VerificationTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sign:returns {return? : The ID of the return to sign, if not specified all returns will be signed.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sign returns for verifications.';

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
        $this->info('Signing returns started.');

        if ($this->argument('return')){

            if (!is_numeric($this->argument('return'))){
                $this->error('Return ID provided is not a number.');
                return 0;
            }

            $return = TaxReturn::find($this->argument('return'));

            if (!$return){
                $this->error('Return ID not found.');
                return 0;
            }

            $this->sign($return);
            return 0;
        }

        $this->info('Return ID not provided, signing all.');

        foreach (TaxReturn::all() as $return){
            $this->info('Signing: ' . $return->id);
            $this->sign($return);
        }

        $this->info('Signing complete');

        return 0;
    }
}
