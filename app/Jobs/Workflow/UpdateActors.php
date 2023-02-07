<?php

namespace App\Jobs\Workflow;

use App\Models\User;
use App\Models\WorkflowTask;
use App\Models\WorkflowTaskOperator;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateActors implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $user_id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user_id)
    {
        $this->user_id = $user_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $user = User::find($this->user_id);
        if ($user == null) {
            return;
        }

        $role_id = $user->role_id;

        $tasks = WorkflowTask::where('status', 'running')->get();

        foreach ($tasks as $row) {
            $actors = [];
            if ($row->operator_type == 'role') {
                $actors = json_decode($row->original_operators);
                if (gettype($actors) != "array") {
                    $actors = [];
                }
            }

            if (in_array($role_id, $actors)) {
                if ($row->owner == 'taxpayer') {
                    $user_type = Taxpayer::class;
                } elseif ($row->owner == 'staff') {
                    $user_type = User::class;
                } else {
                    return;
                }

                $data =  new WorkflowTaskOperator([
                    'task_id' => $row->id,
                    'workflow_id' => $row->workflow_id,
                    'user_id' => $user->id,
                    'user_type' => $user_type
                ]);

                $data->save();
            }
        }
    }
}
