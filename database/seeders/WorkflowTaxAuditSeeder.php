<?php

namespace Database\Seeders;

use App\Models\Workflow;
use Illuminate\Database\Seeder;

class WorkflowTaxAuditSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $name = 'tax_audit';
        $type = 'workflow';
        $marking_store = [
            'type'      => 'multiple_state',
            'property'  => ['marking']
        ];
        $initial_marking = 'apply';
        $supports =  ['App\Models\TaxAudit\TaxAudit'];
        $places = [
            'initial' => [
                'owner' => 'staff',
                'operator_type' => 'role',
                'operators' => [1, 2]
            ],
            'assign_officers' => [
                'owner' => 'staff',
                'operator_type' => 'role',
                'operators' => [1, 2]
            ],
            'conduct_audit' => [
                'owner' => 'staff',
                'operator_type' => 'role',
                'operators' => [1, 2]
            ],
            'preliminary_report' => [
                'owner' => 'staff',
                'operator_type' => 'role',
                'operators' => [1, 2]
            ],
            'preliminary_report_review' => [
                'owner' => 'staff',
                'operator_type' => 'role',
                'operators' => [1, 2]
            ],
            'correct_preliminary_report' => [
                'owner' => 'staff',
                'operator_type' => 'role',
                'operators' => [1, 2]
            ],
            'prepare_final_report' => [
                'owner' => 'staff',
                'operator_type' => 'role',
                'operators' => [1, 2]
            ],
            'final_report' => [
                'owner' => 'staff',
                'operator_type' => 'role',
                'operators' => [1, 2]
            ],
            'correct_final_report' => [
                'owner' => 'staff',
                'operator_type' => 'role',
                'operators' => [1, 2]
            ],
            'final_report_review' => [
                'owner' => 'staff',
                'operator_type' => 'role',
                'operators' => [1, 2]
            ],
            'correct_final_report' => [
                'owner' => 'staff',
                'operator_type' => 'role',
                'operators' => [1, 2]
            ],
            'commissioner' => [
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
            'start' => [
                'from' => 'initial',
                'to'   => 'assign_officers',
                'condition' => '',
            ],
            'assign_officers' => [
                'from' => 'assign_officers',
                'to'   => 'conduct_audit',
                'condition' => '',
            ],
            'conduct_audit' => [
                'from' => 'conduct_audit',
                'to'   => 'preliminary_report',
                'condition' => '',
            ],
            'preliminary_report' => [
                'from' => 'preliminary_report',
                'to'   => 'preliminary_report_review',
                'condition' => '',
            ],
            'correct_preliminary_report' => [
                'from' => 'preliminary_report',
                'to'   => 'conduct_audit',
                'condition' => '',
            ],
            'preliminary_report_review' => [
                'from' => 'preliminary_report_review',
                'to'   => 'prepare_final_report',
                'condition' => '',
            ],
            'correct_preliminary_report_review' => [
                'from' => 'preliminary_report_review',
                'to'   => 'preliminary_report',
                'condition' => '',
            ],
            'prepare_final_report' => [
                'from' => 'prepare_final_report',
                'to'   => 'final_report',
                'condition' => '',
            ],
            'final_report' => [
                'from' => 'final_report',
                'to'   => 'final_report_review',
                'condition' => '',
            ],
            'correct_final_report' => [
                'from' => 'final_report',
                'to'   => 'prepare_final_report',
                'condition' => '',
            ],
            'final_report_review' => [
                'from' => 'final_report_review',
                'to'   => 'commissioner',
                'condition' => '',
            ],
            'correct_final_report_review' => [
                'from' => 'final_report_review',
                'to'   => 'final_report',
                'condition' => '',
            ],
            'accepted' => [
                'from' => 'commissioner',
                'to'   => 'completed',
                'condition' => '',
            ],
        ];

        Workflow::updateOrCreate([
            'code' => 'TAX_AUDIT',
            'summary' => 'Tax audit workflow',
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
