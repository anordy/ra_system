<?php

namespace Database\Seeders;

use App\Models\Workflow;
use Illuminate\Database\Seeder;

class WorkflowUpgradeTaxTypeSeeder extends Seeder
{
    public function run()
    {
        $name = 'business_tax_type_upgrade';
        $type = 'workflow';
        $marking_store = [
            'type'      => 'multiple_state',
            'property'  => ['marking']
        ];
        $initial_marking = 'initial';
        $supports =  ['App\Models\BusinessTaxTypeUpgrade'];
        $places =  [
            'registration_officer' => [
                'owner' => 'staff',
                'operator_type' => 'role',
                'operators' => [1,2,3]
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
                'owner' => 'staff',
                'operator_type' => 'role',
                'operators' => [1,2,3]
            ],
        ];
        $transitions = [
            'initial' => [
                'from' => 'registration_officer',
                'to'   => 'registration_officer',
                'condition' => '',
            ],
            'registration_officer_review' => [
                'from' => 'registration_officer',
                'to'   => 'registration_manager',
                'condition' => '',
            ],
            'registration_manager_reject' => [
                'from' => 'registration_manager',
                'to'   => 'registration_officer',
                'condition' => '',
            ],
            'registration_manager_review' => [
                'from' => 'registration_manager',
                'to'   => 'completed',
                'condition' => '',
            ],
        ];

        Workflow::updateOrCreate([
            'code' => 'BUSINESS_TAX_TYPE_UPGRADE',
            'summary' => 'Business Tax Type Upgrade Workflow',
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
