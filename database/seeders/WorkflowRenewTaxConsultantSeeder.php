<?php

namespace Database\Seeders;

use App\Models\Workflow;
use Illuminate\Database\Seeder;

class WorkflowRenewTaxConsultantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $name = 'renew_tax_consultant_verification';
        $type = 'workflow';
        $marking_store = [
            'type' => 'multiple_state',
            'property' => ['marking']
        ];
        $initial_marking = 'apply';
        $supports = ['App\Models\RenewTaxAgentRequest'];
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
                'to'   => 'completed',
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

        ];

        Workflow::updateOrCreate([
            'code' => 'RENEW_TAX_CONSULTANT_VERIFICATION',
            'summary' => 'Renew Consultant Verification Workflow',
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
