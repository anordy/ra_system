<?php

namespace App\Console\Commands;

use App\Models\DlDriversLicense;
use Carbon\Carbon;
use Illuminate\Console\Command;

class MarkExpiredDlLicenses extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dl:expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mark expired driver licenses';

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
        $licenses = DlDriversLicense::select('id', 'status', 'expiry_date')
            ->where('status', DlDriversLicense::ACTIVE)
            ->where('expiry_date', '<=', Carbon::now())
            ->get();

        foreach ($licenses as $license) {
            $license->status = DlDriversLicense::STATUS_EXPIRED;
            $license->save();
        }

        return 1;
    }
}
