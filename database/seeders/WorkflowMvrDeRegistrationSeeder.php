<?php

namespace Database\Seeders;

use App\Models\Workflow;
use Illuminate\Database\Seeder;

class WorkflowMvrDeRegistrationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $name = 'mvr_deregistration';
        $type = 'workflow';
        $marking_store = [
            'type'      => 'multiple_state',
            'property'  => ['marking']
        ];
        $initial_marking = 'apply';
        $supports =  ['App\Models\MvrDeregistration'];
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
            'mvr_police_officer' => [
                'owner' => 'staff',
                'operator_type' => 'role',
                'operators' => [1, 2, 3],
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
            'zbs_officer' => [
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
                'to' => 'mvr_police_officer',
                'condition' => '',
            ],
            'application_filled_incorrect' => [
                'from' => 'mvr_police_officer',
                'to' => 'correct_application',
                'condition' => '',
            ],
            'application_corrected' => [
                'from' => 'correct_application',
                'to' => 'mvr_police_officer',
                'condition' => '',
            ],
            'mvr_police_officer_review' => [
                'from' => 'mvr_police_officer',
                'to' => 'mvr_registration_officer',
                'condition' => '',
            ],
            'mvr_registration_officer_review' => [
                'from' => 'mvr_registration_officer',
                'to' => 'mvr_registration_manager',
                'condition' => '',
            ],
            'mvr_registration_officer_reject' => [
                'from' => 'mvr_registration_officer',
                'to' => 'mvr_police_officer',
                'condition' => '',
            ],
            'mvr_registration_manager_review' => [
                'from' => 'mvr_registration_manager',
                'to' => 'zbs_officer',
                'condition' => '',
            ],
            'zbs_officer_review' => [
                'from' => 'zbs_officer',
                'to' => 'completed',
                'condition' => '',
            ],
            'zbs_officer_reject' => [
                'from' => 'zbs_officer',
                'to' => 'mvr_registration_manager',
                'condition' => '',
            ],
            'mvr_registration_manager_reject' => [
                'from' => 'mvr_registration_manager',
                'to' => 'mvr_registration_officer',
                'condition' => '',
            ],
        ];


        Workflow::updateOrCreate(
            [
                'code' => 'MVR_DEREGISTRATION'
            ],
            [
            'code' => 'MVR_DEREGISTRATION',
            'summary' => 'Motor Vehicle Deregistration',
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
