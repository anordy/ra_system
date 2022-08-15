<?php

namespace Database\Seeders;

use App\Models\Workflow;
use Illuminate\Database\Seeder;

class WorkflowDisputeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $name = 'dispute';
        $type = 'workflow';
        $marking_store = [
            'type' => 'multiple_state',
            'property' => ['marking'],
        ];
        $initial_marking = 'apply';
        $supports = ['App\Models\Disputes\Dispute'];
        $places = [
            'apply' => [
                'owner' => 'taxpayer',
                'operator_type' => 'user',
                'operators' => [],
            ],
            'correct_application' => [
                'owner' => 'taxpayer',
                'operator_type' => 'user',
                'operators' => [],
            ],
            'objection_manager' => [
                'owner' => 'staff',
                'operator_type' => 'role',
                'operators' => [1, 2, 3],
            ],
            'chief_assurance' => [
                'owner' => 'staff',
                'operator_type' => 'role',
                'operators' => [1, 2, 3],
            ],
            'commisioner' => [
                'owner' => 'staff',
                'operator_type' => 'role',
                'operators' => [1, 2, 3],
            ],
            'completed' => [
                'owner' => 'staff',
                'operator_type' => 'role',
                'operators' => [],
            ],
        ];
        $transitions = [
            'application_submitted' => [
                'from' => 'apply',
                'to' => 'objection_manager',
                'condition' => '',
            ],
            'objection_manager_review' => [
                'from' => 'objection_manager',
                'to' => 'chief_assurance',
                'condition' => '',
            ],
            'application_filled_incorrect' => [
                'from' => 'objection_manager',
                'to' => 'correct_application',
                'condition' => '',
            ],
            'application_corrected' => [
                'from' => 'correct_application',
                'to' => 'objection_manager',
                'condition' => '',
            ],
            'chief_assurance_review' => [
                'from' => 'chief_assurance',
                'to' => 'commisioner',
                'condition' => '',
            ],
            'chief_assurance_reject' => [
                'from' => 'chief_assurance',
                'to' => 'objection_manager',
                'condition' => '',
            ],
            'commisioner_reject' => [
                'from' => 'commisioner',
                'to' => 'chief_assurance',
                'condition' => '',
            ],
            'commisioner_review' => [
                'from' => 'commisioner',
                'to' => 'completed',
                'condition' => '',
            ],

        ];

        Workflow::updateOrCreate([
            'code' => 'DISPUTE',
            'summary' => 'Dispute Workflow',
            'name' => $name,
            'type' => $type,
            'initial_marking' => $initial_marking,
            'marking_store' => json_encode($marking_store),
            'supports' => $supports[0],
            'places' => json_encode($places),
            'transitions' => json_encode($transitions),
        ]);

    }

}
