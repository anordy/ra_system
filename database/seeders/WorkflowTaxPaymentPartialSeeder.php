<?php

namespace Database\Seeders;

use App\Models\Workflow;
use Illuminate\Database\Seeder;

class WorkflowTaxPaymentPartialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $name = 'TAX_PAYMENT_PARTIAL_APPROVAL';
        $type = 'workflow';
        $marking_store = [
            'type'      => 'multiple_state',
            'property'  => ['marking']
        ];
        $initial_marking = 'apply';
        $supports =  ['App\Models\TaxpayerLedger\TaxpayerLedgerPayment'];
        $places = [
            'apply' => [
                'owner' => 'taxpayer',
                'operator_type' => 'user',
                'operators' => [],
            ],
            'department_commissioner' => [
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
                'to' => 'department_commissioner',
                'condition' => '',
            ],
            'department_commissioner_review' => [
                'from' => 'department_commissioner',
                'to' => 'completed',
                'condition' => '',
            ],
            'department_commissioner_reject' => [
                'from' => 'department_commissioner',
                'to' => 'rejected',
                'condition' => '',
            ]
        ];


        Workflow::updateOrCreate(
            [
                'code' => 'TAX_PAYMENT_PARTIAL_APPROVAL'
            ],
            [
            'code' => 'TAX_PAYMENT_PARTIAL_APPROVAL_SEEDER',
            'summary' => 'Tax Payment Partial Approval Workflow',
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
