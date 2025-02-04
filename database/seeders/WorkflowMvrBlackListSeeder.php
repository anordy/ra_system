<?php

namespace Database\Seeders;

use App\Models\MvrBlacklist;
use App\Models\Workflow;
use Illuminate\Database\Seeder;

class WorkflowMvrBlackListSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $name = 'mvr_blacklist';
        $type = 'workflow';
        $marking_store = [
            'type'      => 'multiple_state',
            'property'  => ['marking']
        ];
        $initial_marking = 'apply';
        $supports =  [MvrBlacklist::class];
        $places = [
            'apply' => [
                'owner' => 'staff',
                'operator_type' => 'role',
                'operators' => [],
            ],
            'mvr_registration_officer' => [
                'owner' => 'staff',
                'operator_type' => 'role',
                'operators' => [1, 2, 3],
            ],
            'mvr_registration_manager' => [
                'owner' => 'staff',
                'operator_type' => 'role',
                'operators' => [1, 2, 3],
            ],
            'zartsa_officer' => [
                'owner' => 'staff',
                'operator_type' => 'role',
                'operators' => [1, 2, 3],
            ],
            'zartsa_manager' => [
                'owner' => 'staff',
                'operator_type' => 'role',
                'operators' => [1, 2, 3],
            ],
            'rejected' => [
                'owner' => 'staff',
                'operator_type' => 'role',
                'operators' => [],
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
                'to' => 'zartsa_manager',
                'condition' => '',
            ],
            'zartsa_officer_correct' => [
                'from' => 'zartsa_officer',
                'to' => 'zartsa_manager',
                'condition' => '',
            ],
            'zartsa_manager_review' => [
                'from' => 'zartsa_manager',
                'to' => 'completed',
                'condition' => '',
            ],
            'zartsa_manager_reject' => [
                'from' => 'zartsa_manager',
                'to' => 'zartsa_officer',
                'condition' => '',
            ],
        ];

        Workflow::updateOrCreate(
            [
                'code' => 'MVR_BLACKLIST',
            ],
            [
            'code' => 'MVR_BLACKLIST',
            'summary' => 'Motor Vehicle Blacklist',
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
