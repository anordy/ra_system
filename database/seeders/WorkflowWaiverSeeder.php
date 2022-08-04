<?php

namespace Database\Seeders;

use App\Models\Workflow;
use Illuminate\Database\Seeder;

class WorkflowWaiverSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $name = 'waiver';
        $type = 'workflow';
        $marking_store = [
            'type' => 'multiple_state',
            'property' => ['marking'],
        ];
        $initial_marking = 'apply';
        $supports = ['App\Models\Waiver'];
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
            'waiver_manager' => [
                'owner' => 'staff',
                'operator_type' => 'role',
                'operators' => [1, 2, 3],
            ],
            'chief_assurance' => [
                'owner' => 'staff',
                'operator_type' => 'role',
                'operators' => [1, 2, 3],
            ],
                'Commissioner' => [
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
                'to' => 'waiver_manager',
                'condition' => '',
            ],
            'waiver_manager_review' => [
                'from' => 'waiver_manager',
                'to' => 'chief_assurance',
                'condition' => '',
            ],
            'chief_assurance_review' => [
                'from' => 'chief_assurance',
                'to' => 'Commissioner',
                'condition' => '',
            ],
            'application_filled_incorrect' => [
                'from' => 'waiver_manager',
                'to' => 'correct_application',
                'condition' => '',
            ],
            'application_corrected' => [
                'from' => 'correct_application',
                'to' => 'waiver_manager',
                'condition' => '',
            ],
        ];

        Workflow::updateOrCreate([
            'code' => 'WAIVER',
            'summary' => 'Waiver Workflow',
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
