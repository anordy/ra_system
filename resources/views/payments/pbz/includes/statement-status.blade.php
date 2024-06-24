@if ($row->status === \App\Enum\StatementStatus::PENDING)
    <span class="badge badge-secondary py-1 px-2">
        <i class="bi bi-stop-circle mr-1"></i>
        Pending
    </span>
@elseif ($row->status === \App\Enum\StatementStatus::SUBMITTED)
    <span class="badge badge-info py-1 px-2">
        <i class="bi bi-stop-circle mr-1"></i>
        Submitted
    </span>
@elseif ($row->status === \App\Enum\StatementStatus::FAILED_SUBMISSION)
    <span class="badge badge-danger py-1 px-2">
        <i class="bi bi-stop-circle mr-1"></i>
        Failed Submission
    </span>
@elseif ($row->status === \App\Enum\StatementStatus::FAILED)
    <span class="badge badge-danger py-1 px-2">
        <i class="bi bi-stop-circle mr-1"></i>
        Failed
    </span>
@elseif ($row->status === \App\Enum\StatementStatus::SUCCESS)
    <span class="badge badge-success py-1 px-2">
        <i class="bi bi-stop-circle mr-1"></i>
        Success
    </span>
@else
    <span class="badge badge-info py-1 px-2">
        {{ $row->status }}
    </span>
@endif