<?php

namespace App\Console\Commands;

use App\Traits\Queries\SalesTrait;
use App\Traits\Queries\ShowReturnTrait;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class DailyCreditReturnsCommand extends Command
{
    use SalesTrait, ShowReturnTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'daily:credits';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Daily Credit Returns';

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
        Log::channel('creditReturns')->info('Daily Credit Return collection process started');
        $hotel_returns = $this->getAllHotelSales()['return'];
        $vat_returns = $this->getAllVatSales()['return'];
        $stamp_duty_returns = $this->getAllStampDutySales()['return'];
        $vat_tax_type_id = $this->getAllVatSales()['vat_tax_type_id'];
        $stamp_tax_type_id = $this->getAllStampDutySales()['stamp_duty_tax_type_id'];
        $hotel_tax_type_id = $this->getAllHotelSales()['hotel_tax_type_id'];
        Log::channel('creditReturns')->info('Daily Credit Return collection ended');
    }
}
