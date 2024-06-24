<?php

namespace App\Console\Commands;

use App\Models\Taxpayer;
use App\Models\User;
use App\Notifications\DatabaseNotification;
use Illuminate\Console\Command;

class simulateNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'simulate:notification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will simulate notification';

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
        $leaseOfficers = User::whereHas('role.permissions', function ($query) {
            $query->where('name', 'land-lease-notification');
        })->get();

        foreach ($leaseOfficers as $leaseOfficer) {
            $leaseOfficer->notify(new DatabaseNotification(
                $subject = 'Land Lease Approve Notification',
                $message = "Land Lease has been initiates by test",
                $href = 'land-lease.list',
            ));
        }
        dd('sent');
    }
}
