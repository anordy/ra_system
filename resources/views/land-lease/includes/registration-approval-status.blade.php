@if ($row->approval_status == 'approved')
    <span class="badge badge-success py-1 px-2"
        style="border-radius: 1rem; background: #72DC3559; color: #319e0a; font-size: 85%">
        {{ __('Approved') }}
    </span>
@elseif($row->approval_status == 'pending')
    <span class="badge badge-warning py-1 px-2">
        {{ __('Pending') }}
    </span>
@elseif($row->approval_status == 'rejected')
    <span class="badge badge-danger py-1 px-2"
        style="border-radius: 1rem; background: #dc354559; color: #cf1c2d; font-size: 85%">
        {{ __('Rejected') }}
    </span>
@else
    <span class="badge badge-primary py-1 px-2">
        {{ __('Unknown') }}
    </span>
@endif
