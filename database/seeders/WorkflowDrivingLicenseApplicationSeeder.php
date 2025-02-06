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
                'owner' => 'staff',
                'operator_type' => 'role',
                'operators' => []
            ],
            'applicant' => [
                'owner' => 'taxpayer',
                'operator_type' => 'user',
                'operators' => []
            ],
            'zartsa_officer' => [
                'owner' => 'staff',
                'operator_type' => 'role',
                'operators' => [1,2,3]
            ],
            'zra_officer' => [
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
            'application_initiated' => [
                'from' => 'initiate',
                'to'   => 'applicant',
                'condition' => '',
            ],
            'zra_officer_review' => [
                'from' => 'zra_officer',
                'to'   => 'completed',
                'condition' => '',
            ],
            'application_submitted' => [
                'from' => 'applicant',
                'to'   => 'zra_officer',
                'condition' => '',
            ]
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
