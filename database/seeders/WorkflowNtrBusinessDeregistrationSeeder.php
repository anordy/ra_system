<?php

namespace Database\Seeders;

use App\Models\Workflow;
use Illuminate\Database\Seeder;

class WorkflowNtrBusinessDeregistrationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $name = 'Non Tax Resident Business De-registration';
        $type = 'workflow';
        $marking_store = [
            'type'      => 'multiple_state',
            'property'  => ['marking']
        ];
        $initial_marking = 'apply';
        $supports =  ['App\Models\Ntr\NtrBusinessDeregistration'];
        $places = [
            'apply' => [
                'owner' => 'taxpayer',
                'operator_type' => 'user',
                'operators' => [],
            ],
            'compliance_officer' => [
                'owner' => 'staff',
                'operator_type' => 'role',
                'operators' => [1, 2, 3],
            ],
            'compliance_manager' => [
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
                'to' => 'compliance_officer',
                'condition' => '',
            ],
            'compliance_officer_review' => [
                'from' => 'compliance_officer',
                'to' => 'compliance_manager',
                'condition' => '',
            ],
            'compliance_officer_reject' => [
                'from' => 'compliance_officer',
                'to' => 'rejected',
                'condition' => '',
            ],
            'compliance_manager_reject' => [
                'from' => 'compliance_manager',
                'to' => 'compliance_officer',
                'condition' => '',
            ],
            'compliance_manager_review' => [
                'from' => 'compliance_manager',
                'to' => 'completed',
                'condition' => '',
            ],
        ];


        Workflow::updateOrCreate([
            'code' => 'NON_TAX_RESIDENT_BUSINESS_DEREGISTRATION',
            'summary' => 'Non Tax Resident Business De-registration Workflow',
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
