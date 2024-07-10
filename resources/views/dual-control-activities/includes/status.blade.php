@if ($row->status == 'pending')
    <span class="badge badge-danger px-2 py-1">Pending</span>
@elseif($row->status == 'approved')
    <span class="badge badge-success p-2">Approved</span>
@else
    <span class="badge badge-danger px-2 py-1">Rejected</span>
@endif