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
            'investigation_report' => [
                'owner' => 'staff',
                'operator_type' => 'role',
                'operators' => [1, 2]
            ],
            'investigation_report_review' => [
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
            'legal' => [
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
                'to'   => 'investigation_report',
                'condition' => '',
            ],
            'investigation_report' => [
                'from' => 'investigation_report',
                'to'   => 'investigation_report_review',
                'condition' => '',
            ],
            'investigation_report_correct' => [
                'from' => 'investigation_report',
                'to'   => 'conduct_investigation',
                'condition' => '',
            ],
            'investigation_report_review' => [
                'from' => 'investigation_report_review',
                'to'   => 'commissioner',
                'condition' => '',
            ],
            'investigation_report_review_reject' => [
                'from' => 'investigation_report_review',
                'to'   => 'investigation_report',
                'condition' => '',
            ],
            'accepted' => [
                'from' => 'commissioner',
                'to'   => 'completed',
                'condition' => '',
            ],
            'forward_to_legal' => [
                'from' => 'commissioner',
                'to'   => 'legal',
                'condition' => '',
            ],
            'rejected' => [
                'from' => 'commissioner',
                'to'   => 'rejected',
                'condition' => '',
            ],
        ];

        Workflow::updateOrCreate([
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
