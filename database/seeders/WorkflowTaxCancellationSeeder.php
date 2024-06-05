<?php

namespace Database\Seeders;

use App\Models\Workflow;
use Illuminate\Database\Seeder;

class WorkflowTaxCancellationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $name = 'tax_return_cancellation';
        $type = 'workflow';
        $marking_store = [
            'type'      => 'multiple_state',
            'property'  => ['marking']
        ];
        $initial_marking = 'apply';
        $supports =  ['App\Models\Returns\TaxReturnCancellation'];
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
            'tax_return_officer' => [
                'owner' => 'staff',
                'operator_type' => 'role',
                'operators' => [1, 3]
            ],
            'tax_return_manager' => [
                'owner' => 'staff',
                'operator_type' => 'role',
                'operators' => [1, 3]
            ],
            'department_commissioner' => [
                'owner' => 'staff',
                'operator_type' => 'role',
                'operators' => [1, 3]
            ],
            'general_commissioner' => [
                'owner' => 'staff',
                'operator_type' => 'role',
                'operators' => [1, 3]
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
            'application_submitted' => [
                'from' => 'apply',
                'to'   => 'tax_return_officer',
                'condition' => '',
            ],
            'tax_officer_review' => [
                'from' => 'tax_return_officer',
                'to'   => 'tax_return_manager',
                'condition' => '',
            ],
            'tax_officer_incorrect' => [
                'from' => 'tax_return_officer',
                'to'   => 'correct_application',
                'condition' => '',
            ],
            'application_corrected' => [
                'from' => 'correct_application',
                'to'   => 'tax_return_officer',
                'condition' => '',
            ],
            'tax_manager_review' => [
                'from' => 'tax_return_manager',
                'to'   => 'general_commissioner',
                'condition' => '',
            ],
            'tax_manager_recommends' => [
                'from' => 'tax_return_manager',
                'to'   => 'department_commissioner',
                'condition' => '',
            ],
            'department_commissioner_review' => [
                'from' => 'department_commissioner',
                'to'   => 'general_commissioner',
                'condition' => '',
            ],
            'general_commissioner_review' => [
                'from' => 'general_commissioner',
                'to'   => 'complete',
                'condition' => '',
            ],
            'general_commissioner_reject' => [
                'from' => 'general_commissioner',
                'to'   => 'rejected',
                'condition' => '',
            ],
        ];

        Workflow::updateOrCreate(
            [
                'code' => 'TAX_RETURN_CANCELLATION'
            ],
            [
            'code' => 'TAX_RETURN_CANCELLATION',
            'summary' => 'Tax return cancellation',
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
