@if ($debt->assessment_step == \App\Enum\TaxAssessmentStep::NORMAL)
    <span class="badge badge-success py-1 px-2 green-status">
        <i class="bi bi bi-x-circle-fill mr-1"></i>
        Normal
    </span>
@elseif($debt->assessment_step == \App\Enum\TaxAssessmentStep::DEBT)
    <span class="badge badge-success py-1 px-2 pending-status">
        <i class="bi bi-check-circle-fill mr-1"></i>
        Debt
    </span>
@elseif($debt->assessment_step == \App\Enum\TaxAssessmentStep::OVERDUE)
    <span class="badge badge-success py-1 px-2 danger-status">
        <i class="bi bi-check-circle-fill mr-1"></i>
        Overdue
    </span>
@endif
