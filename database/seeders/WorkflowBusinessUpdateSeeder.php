<?php

namespace Database\Seeders;

use App\Models\Workflow;
use Illuminate\Database\Seeder;

class WorkflowBusinessUpdateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $name = 'business_update';
        $type = 'workflow';
        $marking_store = [
            'type'      => 'multiple_state',
            'property'  => ['marking']
        ];
        $initial_marking = 'apply';
        $supports =  ['App\Models\BusinessUpdate'];
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
            'registration_officer' => [
                'owner' => 'staff',
                'operator_type' => 'role',
                'operators' => [1, 3]
            ],
            'registration_manager' => [
                'owner' => 'staff',
                'operator_type' => 'role',
                'operators' => [1, 2]
            ],
            'completed' => [
                'owner' => 'staff',
                'operator_type' => 'role',
                'operators' => []
            ],
        ];
        $transitions = [
            'application_updated' => [
                'from' => 'apply',
                'to'   => 'registration_officer',
                'condition' => '',
            ],
            'registration_officer_review' => [
                'from' => 'registration_officer',
                'to'   => 'registration_manager',
                'condition' => '',
            ],
            'application_filled_incorrect' => [
                'from' => 'registration_officer',
                'to'   => 'correct_application',
                'condition' => '',
            ],
            'application_corrected' => [
                'from' => 'correct_application',
                'to'   => 'registration_officer',
                'condition' => '',
            ],
            'registration_manager_review' => [
                'from' => 'registration_manager',
                'to'   => 'completed',
                'condition' => '',
            ],
            'registration_manager_reject' => [
                'from' => 'registration_manager',
                'to'   => 'registration_officer',
                'condition' => '',
            ],
        ];

        Workflow::updateOrCreate([
            'code' => 'BUSINESS_UPDATE',
            'summary' => 'Business Update Workflow',
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
