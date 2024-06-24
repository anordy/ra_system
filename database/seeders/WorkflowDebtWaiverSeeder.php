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
            'department_commissioner' => [
                'owner' => 'staff',
                'operator_type' => 'role',
                'operators' => [1, 10]
            ],
            'commissioner_general' => [
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
                'to'   => 'department_commissioner',
                'condition' => '',
            ],
            'department_commissioner_review' => [
                'from' => 'department_commissioner',
                'to'   => 'commissioner_general',
                'condition' => '',
            ],
            'commissioner_general_complete' => [
                'from' => 'commissioner_general',
                'to'   => 'completed',
                'condition' => '',
            ],
            'commissioner_general_reject' => [
                'from' => 'commissioner_general',
                'to'   => 'rejected',
                'condition' => '',
            ],

        ];

        Workflow::updateOrCreate(
            [
                'code' => 'DEBT_WAIVER',
            ],[
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
