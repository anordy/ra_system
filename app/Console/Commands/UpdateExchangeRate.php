<?php

namespace App\Console\Commands;

use App\Models\ExchangeRate;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UpdateExchangeRate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:exchangerates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update exchange rate from BOT';

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
        Log::channel('dailyJobs')->info('Daily Save exchange rates from bot api start');
        DB::beginTransaction();
        $this->saveExchangeRate();
        DB::commit();
        Log::channel('dailyJobs')->info('Daily Save exchange rates from bot api ended');
    }

    public function saveExchangeRate()
    {
        try {
            $request_url = config('modulesconfig.botexapi');

            $client = new \GuzzleHttp\Client();
            $response = $client->request('GET', $request_url, [
                'headers' => ['Accept' => 'application/xml'],
                'timeout' => 120
            ])->getBody()->getContents();

            $responseXml = simplexml_load_string($response);
            $json = json_encode($responseXml );
            $phpDataArray = json_decode($json,TRUE); 
            $latestExchangeRate = $phpDataArray['UsdRate'][0];

            ExchangeRate::create([
              'currency' => 'USD',
              'mean' => $latestExchangeRate['Mean'],
              'spot_buying' => $latestExchangeRate['SpotBuying'],
              'spot_selling' => $latestExchangeRate['SpotSelling'],
              'exchange_date' => $latestExchangeRate['ExchangeDate'],
            ]);

        } catch (\Throwable $ex) {
            Log::error('BOT EXCHANGE RATE CALLBACK Error: ' . $ex . "\n");
            return $ex->getMessage();
        }
    }
}
