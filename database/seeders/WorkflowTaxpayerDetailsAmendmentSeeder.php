<?php

namespace Database\Seeders;

use App\Models\Workflow;
use Illuminate\Database\Seeder;

class WorkflowTaxpayerDetailsAmendmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $name = 'taxpayer_details_amendment_verification';
        $type = 'workflow';
        $marking_store = [
            'type' => 'multiple_state',
            'property' => ['marking']
        ];
        $initial_marking = 'apply';
        $supports = ['App\Models\TaxpayerAmendmentRequest'];
        $places =  [
            'apply' => [
                'owner' => 'staff',
                'operator_type' => 'role',
                'operators' => [1,3]
            ],
            'registration_manager' => [
                'owner' => 'staff',
                'operator_type' => 'role',
                'operators' => [1, 2]
            ],
            'completed' => [
                'owner' => 'staff',
                'operator_type' => 'role',
                'operators' => []
            ],
            'tempered_information' => [
                'owner' => 'staff',
                'operator_type' => 'role',
                'operators' => []
            ],
        ];
        $transitions = [
            'application_submitted' => [
                'from' => 'apply',
                'to'   => 'registration_manager',
                'condition' => '',
            ],
            'registration_manager_review' => [
                'from' => 'registration_manager',
                'to'   => 'completed',
                'condition' => '',
            ],
            'registration_manager_reject' => [
                'from' => 'registration_manager',
                'to'   => 'completed',
                'condition' => '',
            ],
            'tempered_information_detected' => [
                'from' => 'registration_manager',
                'to'   => 'tempered_information',
                'condition' => '',
            ],
        ];

        Workflow::updateOrCreate([
            'code' => 'taxpayer_details_amendment_verification',
            'summary' => 'Taxpayer Details Amendment Verification Workflow',
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
