<?php

namespace Database\Seeders;

use App\Models\Workflow;
use Illuminate\Database\Seeder;

class BusinessBranchWorkflow extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $name = 'business_branch_registration';
        $type = 'workflow';
        $marking_store = [
            'type'      => 'multiple_state',
            'property'  => ['marking']
        ];
        $initial_marking = 'apply';
        $supports =  ['App\Models\BusinessLocation'];
        $places =  [
            'apply' => [
                'owner' => 'taxpayer',
                'operator_type' => 'user',
                'operators' => []
            ],
            'correct_application' => [
                'owner' => 'taxpayer',
                'operator_type' => 'user',
                'operators' => []
            ],
            'registration_officer' => [
                'owner' => 'staff',
                'operator_type' => 'role',
                'operators' => [1, 3]
            ],
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
            'completed' => [
                'owner' => 'staff',
                'operator_type' => 'role',
                'operators' => []
            ],
        ];
        $transitions = [
            'application_submitted' => [
                'from' => 'apply',
                'to'   => 'registration_officer',
                'condition' => '',
            ],
            'registration_officer_review' => [
                'from' => 'registration_officer',
                'to'   => 'registration_manager',
                'condition' => '',
            ],
            'application_filled_incorrect' => [
                'from' => 'registration_officer',
                'to'   => 'correct_application',
                'condition' => '',
            ],
            'application_corrected' => [
                'from' => 'correct_application',
                'to'   => 'registration_officer',
                'condition' => '',
            ],
            'registration_manager_review' => [
                'from' => 'registration_manager',
                'to'   => 'director_of_trai',
                'condition' => '',
            ],
            'registration_manager_reject' => [
                'from' => 'registration_manager',
                'to'   => 'registration_officer',
                'condition' => '',
            ],
            'director_of_trai_review' => [
                'from' => 'director_of_trai',
                'to'   => 'completed',
                'condition' => '',
            ],
            'director_of_trai_reject' => [
                'from' => 'director_of_trai',
                'to'   => 'registration_manager',
                'condition' => '',
            ]
        ];

        Workflow::updateOrCreate([
            'code' => 'BUSSINESS_BRANCH_REGISTRATION',
            'summary' => 'Bussiness Branch Registration Workflow',
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
