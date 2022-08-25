<?php

namespace Database\Seeders;

use App\Models\Workflow;
use Illuminate\Database\Seeder;

class WorkflowRecoveryMeasureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $name = 'debt_recovery_measure';
        $type = 'workflow';
        $marking_store = [
            'type'      => 'multiple_state',
            'property'  => ['marking']
        ];
        $initial_marking = 'apply';
        $supports =  ['App\Models\Debts\Debt'];
        $places = [
            'crdm' => [
                'owner' => 'staff',
                'operator_type' => 'role',
                'operators' => [1, 10]
            ],
            'correct_assignment' => [
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
            'crdm_assign' => [
                'from' => 'crdm',
                'to'   => 'commissioner',
                'condition' => '',
            ],
            'assignment_filled_incorrect' => [
                'from' => 'commissioner',
                'to'   => 'correct_assignment',
                'condition' => '',
            ],
            'assignment_corrected' => [
                'from' => 'correct_assignment',
                'to'   => 'commissioner',
                'condition' => '',
            ],
            'commissioner_review' => [
                'from' => 'commissioner',
                'to'   => 'completed',
                'condition' => '',
            ]
        ];

        Workflow::updateOrCreate([
            'code' => 'DEBT_RECOVERY_MEASURE',
            'summary' => 'Debt Recovery Measure',
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
