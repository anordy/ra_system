@if ($row->status === 'pending')
    @if ($row->debt_type === 'App\Models\TaxAssessments\TaxAssessment')
    <a href="{{ route('debts.assessments.waivers.approval', encrypt($row->id)) }}" class="btn btn-outline-primary btn-sm">
        <i class="bi bi-eye-fill mr-1"></i> View & Approve
    </a>
    @elseif($row->debt_type === 'App\Models\Returns\TaxReturn')
    <a href="{{ route('debts.returns.waivers.approval', encrypt($row->id)) }}" class="btn btn-outline-primary btn-sm">
        <i class="bi bi-eye-fill mr-1"></i> View & Approve
    </a>
    @endif
  
@else
    <a href="{{ route('debts.returns.waivers.approval', encrypt($row->id)) }}" class="btn btn-outline-primary btn-sm">
        <i class="bi bi-eye-fill mr-1"></i> View
    </a>
@endif
