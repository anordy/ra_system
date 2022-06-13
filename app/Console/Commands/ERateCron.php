<?php

namespace App\Console\Commands;

use App\Http\Controllers\v1\ExchangeRateController;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ERateCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'erate:daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch exchange rates from BoT every day at 0000hrs';

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
        $xchangerate_controller = new ExchangeRateController;
        $xchangerate_controller->fetchrates();
    }
}
