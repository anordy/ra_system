<?php

namespace Database\Seeders;

use App\Models\Workflow;
use Illuminate\Database\Seeder;

class WorkflowTaxVerificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $name = 'tax_return_verifications';
        $type = 'workflow';
        $marking_store = [
            'type' => 'multiple_state',
            'property' => ['marking']
        ];
        $initial_marking = 'apply';
        $supports = ['App\Models\Verification\TaxVerification'];
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
            'send_notification' => [
                'owner' => 'staff',
                'operator_type' => 'role',
                'operators' => [1, 2]
            ],
            'conduct_verification' => [
                'owner' => 'staff',
                'operator_type' => 'user',
                'operators' => [1, 2]
            ],
            'manager_verification_report_review' => [
                'owner' => 'staff',
                'operator_type' => 'role',
                'operators' => [1, 2]
            ],
            'correct_verification_report' => [
                'owner' => 'staff',
                'operator_type' => 'role',
                'operators' => [1, 2]
            ],
            'commissioner_verification_report_review' => [
                'owner' => 'staff',
                'operator_type' => 'role',
                'operators' => [1, 2]
            ],
            'taxpayer_acceptance' => [
                'owner' => 'taxpayer',
                'operator_type' => 'user',
                'operators' => [1, 2]
            ],
            'taxpayer_respond' => [
                'owner' => 'taxpayer',
                'operator_type' => 'user',
                'operators' => [1, 2]
            ],
            'officer_prepare_final_report' => [
                'owner' => 'staff',
                'operator_type' => 'user',
                'operators' => [1, 2]
            ],
            'officer_prepare_notice_of_discussion' => [
                'owner' => 'staff',
                'operator_type' => 'user',
                'operators' => [1, 2]
            ],
            'manager_final_report_review' => [
                'owner' => 'staff',
                'operator_type' => 'role',
                'operators' => [1, 2]
            ],
            'manager_exit_discussion_review' => [
                'owner' => 'staff',
                'operator_type' => 'role',
                'operators' => [1, 2]
            ],
            'commissioner_final_report_review' => [
                'owner' => 'staff',
                'operator_type' => 'role',
                'operators' => [1, 2]
            ],
            'commissioner_exit_discussion_review' => [
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
                'to' => 'assign_officers',
                'condition' => '',
            ],
            'assign_officers' => [
                'from' => 'assign_officers',
                'to' => 'send_notification',
                'condition' => '',
            ],
            'send_notification_to_taxpayer' => [
                'from' => 'send_notification',
                'to' => 'conduct_verification',
                'condition' => '',
            ],
            'conduct_verification' => [
                'from' => 'conduct_verification',
                'to' => 'manager_verification_report_review',
                'condition' => '',
            ],
            'manager_verification_review_report_review' => [
                'from' => 'manager_verification_report_review',
                'to' => 'commissioner_verification_report_review',
                'condition' => '',
            ],
            'manager_verification_review_report_reject' => [
                'from' => 'manager_verification_report_review',
                'to' => 'conduct_verification',
                'condition' => '',
            ],
            'commissioner_verification_review_report_review' => [
                'from' => 'commissioner_verification_report_review',
                'to' => 'taxpayer_acceptance',
                'condition' => '',
            ],
            'commissioner_verification_review_report_reject' => [
                'from' => 'commissioner_verification_report_review',
                'to' => 'manager_verification_report_review',
                'condition' => '',
            ],
            'taxpayer_accepted' => [
                'from' => 'taxpayer_acceptance',
                'to' => 'officer_prepare_final_report',
                'condition' => '',
            ],
            'taxpayer_responded' => [
                'from' => 'taxpayer_acceptance',
                'to' => 'officer_prepare_notice_of_discussion',
                'condition' => '',
            ],
            'officer_prepare_final_report' => [
                'from' => 'officer_prepare_final_report',
                'to' => 'manager_final_report_review',
                'condition' => '',
            ],
            'exit_discussion' => [
                'from' => 'officer_prepare_notice_of_discussion',
                'to' => 'manager_exit_discussion_review',
                'condition' => '',
            ],
            'exit_discussion_correct' => [
                'from' => 'manager_exit_discussion_review',
                'to' => 'officer_prepare_notice_of_discussion',
                'condition' => '',
            ],
            'commissioner_exit_discussion_review' => [
                'from' => 'manager_exit_discussion_review',
                'to' => 'commissioner_exit_discussion_review',
                'condition' => '',
            ],
            'commissioner_exit_discussion_reject' => [
                'from' => 'commissioner_exit_discussion_review',
                'to' => 'manager_exit_discussion_review',
                'condition' => '',
            ],
            'commissioner_exit_discussion_approve' => [
                'from' => 'commissioner_exit_discussion_review',
                'to' => 'completed',
                'condition' => '',
            ],
            'manager_final_report_review' => [
                'from' => 'manager_final_report_review',
                'to' => 'commissioner_final_report_review',
                'condition' => '',
            ],
            'manager_final_report_reject' => [
                'from' => 'manager_final_report_review',
                'to' => 'officer_prepare_final_report',
                'condition' => '',
            ],
            'commissioner_final_report_review' => [
                'from' => 'commissioner_final_report_review',
                'to' => 'completed',
                'condition' => '',
            ],
            'commissioner_final_report_reject' => [
                'from' => 'commissioner_final_report_review',
                'to' => 'manager_final_report_review',
                'condition' => '',
            ],
        ];

        Workflow::updateOrCreate(
            [
                'code' => 'TAX_RETURN_VERIFICATION',
            ],
            [
                'code' => 'TAX_RETURN_VERIFICATION',
                'summary' => 'Tax return verification modal',
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
