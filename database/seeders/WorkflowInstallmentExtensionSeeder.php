<?php

namespace Database\Seeders;

use App\Models\Workflow;
use Illuminate\Database\Seeder;

class WorkflowInstallmentExtensionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $name = 'installment_extension';
        $type = 'workflow';
        $marking_store = [
            'type'      => 'multiple_state',
            'property'  => ['marking']
        ];
        $initial_marking = 'apply';
        $supports =  ['App\Models\Installment\InstallmentExtensionRequest'];
        $places =  [
            'apply' => [
                'owner' => 'taxpayer',
                'operator_type' => 'user',
                'operators' => []
            ],
            'commissioner' => [
                'owner' => 'staff',
                'operator_type' => 'role',
                'operators' => [1, 7]
            ],
            'rejected' => [
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

        Workflow::updateOrCreate(
            [
                'code' => 'INSTALLMENT_EXTENSION'
            ],
            [
            'code' => 'INSTALLMENT_EXTENSION',
            'summary' => 'Installment Extension',
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
