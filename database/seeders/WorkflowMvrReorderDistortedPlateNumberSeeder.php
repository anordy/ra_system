<?php

namespace Database\Seeders;

use App\Models\Workflow;
use Illuminate\Database\Seeder;

class WorkflowMvrReorderDistortedPlateNumberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $name = 'mvr_reorder_distorted_plate_number';
        $type = 'workflow';
        $marking_store = [
            'type'      => 'multiple_state',
            'property'  => ['marking']
        ];
        $initial_marking = 'apply';
        $supports =  ['App\Models\MvrReorderPlateNumber'];
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
            'mvr_police' => [
                'owner' => 'staff',
                'operator_type' => 'role',
                'operators' => [1, 2, 3],
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
                'to' => 'mvr_police',
                'condition' => '',
            ],
            'application_filled_incorrect' => [
                'from' => 'mvr_police',
                'to' => 'correct_application',
                'condition' => '',
            ],
            'application_corrected' => [
                'from' => 'correct_application',
                'to' => 'mvr_police',
                'condition' => '',
            ],
            'mvr_police_review' => [
                'from' => 'mvr_police',
                'to' => 'mvr_zartsa',
                'condition' => '',
            ],
            'mvr_zartsa_reject' => [
                'from' => 'mvr_zartsa',
                'to' => 'mvr_police',
                'condition' => '',
            ],
            'mvr_zartsa_review' => [
                'from' => 'mvr_zartsa',
                'to' => 'mvr_registration_officer',
                'condition' => '',
            ],
            'mvr_registration_officer_reject' => [
                'from' => 'mvr_registration_officer',
                'to' => 'mvr_zartsa',
                'condition' => '',
            ],
            'mvr_registration_officer_review' => [
                'from' => 'mvr_registration_officer',
                'to' => 'mvr_registration_manager',
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
                'code' => 'MVR_REORDER_DISTORTED_PLATE_NUMBER',
            ],
            [
            'code' => 'MVR_REORDER_DISTORTED_PLATE_NUMBER',
            'summary' => 'Motor Vehicle Reorder Distorted Plate Number',
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
