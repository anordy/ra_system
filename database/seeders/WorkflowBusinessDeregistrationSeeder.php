<?php

namespace Database\Seeders;

use App\Models\Workflow;
use Illuminate\Database\Seeder;

class WorkflowBusinessDeregistrationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $name = 'business_deregister';
        $type = 'workflow';
        $marking_store = [
            'type'      => 'multiple_state',
            'property'  => ['marking']
        ];
        $initial_marking = 'apply';
        $supports =  ['App\Models\BusinessDeregistration'];
        $places =  [
            'apply' => [
                'owner' => 'taxpayer',
                'operator_type' => 'user',
                'operators' => []
            ],
            'correct_application' => [
                'owner' => 'taxpayer',
                'operator_type' => 'user',
                'operators' => [1,2,3]
            ],
            'audit_manager' => [
                'owner' => 'staff',
                'operator_type' => 'role',
                'operators' => [1,2,3]
            ],
            'commissioner' => [
                'owner' => 'staff',
                'operator_type' => 'role',
                'operators' => [1,2,3]
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
                'to'   => 'audit_manager',
                'condition' => '',
            ],
            'audit_manager_review' => [
                'from' => 'audit_manager',
                'to'   => 'commissioner',
                'condition' => '',
            ],
            'commissioner_review' => [
                'from' => 'commissioner',
                'to'   => 'completed',
                'condition' => '',
            ],
            'commissioner_reject' => [
                'from' => 'commissioner',
                'to'   => 'audit_manager',
                'condition' => '',
            ],
            'application_filled_incorrect' => [
                'from' => 'audit_manager',
                'to'   => 'correct_application',
                'condition' => '',
            ],
            'application_corrected' => [
                'from' => 'correct_application',
                'to'   => 'audit_manager',
                'condition' => '',
            ],
        ];

        Workflow::updateOrCreate([
            'code' => 'BUSSINESS_DEREGISTRATION',
            'summary' => 'Bussiness Deregistration Workflow',
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
