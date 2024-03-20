@if ($row->status == 'pending')
    <span class="badge badge-danger p-2">Pending</span>
@elseif($row->status == 'approved')
    <span class="badge badge-success p-2">Approved</span>
@else
    <span class="badge badge-danger p-2">Rejected</span>
@endif