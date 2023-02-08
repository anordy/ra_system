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

class WorkflowUpdateActors implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $workflow_id;
    public $place;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($workflow_id, $place)
    {
        $this->workflow_id = $workflow_id;
        $this->place = $place;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $workflow = Workflow::find($this->workflow_id);
        if ($workflow == null) {
            return;
        }

        $places = json_decode($workflow->places, true);
        $place = $places[$this->place];


        if (count($place) > 0) {
            $tasks = WorkflowTask::where('workflow_id', $workflow->id)
                ->where('from_place', $this->place)
                ->where('status', 'running')->get();

            $operators = [];
            if ($place['operator_type'] == 'role') {
                $users = User::whereIn('role_id', $place['operators'])->get();
                $operators = $users->count() > 0 ? $users->pluck('id')->toArray() : [];
            } else {
                $operators = $place['operators'];
            }

            $user_type = '';
            if ($place['owner'] == 'taxpayer') {
                $user_type = Taxpayer::class;
            } elseif ($place['owner'] == 'staff') {
                $user_type = User::class;
            }

            foreach ($tasks as $task) {
                $operators_collection = array();

                foreach ($operators as $operator) {
                    array_push(
                        $operators_collection,
                        new WorkflowTaskOperator([
                            'task_id' => $task->id,
                            'workflow_id' => $workflow->id,
                            'user_id' => $operator,
                            'user_type' => $user_type
                        ])
                    );
                }

                $task->original_operators = json_decode($place['operators']);
                $task->operators = json_decode($operators);
                $task->save();
                $task->actors()->delete();
                $task->actors()->saveMany($operators_collection);
            }
            Log::info("Workflow with id=" . $workflow->id . " and the placename=" . $this->place . " synced successfully");
        } else {
            Log::info("Workflow with id=" . $workflow->id . " and the placename=" . $this->place . " has no configured places ");
        }
    }
}
