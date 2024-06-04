<?php

namespace Database\Seeders;

use App\Models\Workflow;
use Illuminate\Database\Seeder;

class WorkflowTaxInvestigationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $name = 'tax_investigation';
        $type = 'workflow';
        $marking_store = [
            'type'      => 'multiple_state',
            'property'  => ['marking']
        ];
        $initial_marking = 'apply';
        $supports =  ['App\Models\Investigation\TaxInvestigation'];
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
            'conduct_investigation' => [
                'owner' => 'staff',
                'operator_type' => 'user',
                'operators' => []
            ],
            'investigation_report_review' => [
                'owner' => 'staff',
                'operator_type' => 'role',
                'operators' => [1, 2]
            ],
            'taxPayer_acceptance' => [
                'owner' => 'taxpayer',
                'operator_type' => 'role',
                'operators' => [1, 2]
            ],
            'final_report' => [
                'owner' => 'staff',
                'operator_type' => 'role',
                'operators' => [1, 2]
            ],
            'final_report_review' => [
                'owner' => 'staff',
                'operator_type' => 'role',
                'operators' => [1, 2]
            ],
            'taxPayer_rejected' => [
                'owner' => 'staff',
                'operator_type' => 'role',
                'operators' => [1, 2]
            ],
            'taxPayer_rejected_review' => [
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
            'rejected' => [
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
                'to'   => 'conduct_investigation',
                'condition' => '',
            ],
            'conduct_investigation' => [
                'from' => 'conduct_investigation',
                'to'   => 'investigation_report_review',
                'condition' => '',
            ],
            'investigation_report_review' => [
                'from' => 'investigation_report_review',
                'to'   => 'taxPayer_acceptance',
                'condition' => '',
            ],
            'prepare_final_report' => [
                'from' => 'investigation_report_review',
                'to'   => 'final_report',
                'condition' => '',
            ],
            'investigation_report_reject' => [
                'from' => 'investigation_report_review',
                'to'   => 'conduct_investigation',
                'condition' => '',
            ],
            'taxPayer_acceptance' => [
                'from' => 'taxPayer_acceptance',
                'to'   => 'final_report',
                'condition' => '',
            ],
            'taxPayer_rejected' => [
                'from' => 'taxPayer_acceptance',
                'to'   => 'taxPayer_rejected_review',
                'condition' => '',
            ],
            'taxPayer_rejected_review' => [
                'from' => 'taxPayer_rejected_review',
                'to'   => 'conduct_investigation',
                'condition' => '',
            ],
            'final_report' => [
                'from' => 'final_report',
                'to'   => 'final_report_review',
                'condition' => '',
            ],
            'final_report_review' => [
                'from' => 'final_report_review',
                'to'   => 'commissioner',
                'condition' => '',
            ],
            'final_report_reject' => [
                'from' => 'final_report_review',
                'to'   => 'final_report',
                'condition' => '',
            ],
            'accepted' => [
                'from' => 'commissioner',
                'to'   => 'completed',
                'condition' => '',
            ],
            'rejected' => [
                'from' => 'commissioner',
                'to'   => 'rejected',
                'condition' => '',
            ],
        ];


        Workflow::updateOrCreate(
            [
                'code' => 'TAX_INVESTIGATION',
            ],
            [
            'code' => 'TAX_INVESTIGATION',
            'summary' => 'Tax investigation workflow',
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
