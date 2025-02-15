<?php

namespace Database\Seeders;

use App\Models\Workflow;
use Illuminate\Database\Seeder;

class WorkflowMvrReorderPlateNumberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $name = 'mvr_reorder_plate_number';
        $type = 'workflow';
        $marking_store = [
            'type'      => 'multiple_state',
            'property'  => ['marking']
        ];
        $initial_marking = 'apply';
        $supports =  ['App\Models\MvrReorderPlateNumber'];
        $places =  [
           'apply' => [
                'owner' => 'taxpayer',
                'operator_type' => 'user',
                'operators' => [],
            ],
            'zartsa_officer' => [
                'owner' => 'staff',
                'operator_type' => 'role',
                'operators' => [1,2,3]
            ],
            'zra_officer_distorted' => [
                'owner' => 'staff',
                'operator_type' => 'role',
                'operators' => [1,2,3]
            ],
            'zra_officer_lost' => [
                'owner' => 'staff',
                'operator_type' => 'role',
                'operators' => [1,2,3]
            ],
            'police_officer' => [
                'owner' => 'staff',
                'operator_type' => 'role',
                'operators' => [1,2,3]
            ],
            'completed' => [
                'owner' => 'staff',
                'operator_type' => 'role',
                'operators' => []
            ],
            'rejected' => [
                'owner' => 'staff',
                'operator_type' => 'role',
                'operators' => []
            ],
        ];
        $transitions = [
            'application_submited_for_distorted' => [
                'from' => 'apply',
                'to'   => 'zra_officer_distorted',
                'condition' => '',
            ],
            'application_submited_for_lost' => [
                'from' => 'apply',
                'to'   => 'police_officer',
                'condition' => '',
            ],
            'application_returned_for_distorted' => [
                'from' => 'zra_officer_distorted',
                'to'   => 'apply',
                'condition' => '',
            ],
            'police_officer_reject' => [
                'from' => 'police_officer',
                'to'   => 'rejected',
                'condition' => '',
            ],
            'police_officer_review' => [
                'from' => 'police_officer',
                'to'   => 'zartsa_officer',
                'condition' => '',
            ],
            'zartsa_officer_reject_to_police' => [
                'from' => 'zartsa_officer',
                'to'   => 'police_officer',
                'condition' => '',
            ],
            'zartsa_officer_review_to_zra' => [
                'from' => 'zartsa_officer',
                'to'   => 'zra_officer_lost',
                'condition' => '',
            ],
            'zra_officer_reject_to_zartsa' => [
                'from' => 'zra_officer_lost',
                'to'   => 'zartsa_officer',
                'condition' => '',
            ],
            'zra_officer_review_lost' => [
                'from' => 'zra_officer_lost',
                'to'   => 'completed',
                'condition' => '',
            ],
            'zra_officer_review_distorted' => [
                'from' => 'zra_officer_distorted',
                'to'   => 'completed',
                'condition' => '',
            ]
        ];


        Workflow::updateOrCreate(
            [
                'code' => 'MVR_REORDER_PLATE_NUMBER',
            ],
            [
            'code' => 'MVR_REORDER_PLATE_NUMBER',
            'summary' => 'Motor Vehicle Reorder both distorted and lost Plate Number',
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
