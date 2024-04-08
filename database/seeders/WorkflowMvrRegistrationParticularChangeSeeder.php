<?php

namespace Database\Seeders;

use App\Models\Workflow;
use Illuminate\Database\Seeder;

class WorkflowMvrRegistrationParticularChangeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $name = 'mvr_registration_particular_change';
        $type = 'workflow';
        $marking_store = [
            'type'      => 'multiple_state',
            'property'  => ['marking']
        ];
        $initial_marking = 'apply';
        $supports =  ['App\Models\MvrRegistrationParticularChange'];

        $places = [
            'apply' => [
                'owner' => 'taxpayer',
                'operator_type' => 'user',
                'operators' => [],
            ],
            'application_rejected' => [
                'owner' => 'taxpayer',
                'operator_type' => 'user',
                'operators' => [],
            ],
            'mvr_zartsa' => [
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
                'to' => 'mvr_zartsa',
                'condition' => '',
            ],
            'application_rejected' => [
                'from' => 'mvr_zartsa',
                'to' => 'application_rejected',
                'condition' => '',
            ],
            'mvr_zartsa_review' => [
                'from' => 'mvr_zartsa',
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
                'to' => 'mvr_zartsa',
                'condition' => '',
            ],
            'mvr_registration_manager_reject' => [
                'from' => 'mvr_registration_manager',
                'to' => 'mvr_registration_officer',
                'condition' => '',
            ],
            'mvr_registration_manager_review' => [
                'from' => 'mvr_registration_manager',
                'to' => 'completed',
                'condition' => '',
            ],
        ];


        Workflow::updateOrCreate(
            [
                'code' => 'MVR_REGISTRATION_PARTICULAR_CHANGE'
            ],
            [
            'code' => 'MVR_REGISTRATION_PARTICULAR_CHANGE',
            'summary' => 'Motor Vehicle Registration Particular Change',
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
