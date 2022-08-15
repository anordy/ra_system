<?php

namespace Database\Seeders;

use App\Models\Workflow;
use Illuminate\Database\Seeder;

class WorkflowBusinessTaxTypeChangeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $name = 'business_tax_type_change';
        $type = 'workflow';
        $marking_store = [
            'type'      => 'multiple_state',
            'property'  => ['marking']
        ];
        $initial_marking = 'apply';
        $supports =  ['App\Models\BusinessTaxTypeChange'];
        $places =  [
            'apply' => [
                'owner' => 'taxpayer',
                'operator_type' => 'user',
                'operators' => []
            ],
            'registration_manager' => [
                'owner' => 'staff',
                'operator_type' => 'role',
                'operators' => [1,2,3]
            ],
            'completed' => [
                'owner' => 'staff',
                'operator_type' => 'role',
                'operators' => []
            ],
            'rejected' => [
                'owner' => 'taxpayer',
                'operator_type' => 'user',
                'operators' => []
            ],
        ];
        $transitions = [
            'application_submitted' => [
                'from' => 'apply',
                'to'   => 'registration_manager',
                'condition' => '',
            ],
            'registration_manager_reject' => [
                'from' => 'registration_manager',
                'to'   => 'rejected',
                'condition' => '',
            ],
            'registration_manager_review' => [
                'from' => 'registration_manager',
                'to'   => 'completed',
                'condition' => '',
            ],
        ];

        Workflow::updateOrCreate([
            'code' => 'BUSINESS_TAX_TYPE_CHANGE',
            'summary' => 'Business Tax Type Change Workflow',
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
