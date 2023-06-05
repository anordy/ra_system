<?php

namespace App\Console\Commands;

use App\Enum\AssistantStatus;
use App\Models\Business;
use App\Models\BusinessAssistant;
use App\Models\Taxpayer;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class NormalizeAssistants extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'normalize:assistants';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Normalize assistants from 1:1 to 1:N relation';

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
        DB::beginTransaction();
        try {
            $this->line('Starting');
            foreach (Business::all() as $business){
                $this->line($business->name);
                // Check if taxpayer_id and responsible person id do not match
                if ($business->taxpayer_id != $business->responsible_person_id){
                    $assistant = Taxpayer::find($business->responsible_person_id);
                    $this->info("We will have to create a new record for {$assistant->first_name}");
                    BusinessAssistant::updateOrCreate([
                        'business_id' => $business->id,
                        'taxpayer_id' => $assistant->id,
                        'assigned_at' => $business->created_at
                    ], [
                        'business_id' => $business->id,
                        'taxpayer_id' => $assistant->id,
                        'added_by_type' => get_class(new Taxpayer()),
                        'added_by_id' => $assistant->id,
                        'status' => AssistantStatus::ACTIVE,
                        'assigned_at' => $business->created_at
                    ]);
                } else {
                    $this->info('We are good.');
                }
            }
            $this->info('Complete');
            DB::commit();
        } catch (\Exception $exception){
            DB::rollBack();
            $this->error($exception->getMessage());
        }
        return 0;
    }
}
