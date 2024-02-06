<?php

namespace Database\Seeders;

use App\Models\Workflow;
use Illuminate\Database\Seeder;

class WorkflowPropertyTaxRegistrationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $name = 'property_tax_registration';
        $type = 'workflow';
        $marking_store = [
            'type'      => 'multiple_state',
            'property'  => ['marking']
        ];
        $initial_marking = 'apply';
        $supports =  ['App\Models\PropertyTax\Property'];
        $places =  [
            'apply' => [
                'owner' => 'taxpayer',
                'operator_type' => 'user',
                'operators' => []
            ],
            'correct_application' => [
                'owner' => 'taxpayer',
                'operator_type' => 'user',
                'operators' => []
            ],
            'property_tax_officer' => [
                'owner' => 'staff',
                'operator_type' => 'role',
                'operators' => [1]
            ],
            'completed' => [
                'owner' => 'staff',
                'operator_type' => 'role',
                'operators' => []
            ]
        ];
        $transitions = [
            'application_submitted' => [
                'from' => 'apply',
                'to'   => 'property_tax_officer',
                'condition' => '',
            ],
            'property_tax_officer_review' => [
                'from' => 'property_tax_officer',
                'to'   => 'completed',
                'condition' => '',
            ],
            'application_filled_incorrect' => [
                'from' => 'property_tax_officer',
                'to'   => 'correct_application',
                'condition' => '',
            ],
            'application_corrected' => [
                'from' => 'correct_application',
                'to'   => 'property_tax_officer',
                'condition' => '',
            ],
        ];

        Workflow::updateOrCreate([
            'code' => 'PROPERTY_TAX_REGISTRATION',
            'summary' => 'Property Tax Registration',
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
