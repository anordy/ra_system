@if ($row->status === \App\Models\BranchStatus::PENDING)
    <a href="{{ route('business.branches.show', encrypt($row->id)) }}" class="btn btn-outline-primary btn-sm">
        <i class="bi bi-eye-fill mr-1"></i> View & Approve
    </a>
@else
    <a href="{{ route('business.branches.show', encrypt($row->id)) }}" class="btn btn-outline-primary btn-sm">
        <i class="bi bi-eye-fill mr-1"></i> View
    </a>
@endif