<?php

namespace Database\Seeders;

use App\Models\Workflow;
use Illuminate\Database\Seeder;

class WorkflowPublicServiceTemporaryClosureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $name = 'public_service_temporary_closure';
        $type = 'workflow';
        $marking_store = [
            'type'      => 'multiple_state',
            'property'  => ['marking']
        ];
        $initial_marking = 'apply';
        $supports =  ['App\Models\PublicService\TemporaryClosure'];
        $places = [
            'apply' => [
                'owner' => 'taxpayer',
                'operator_type' => 'user',
                'operators' => [],
            ],
            'correct_application' => [
                'owner' => 'taxpayer',
                'operator_type' => 'user',
                'operators' => [],
            ],
            'public_service_registration_officer' => [
                'owner' => 'staff',
                'operator_type' => 'role',
                'operators' => [1, 2, 3],
            ],
            'public_service_registration_manager' => [
                'owner' => 'staff',
                'operator_type' => 'role',
                'operators' => [1, 2, 3],
            ],
            'rejected' => [
                'owner' => 'staff',
                'operator_type' => 'role',
                'operators' => [],
            ],
            'completed' => [
                'owner' => 'staff',
                'operator_type' => 'role',
                'operators' => [],
            ],
        ];
        $transitions = [
            'application_submitted' => [
                'from' => 'apply',
                'to' => 'public_service_registration_officer',
                'condition' => '',
            ],
            'public_service_registration_officer_review' => [
                'from' => 'public_service_registration_officer',
                'to' => 'public_service_registration_manager',
                'condition' => '',
            ],
            'application_filled_incorrect' => [
                'from' => 'public_service_registration_officer',
                'to' => 'correct_application',
                'condition' => '',
            ],
            'application_corrected' => [
                'from' => 'correct_application',
                'to' => 'public_service_registration_officer',
                'condition' => '',
            ],
            'public_service_registration_manager_reject' => [
                'from' => 'public_service_registration_manager',
                'to' => 'public_service_registration_officer',
                'condition' => '',
            ],
            'public_service_registration_manager_review' => [
                'from' => 'public_service_registration_manager',
                'to' => 'completed',
                'condition' => '',
            ],
        ];


        Workflow::updateOrCreate(
            [
                'code' => 'PUBLIC_SERVICE_TEMPORARY_CLOSURE'
            ],
            [
                'code' => 'PUBLIC_SERVICE_TEMPORARY_CLOSURE',
                'summary' => 'Public Service Temporary Closure',
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
