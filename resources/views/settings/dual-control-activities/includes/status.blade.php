@if ($row->status == 'pending')
    <span style=" background: #dc354559; color: #cf1c2d; font-size: 85%"
          class="badge badge-danger p-2">Pending</span>
@elseif($row->status == 'approved')
    <span style=" background: #72DC3559; color: #319e0a; font-size: 85%"
          class="badge badge-success p-2">Approved</span>
@else
    <span style=" background: #dc354559; color: #cf1c2d; font-size: 85%"
          class="badge badge-danger p-2">Rejected</span>
@endif