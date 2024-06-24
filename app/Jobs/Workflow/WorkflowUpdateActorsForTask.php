<?php

namespace App\Jobs\Workflow;

use App\Models\User;
use App\Models\Workflow;
use App\Models\WorkflowTask;
use App\Models\WorkflowTaskOperator;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class WorkflowUpdateActorsForTask implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $task;
    public $operators;

    public $timeout = 320;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($task, $operators) {
        $this->task = $task;
        $this->operators = $operators;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle() {
        $workflow = Workflow::find($this->task->workflow_id);
        if (!$workflow) {
            return;
        }

        $places = json_decode($workflow->places, true);
        $place = $places[$this->task->to_place];


        if (count($place) > 0) {
            $task = WorkflowTask::query()->where('workflow_id', $workflow->id)
                ->where('id', $this->task->id)
                ->where('to_place', $this->task->to_place)
                ->where('status', 'running')->first();

            $operators_collection = array();
            foreach ($this->operators as $operator) {
                $operators_collection[] = new WorkflowTaskOperator([
                    'task_id' => $task->id,
                    'workflow_id' => $workflow->id,
                    'user_id' => $operator,
                    'user_type' => User::class
                ]);
            }

            $task->original_operators = json_encode($place['operators']);
            $task->operators = json_encode($this->operators);
            $task->save();
            $task->actors()->forceDelete();
            $task->actors()->saveMany($operators_collection);

            Log::info("Workflow with id=" . $workflow->id . " and the placename=" . $this->task->to_place . " synced successfully");
        } else {
            Log::info("Workflow with id=" . $workflow->id . " and the placename=" . $this->task->to_place . " has no configured places ");
        }
    }
}
