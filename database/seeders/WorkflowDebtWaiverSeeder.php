<?php

namespace Database\Seeders;

use App\Models\Workflow;
use Illuminate\Database\Seeder;

class WorkflowDebtWaiverSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $name = 'debt_waiver';
        $type = 'workflow';
        $marking_store = [
            'type' => 'multiple_state',
            'property' => ['marking'],
        ];
        $initial_marking = 'apply';
        $supports = ['App\Models\Debts\DebtWaiver'];
        $places = [
            'apply' => [
                'owner' => 'taxpayer',
                'operator_type' => 'user',
                'operators' => [],
            ],
            'debt_manager' => [
                'owner' => 'staff',
                'operator_type' => 'role',
                'operators' => [1, 9]
            ],
            'crdm' => [
                'owner' => 'staff',
                'operator_type' => 'role',
                'operators' => [1, 10]
            ],
            'commissioner' => [
                'owner' => 'staff',
                'operator_type' => 'role',
                'operators' => [1, 7]
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
                'to'   => 'debt_manager',
                'condition' => '',
            ],
            'debt_manager_review' => [
                'from' => 'debt_manager',
                'to'   => 'crdm',
                'condition' => '',
            ],
            'crdm_review' => [
                'from' => 'crdm',
                'to'   => 'commissioner',
                'condition' => '',
            ],
            'crdm_complete' => [
                'from' => 'crdm',
                'to'   => 'completed',
                'condition' => '',
            ],
            'crdm_reject' => [
                'from' => 'crdm',
                'to'   => 'rejected',
                'condition' => '',
            ],
            'commissioner_reject' => [
                'from' => 'commissioner',
                'to'   => 'rejected',
                'condition' => '',
            ],
            'commissioner_complete' => [
                'from' => 'commissioner',
                'to'   => 'completed',
                'condition' => '',
            ],

        ];

        Workflow::updateOrCreate([
            'code' => 'DEBT_WAIVER',
            'summary' => 'Debt Waiver Workflow',
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
