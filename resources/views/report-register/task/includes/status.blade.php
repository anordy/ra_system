@if($status === \App\Enum\ReportRegister\RgTaskStatus::CREATED)
    <span class="badge badge-primary">Created</span>
@elseif($status === \App\Enum\ReportRegister\RgTaskStatus::PENDING)
    <span class="badge badge-warning">In Progress</span>
@elseif($status === \App\Enum\ReportRegister\RgTaskStatus::CLOSED)
    <span class="badge badge-success">Closed</span>
@elseif($status === \App\Enum\ReportRegister\RgTaskStatus::CANCELLED)
    <span class="badge badge-danger">Cancelled</span>
@else
    <span class="badge badge-primary">{{ strtoupper($status) }}</span>
@endif
