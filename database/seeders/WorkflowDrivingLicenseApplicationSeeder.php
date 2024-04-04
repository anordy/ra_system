<?php

namespace Database\Seeders;

use App\Models\Workflow;
use Illuminate\Database\Seeder;

class WorkflowDrivingLicenseApplicationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $name = 'license_application';
        $type = 'workflow';
        $marking_store = [
            'type'      => 'multiple_state',
            'property'  => ['marking']
        ];
        $initial_marking = 'initiate';
        $supports =  ['App\Models\DlLicenseApplication'];
        $places =  [
            'initiate' => [
                'owner' => 'taxpayer',
                'operator_type' => 'user',
                'operators' => []
            ],
            'correct_application' => [
                'owner' => 'taxpayer',
                'operator_type' => 'user',
                'operators' => []
            ],
            'transport_officer' => [
                'owner' => 'staff',
                'operator_type' => 'role',
                'operators' => [1,2,3]
            ],
            'completed' => [
                'owner' => 'staff',
                'operator_type' => 'role',
                'operators' => []
            ],
        ];
        $transitions = [
            'application_submitted' => [
                'from' => 'initiate',
                'to'   => 'transport_officer',
                'condition' => '',
            ],
            'transport_officer_review' => [
                'from' => 'transport_officer',
                'to'   => 'completed',
                'condition' => '',
            ],
            'application_filled_incorrect' => [
                'from' => 'transport_officer',
                'to'   => 'correct_application',
                'condition' => '',
            ],
            'application_corrected' => [
                'from' => 'correct_application',
                'to'   => 'transport_officer',
                'condition' => '',
            ],
        ];

        Workflow::updateOrCreate([
            'code' => 'LICENSE_APPLICATION',
            'summary' => 'Drivers License Application Closure Workflow',
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
