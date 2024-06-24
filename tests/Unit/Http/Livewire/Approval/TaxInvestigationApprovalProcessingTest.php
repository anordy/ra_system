<?php

namespace Tests\Unit\Http\Livewire\Approval;

use App\Http\Livewire\Approval\TaxInvestigationApprovalProcessing;
use App\Models\TaxInvestigation;
use App\Models\TaxInvestigationOfficer;
use App\Models\TaxAssessment;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class TaxInvestigationApprovalProcessingTest extends TestCase
{
    use DatabaseMigrations, DatabaseTransactions;

    public function testApproveAssignOfficersValidation()
    {
        $investigation = TaxInvestigation::factory()->create();
        $teamLeader = User::factory()->create();
        $teamMember = User::factory()->create();

        $component = new TaxInvestigationApprovalProcessing($investigation);
        $component->transition = ['data' => ['transition' => 'assign_officers']];
        $component->teamLeader = $teamLeader->id;
        $component->teamMembers = $teamMember->id;
        $component->periodFrom = '2023-01-01';
        $component->periodTo = '2023-12-31';
        $component->intension = 'Test Intension';
        $component->scope = 'Test Scope';
        $component->comments = 'Test Comment';

        $component->approve($component->transition);

        $this->assertDatabaseHas('tax_investigation_officers', [
            'investigation_id' => $investigation->id,
            'user_id' => $teamLeader->id,
            'team_leader' => true,
        ]);

        $this->assertDatabaseHas('tax_investigation_officers', [
            'investigation_id' => $investigation->id,
            'user_id' => $teamMember->id,
            'team_leader' => false,
        ]);

        $this->assertDatabaseHas('tax_investigations', [
            'id' => $investigation->id,
            'period_from' => '2023-01-01',
            'period_to' => '2023-12-31',
            'intension' => 'Test Intension',
            'scope' => 'Test Scope',
        ]);
    }

    public function testApproveAssignOfficersValidationFailure()
    {
        $investigation = TaxInvestigation::factory()->create();
        $teamLeader = User::factory()->create();
        $teamMember = $teamLeader;

        $component = new TaxInvestigationApprovalProcessing($investigation);
        $component->transition = ['data' => ['transition' => 'assign_officers']];
        $component->teamLeader = $teamLeader->id;
        $component->teamMembers = $teamMember->id;
        $component->periodFrom = '2023-01-01';
        $component->periodTo = '2023-12-31';
        $component->intension = 'Test Intension';
        $component->scope = 'Test Scope';
        $component->comments = 'Test Comment';

        $component->approve($component->transition);

        $this->assertDatabaseMissing('tax_investigation_officers', [
            'investigation_id' => $investigation->id,
            'user_id' => $teamLeader->id,
            'team_leader' => true,
        ]);

        $this->assertDatabaseMissing('tax_investigation_officers', [
            'investigation_id' => $investigation->id,
            'user_id' => $teamMember->id,
            'team_leader' => false,
        ]);
    }

    public function testApproveAssessmentValidation()
    {
        $investigation = TaxInvestigation::factory()->create();
        $preliminaryReport = UploadedFile::fake()->create('preliminary_report.pdf', 100, 'application/pdf');
        $noticeOfDiscussion = UploadedFile::fake()->create('notice_of_discussion.pdf', 100, 'application/pdf');

        $component = new TaxInvestigationApprovalProcessing($investigation);
        $component->transition = ['data' => ['transition' => 'conduct_investigation']];
        $component->preliminaryReport = $preliminaryReport;
        $component->noticeOfDiscussion = $noticeOfDiscussion;
        $component->hasAssessment = true;
        $component->principalAmounts = ['Tax Type 1' => '1000'];
        $component->interestAmounts = ['Tax Type 1' => '200'];
        $component->penaltyAmounts = ['Tax Type 1' => '300'];
        $component->taxTypeIds = ['Tax Type 1' => 1];
        $component->comments = 'Test Comment';

        $component->approve($component->transition);

        $this->assertDatabaseHas('tax_assessments', [
            'assessment_id' => $investigation->id,
            'assessment_type' => get_class($investigation),
            'tax_type_id' => 1,
            'principal_amount' => 1000,
            'interest_amount' => 200,
            'penalty_amount' => 300,
            'total_amount' => 1500,
            'outstanding_amount' => 1500,
        ]);

        $this->assertDatabaseHas('tax_investigations', [
            'id' => $investigation->id,
            'preliminary_report' => $preliminaryReport->hashName(),
            'notice_of_discussion' => $noticeOfDiscussion->hashName(),
        ]);

        Storage::disk('local')->assertExists('investigation/' . $preliminaryReport->hashName());
        Storage::disk('local')->assertExists('investigation/' . $noticeOfDiscussion->hashName());
    }

    public function testApproveAssessmentValidationFailure()
    {
        $investigation = TaxInvestigation::factory()->create();
        $preliminaryReport = UploadedFile::fake()->create('preliminary_report.pdf', 100, 'application/pdf');
        $noticeOfDiscussion = UploadedFile::fake()->create('notice_of_discussion.pdf', 100, 'application/pdf');

        $component = new TaxInvestigationApprovalProcessing($investigation);
        $component->transition = ['data' => ['transition' => 'conduct_investigation']];
        $component->preliminaryReport = $preliminaryReport;
        $component->noticeOfDiscussion = $noticeOfDiscussion;
        $component->hasAssessment = true;
        $component->principalAmounts = ['Tax Type 1' => 'invalid'];
        $component->interestAmounts = ['Tax Type 1' => 'invalid'];
        $component->penaltyAmounts = ['Tax Type 1' => 'invalid'];
        $component->taxTypeIds = ['Tax Type 1' => 1];
        $component->comments = 'Test Comment';

        $component->approve($component->transition);

        $this->assertDatabaseMissing('tax_assessments', [
            'assessment_id' => $investigation->id,
            'assessment_type' => get_class($investigation),
            'tax_type_id' => 1,
        ]);

        $this->assertDatabaseMissing('tax_investigations', [
            'id' => $investigation->id,
            'preliminary_report' => $preliminaryReport->hashName(),
            'notice_of_discussion' => $noticeOfDiscussion->hashName(),
        ]);

        Storage::disk('local')->assertExists('investigation/' . $preliminaryReport->hashName());
        Storage::disk('local')->assertExists('investigation/' . $noticeOfDiscussion->hashName());
    }
}
