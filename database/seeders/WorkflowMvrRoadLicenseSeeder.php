<?php

namespace Database\Seeders;

use App\Models\Workflow;
use Illuminate\Database\Seeder;

class WorkflowMvrRoadLicenseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $name = 'mvr_road_license';
        $type = 'workflow';
        $marking_store = [
            'type'      => 'multiple_state',
            'property'  => ['marking']
        ];
        $initial_marking = 'apply';
        $supports =  ['App\Models\RoadLicense\RoadLicense'];
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
            'mvr_zartsa_officer' => [
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
                'to' => 'mvr_zartsa_officer',
                'condition' => '',
            ],
            'mvr_zartsa_officer_review' => [
                'from' => 'mvr_zartsa_officer',
                'to' => 'completed',
                'condition' => '',
            ],
            'application_filled_incorrect' => [
                'from' => 'mvr_zartsa_officer',
                'to' => 'correct_application',
                'condition' => '',
            ],
            'application_corrected' => [
                'from' => 'correct_application',
                'to' => 'mvr_zartsa_officer',
                'condition' => '',
            ],
        ];


        Workflow::updateOrCreate(
            [
                'code' => 'MVR_ROAD_LICENSE'
            ],
            [
            'code' => 'MVR_ROAD_LICENSE',
            'summary' => 'Motor Vehicle Road License',
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
