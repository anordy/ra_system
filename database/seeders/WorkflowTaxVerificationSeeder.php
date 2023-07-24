<?php

namespace Database\Seeders;

use App\Models\Workflow;
use Illuminate\Database\Seeder;

class WorkflowTaxVerificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $name = 'tax_return_verifications';
        $type = 'workflow';
        $marking_store = [
            'type'      => 'multiple_state',
            'property'  => ['marking']
        ];
        $initial_marking = 'apply';
        $supports =  ['App\Models\Verification\TaxVerification'];
        $places = [
            'initial' => [
                'owner' => 'staff',
                'operator_type' => 'role',
                'operators' => [1, 2]
            ],
            'assign_officers' => [
                'owner' => 'staff',
                'operator_type' => 'role',
                'operators' => [1, 2]
            ],
            'conduct_verification' => [
                'owner' => 'staff',
                'operator_type' => 'user',
                'operators' => [1,2]
            ],
            'verification_report' => [
                'owner' => 'staff',
                'operator_type' => 'role',
                'operators' => [1, 2]
            ],
            'correct_verification_report' => [
                'owner' => 'staff',
                'operator_type' => 'role',
                'operators' => [1, 2]
            ],
            'commissioner' => [
                'owner' => 'staff',
                'operator_type' => 'role',
                'operators' => [1, 2]
            ],
            'completed' => [
                'owner' => 'staff',
                'operator_type' => 'role',
                'operators' => []
            ],
        ];
        $transitions = [
            'start' => [
                'from' => 'initial',
                'to'   => 'assign_officers',
                'condition' => '',
            ],
            'assign_officers' => [
                'from' => 'assign_officers',
                'to'   => 'conduct_verification',
                'condition' => '',
            ],
            'conduct_verification' => [
                'from' => 'conduct_verification',
                'to'   => 'verification_report',
                'condition' => '',
            ],
            'verification_review_report' => [
                'from' => 'verification_report',
                'to'   => 'commissioner',
                'condition' => '',
            ],
            'correct_verification_report' => [
                'from' => 'verification_report',
                'to'   => 'conduct_verification',
                'condition' => '',
            ],
            'correct_reviewed_report' => [
                'from' => 'commissioner',
                'to'   => 'verification_report',
                'condition' => '',
            ],
            'completed' => [
                'from' => 'commissioner',
                'to'   => 'completed',
                'condition' => '',
            ],
        ];

        Workflow::updateOrCreate([
            'code' => 'TAX_RETURN_VERIFICATION',
            'summary' => 'Tax return verification modal',
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
