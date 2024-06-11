<?php

namespace App\Jobs\Workflow;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\DB;
use App\Traits\WorkflowProcesssingTrait;
use Illuminate\Support\Facades\Log;

class ForwardApplicationStateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, WorkflowProcesssingTrait;

    protected $modelName;
    protected $modelId;
    protected $transition;

    public function __construct($modelName, $modelId, $transition)
    {
        $this->modelName = $modelName;
        $this->modelId   = decrypt($modelId);
        $this->transition = $transition;
        $this->registerWorkflow($modelName, $this->modelId);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        DB::beginTransaction();

        try {
            $this->doTransition($this->transition, $this->subject->comments);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to forward application state: ' . $e->getMessage());
        }
    }
}
