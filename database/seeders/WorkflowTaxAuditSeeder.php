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
        $initial_marking = 'initial';
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
            'send_notification_letter' => [
                'owner' => 'staff',
                'operator_type' => 'role',
                'operators' => [1, 2]
            ],
            'taxpayer_uploads_documents' => [
                'owner' => 'taxpayer',
                'operator_type' => 'role',
                'operators' => []
            ],
            'audit_taxpayer_acceptance' => [
                'owner' => 'taxpayer',
                'operator_type' => 'role',
                'operators' => []
            ],
            'audit_team_review' => [
                'owner' => 'staff',
                'operator_type' => 'role',
                'operators' => [1, 2]
            ],
            'conduct_audit' => [
                'owner' => 'staff',
                'operator_type' => 'user',
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
            'taxpayer_accept_preliminary_report' => [
                'owner' => 'taxpayer',
                'operator_type' => 'role',
                'operators' => []
            ],
            'prepare_final_report' => [
                'owner' => 'staff',
                'operator_type' => 'role',
                'operators' => [1, 2]
            ],
            'taxpayer_respond_preliminary_report_DC' => [
                'owner' => 'staff',
                'operator_type' => 'role',
                'operators' => [1, 2]
            ],
            'taxpayer_respond_preliminary_report_MN' => [
                'owner' => 'staff',
                'operator_type' => 'role',
                'operators' => [1, 2]
            ],
            'taxpayer_respond_preliminary_report_TL' => [
                'owner' => 'staff',
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
            'commissioner' => [
                'owner' => 'staff',
                'operator_type' => 'role',
                'operators' => [1, 2]
            ],
            'completed' => [
                'owner' => 'staff',
                'operator_type' => 'role',
                'operators' => [1, 2]
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
                'to'   => 'send_notification_letter',
                'condition' => '',
            ],
            'send_notification_letter' => [
                'from' => 'send_notification_letter',
                'to'   => 'taxpayer_uploads_documents',
                'condition' => '',
            ],
            'taxpayer_uploads_documents' => [
                'from' => 'taxpayer_uploads_documents',
                'to'   => 'audit_taxpayer_acceptance',
                'condition' => '',
            ],
            'audit_taxpayer_acceptance' => [
                'from' => 'audit_taxpayer_acceptance',
                'to'   => 'audit_team_review',
                'condition' => '',
            ],
            'audit_team_review' => [
                'from' => 'audit_team_review',
                'to'   => 'conduct_audit',
                'condition' => '',
            ],
            'audit_team_reject_extension' => [
                'from' => 'audit_team_review',
                'to'   => 'taxpayer_uploads_documents',
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
                'to'   => 'taxpayer_accept_preliminary_report',
                'condition' => '',
            ],
            'correct_preliminary_report_review' => [
                'from' => 'preliminary_report_review',
                'to'   => 'preliminary_report',
                'condition' => '',
            ],
            'taxpayer_accept_preliminary_report' => [
                'from' => 'taxpayer_accept_preliminary_report',
                'to'   => 'prepare_final_report',
                'condition' => '',
            ],
            'taxpayer_respond_preliminary_report_DC' => [
                'from' => 'taxpayer_accept_preliminary_report',
                'to'   => 'taxpayer_respond_preliminary_report_DC',
                'condition' => '',
            ],
            'taxpayer_respond_preliminary_report_MN' => [
                'from' => 'taxpayer_respond_preliminary_report_DC',
                'to'   => 'taxpayer_respond_preliminary_report_MN',
                'condition' => '',
            ],
            'taxpayer_respond_preliminary_report_TL' => [
                'from' => 'taxpayer_respond_preliminary_report_MN',
                'to'   => 'conduct_audit',
                'condition' => '',
            ],
            'prepare_final_report' => [ //Audit Team
                'from' => 'prepare_final_report',
                'to'   => 'final_report',
                'condition' => '',
            ],
            'final_report' => [ //Audit Manager Review
                'from' => 'final_report',
                'to'   => 'final_report_review',
                'condition' => '',
            ],
            'correct_final_report' => [
                'from' => 'final_report',
                'to'   => 'prepare_final_report',
                'condition' => '',
            ],
            'final_report_review' => [ //Department commissioner Review
                'from' => 'final_report_review',
                'to'   => 'completed',
                'condition' => '',
            ],
            'foward_to_commissioner' => [ //Department commissioner Review
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
            'rejected' => [
                'from' => 'commissioner',
                'to'   => 'final_report_review',
                'condition' => '',
            ],
        ];

        // Check if a record exists with the TAX_AUDIT code
        $existingWorkflow = Workflow::where('code', 'TAX_AUDIT')->get();

        if ($existingWorkflow) {
            // Delete all found records
            foreach ($existingWorkflow as $workflow) {
                $workflow->delete();
            }
        }
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
