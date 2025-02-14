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
        $initial_marking = 'initiator';
        $supports =  ['App\Models\DlLicenseApplication'];
        $places =  [
            'initiator' => [
                'owner' => 'any',
                'operator_type' => 'user',
                'operators' => []
            ],
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
            'application_initiated' => [
                'from' => 'initiator',
                'to'   => 'applicant',
                'condition' => '',
            ],
            'application_initiated_for_class' => [
                'from' => 'initiator',
                'to'   => 'zra_officer',
                'condition' => '',
            ],
            'application_initiated_for_renewal' => [
                'from' => 'initiator',
                'to'   => 'zra_officer',
                'condition' => '',
            ],
            'application_initiated_for_distorted' => [
                'from' => 'initiator',
                'to'   => 'zra_officer',
                'condition' => '',
            ],
            'application_initiated_for_lost' => [
                'from' => 'initiator',
                'to'   => 'police_officer',
                'condition' => '',
            ],
            'application_returned_for_lost' => [
                'from' => 'police_officer',
                'to'   => 'initiator',
                'condition' => '',
            ],
            'police_officer_review' => [
                'from' => 'police_officer',
                'to'   => 'zartsa_officer',
                'condition' => '',
            ],
            'police_officer_reject' => [
                'from' => 'police_officer',
                'to'   => 'rejected',
                'condition' => '',
            ],
            'zartsa_officer_reject_to_police' => [
                'from' => 'zartsa_officer',
                'to'   => 'police_officer',
                'condition' => '',
            ],
            'zartsa_officer_review_to_zra' => [
                'from' => 'zartsa_officer',
                'to'   => 'zra_officer',
                'condition' => '',
            ],
            'zra_officer_reject_to_zartsa' => [
                'from' => 'zra_officer',
                'to'   => 'zartsa_officer',
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

        Workflow::updateOrCreate(
            [
              'code' => 'LICENSE_APPLICATION',
            ],
            [
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
