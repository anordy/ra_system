<?php

namespace Database\Seeders;

use App\Models\Workflow;
use Illuminate\Database\Seeder;

class WorkflowInternalBusinessInfoChangeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $name = 'internal_business_information_change';
        $type = 'workflow';
        $marking_store = [
            'type'      => 'multiple_state',
            'property'  => ['marking']
        ];
        $initial_marking = 'apply';
        $supports =  ['App\Models\InternalBusinessUpdate'];
        $places =  [
            'registration_manager' => [
                'owner' => 'staff',
                'operator_type' => 'role',
                'operators' => [1, 2]
            ],
            'director_of_trai' => [
                'owner' => 'staff',
                'operator_type' => 'role',
                'operators' => [1, 6]
            ],
            'cdt' => [
                'owner' => 'staff',
                'operator_type' => 'role',
                'operators' => [1, 6]
            ],
            'completed' => [
                'owner' => 'staff',
                'operator_type' => 'role',
                'operators' => []
            ],
        ];
        $transitions = [
            'registration_manager_review' => [
                'from' => 'registration_manager',
                'to'   => 'director_of_trai',
                'condition' => '',
            ],
            'director_of_trai_review' => [
                'from' => 'director_of_trai',
                'to'   => 'cdt',
                'condition' => '',
            ],
            'director_of_trai_reject' => [
                'from' => 'director_of_trai',
                'to'   => 'registration_manager',
                'condition' => '',
            ],
            'cdt_review' => [
                'from' => 'cdt',
                'to'   => 'completed',
                'condition' => '',
            ],
            'cdt_reject' => [
                'from' => 'cdt',
                'to'   => 'director_of_trai',
                'condition' => '',
            ]
        ];

        Workflow::updateOrCreate([
            'code' => 'INTERNAL_BUSINESS_INFORMATION_CHANGE',
            'summary' => 'Internal Business Information Change Workflow',
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
