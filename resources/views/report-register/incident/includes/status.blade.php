@if($status === \App\Enum\ReportRegister\RgStatus::IN_PROGRESS)
    <span class="badge badge-warning">In Progress</span>
@elseif($status === \App\Enum\ReportRegister\RgStatus::ON_HOLD)
    <span class="badge badge-info">On Hold</span>
@elseif($status === \App\Enum\ReportRegister\RgStatus::RESOLVED)
    <span class="badge badge-success">Resolved</span>
@elseif($status === \App\Enum\ReportRegister\RgStatus::EXTERNAL_SUPPORT)
    <span class="badge badge-primary">External Support</span>
@elseif($status === \App\Enum\ReportRegister\RgStatus::SUBMITTED)
    <span class="badge badge-info">Submitted</span>
@else
    <span class="badge badge-primary">{{ strtoupper($status) }}</span>
@endif