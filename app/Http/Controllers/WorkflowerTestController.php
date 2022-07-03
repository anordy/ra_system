<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\User;
use App\Services\Workflow\Events\WorkflowSubscriber;
use App\Services\Workflow\WorkflowRegistry;

class WorkflowerTestController extends Controller
{
    public function index()
    {
        $flow = [
            'business_registration'   => [
                'type'          => 'workflow',
                'marking_store' => [
                    'type'      => 'multiple_state',
                    'property'  => ['marking']
                ],
                'initial_marking' => 'draft',
                'supports'      => ['App\Models\Business'],
                'places'        => [
                    'draft' => [
                        'owner' => 'taxpayer',
                        'operator_type' => 'user',
                        'operators' => []
                    ],
                    'taxpayer' => [
                        'owner' => 'staff',
                        'operator_type' => 'user',
                        'operators' => []
                    ],
                    'registration_officer' => [
                        'owner' => 'staff',
                        'operator_type' => 'role',
                        'operators' => []
                    ],
                    'registration_manager' => [
                        'owner' => 'staff',
                        'operator_type' => 'role',
                        'operators' => []
                    ],
                    'director_of_trai' => [
                        'owner' => 'staff',
                        'operator_type' => 'role',
                        'operators' => []
                    ],
                     'completed' => [
                        'owner' => 'staff',
                        'operator_type' => 'role',
                        'operators' => []
                    ],
                ],
                'transitions'   => [
                    'application_submitted' => [
                        'condition' => '',
                        'from' => 'draft',
                        'to'   => 'registration_officer',
                    ],
                    'application_filled_incorrect' => [
                        'condition' => '',
                        'from' => 'registration_officer',
                        'to'   => 'taxpayer'
                    ],
                    'registration_officer_review' => [
                        'condition' => '',
                        'from' => 'registration_officer',
                        'to'   => 'registration_manager',
                    ],
                    'application_corrected' => [
                        'condition' => '',
                        'from' => 'taxpayer',
                        'to'   => 'registration_officer'
                    ],
                    'registration_manager_review' => [
                        'condition' => '',
                        'from' => 'registration_manager',
                        'to'   => 'director_of_trai'
                    ],
                    'director_of_trai_review' => [
                        'condition' => '',
                        'from' => 'director_of_trai',
                        'to'   => 'completed'
                    ]
                ],
            ]
        ];

        $registry = new WorkflowRegistry($flow, new WorkflowSubscriber());
        $post = Business::find(1);
        $workflow = $registry->get($post);


        $workflow->apply($post, 'submitted');
        $post->save();
    }
}
