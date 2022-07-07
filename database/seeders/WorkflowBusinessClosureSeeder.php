<?php

namespace Database\Seeders;

use App\Models\Workflow;
use Illuminate\Database\Seeder;

class WorkflowBusinessClosureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $name = 'business_closure';
        $type = 'workflow';
        $marking_store = [
            'type'      => 'multiple_state',
            'property'  => ['marking']
        ];
        $initial_marking = 'apply';
        $supports =  ['App\Models\BusinessTempClosure'];
        $places =  [
            'apply' => [
                'owner' => 'taxpayer',
                'operator_type' => 'user',
                'operators' => []
            ],
            'compliance_manager' => [
                'owner' => 'staff',
                'operator_type' => 'role',
                'operators' => []
            ],
            'compliance_officer' => [
                'owner' => 'staff',
                'operator_type' => 'role',
                'operators' => []
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
                'to'   => 'compliance_manager',
                'condition' => '',
            ],
            'compliance_manager_review' => [
                'from' => 'compliance_manager',
                'to'   => 'compliance_officer',
                'condition' => '',
            ],
            'compliance_officer_review' => [
                'from' => 'compliance_officer',
                'to'   => 'completed',
                'condition' => '',
            ],
        ];

        Workflow::updateOrCreate([
            'code' => 'BUSSINESS_CLOSURE',
            'summary' => 'Bussiness Closure Workflow',
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
