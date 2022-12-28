<?php

namespace App\Console\Commands\Sign;

use App\Models\ZmBill;
use App\Traits\VerificationTrait;
use Illuminate\Console\Command;

class SignBill extends Command
{
    use VerificationTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sign:bills {bill? : The ID of the bill to sign, if not specified all bills will be signed}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sign bills for verification.';

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
        $this->info('Signing bills started.');

        if ($this->argument('bill')){

            if (!is_numeric($this->argument('bill'))){
                $this->error('Bill ID provided is not a number.');
                return 0;
            }

            $bill = ZmBill::find($this->argument('bill'));

            if (!$bill){
                $this->error('Bill ID not found.');
                return 0;
            }

            $this->sign($bill);
            return 0;
        }

        $this->info('Bill ID not provided, signing all.');

        foreach (ZmBill::all() as $bill){
            $this->info('Signing: ' . $bill->id);
            $this->sign($bill);
        }

        $this->info('Signing complete');

        return 0;
    }
}
