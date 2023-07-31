<?php

namespace Database\Seeders;

use App\Models\Workflow;
use Illuminate\Database\Seeder;

class WorkflowCertificateOfQuantitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $name = 'certificate_of_quantity';
        $type = 'workflow';
        $marking_store = [
            'type'      => 'multiple_state',
            'property'  => ['marking']
        ];
        $initial_marking = 'residence_officer';
        $supports =  ['App\Models\Returns\Petroleum\QuantityCertificate'];
        $places =  [
            'residence_officer' => [
                'owner' => 'staff',
                'operator_type' => 'role',
                'operators' => [1, 3]
            ],
            'manager_of_special_sector' => [
                'owner' => 'staff',
                'operator_type' => 'role',
                'operators' => [1, 3]
            ],
            'taxpayer' => [
                'owner' => 'taxpayer',
                'operator_type' => 'user',
                'operators' => [],
            ],
            'completed' => [
                'owner' => 'taxpayer',
                'operator_type' => 'user',
                'operators' => []
            ],
        ];
        $transitions = [
            'certificate_created' => [
                'from' => 'residence_officer',
                'to'   => 'manager_of_special_sector',
                'condition' => '',
            ],
            'certificate_corrected' => [
                'from' => 'residence_officer',
                'to'   => 'manager_of_special_sector',
                'condition' => '',
            ],
            'manager_of_special_sector_review' => [
                'from' => 'manager_of_special_sector',
                'to'   => 'taxpayer',
                'condition' => '',
            ],
            'manager_of_special_sector_reject' => [
                'from' => 'manager_of_special_sector',
                'to'   => 'residence_officer',
                'condition' => '',
            ],
            'taxpayer_review' => [
                'from' => 'taxpayer',
                'to'   => 'completed',
                'condition' => '',
            ],
            'taxpayer_reject' => [
                'from' => 'taxpayer',
                'to'   => 'residence_officer',
                'condition' => '',
            ],
        ];

        Workflow::updateOrCreate([
            'code' => 'QUANTITY_OF_CERTIFICATE',
            'summary' => 'Quantity of Certificate Generation',
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
