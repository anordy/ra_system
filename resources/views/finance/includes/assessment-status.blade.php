@if ($debt->assessment_step == \App\Enum\TaxAssessmentStep::NORMAL)
    <span class="badge badge-success py-1 px-2"
        style="border-radius: 1rem; background: #10b981; color: #065f46; font-size: 85%">
        <i class="bi bi bi-x-circle-fill mr-1"></i>
        Normal
    </span>
@elseif($debt->assessment_step == \App\Enum\TaxAssessmentStep::DEBT)
    <span class="badge badge-success py-1 px-2"
        style="border-radius: 1rem; background: #fbbf24; color: #d97706; font-size: 85%">
        <i class="bi bi-check-circle-fill mr-1"></i>
        Debt
    </span>
@elseif($debt->assessment_step == \App\Enum\TaxAssessmentStep::OVERDUE)
    <span class="badge badge-success py-1 px-2"
        style="border-radius: 1rem; background: #f43f5e; color: #be123c; font-size: 85%">
        <i class="bi bi-check-circle-fill mr-1"></i>
        Overdue
    </span>
@endif
