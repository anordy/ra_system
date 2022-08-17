<?php

namespace Database\Seeders;

use App\Models\Workflow;
use Illuminate\Database\Seeder;

class WorkflowInstallmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $name = 'installments_request';
        $type = 'workflow';
        $marking_store = [
            'type'      => 'multiple_state',
            'property'  => ['marking']
        ];
        $initial_marking = 'apply';
        $supports =  ['App\Models\Installment\InstallmentRequest'];
        $places = [
            'initial' => [
                'owner' => 'staff',
                'operator_type' => 'role',
                'operators' => [1, 2]
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
            'start' => [
                'from' => 'initial',
                'to'   => 'debt_manager',
                'condition' => '',
            ],
            'debt_manager' => [
                'from' => 'debt_manager',
                'to'   => 'crdm',
                'condition' => '',
            ],
            'crdm' => [
                'from' => 'crdm',
                'to'   => 'commissioner',
                'condition' => '',
            ],
            'rejected' => [
                'from' => 'commissioner',
                'to'   => 'rejected',
                'condition' => '',
            ],
            'accepted' => [
                'from' => 'commissioner',
                'to'   => 'completed',
                'condition' => '',
            ],
        ];

        Workflow::updateOrCreate([
            'code' => 'INSTALLMENT_REQUESTS',
            'summary' => 'Installment Request',
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
