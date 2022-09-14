@if ($row->status === 'pending')
    <a href="{{ route('business.viewClosure', encrypt($row->id)) }}" class="btn btn-info btn-sm" onclick="">View & Approve</a>
@else
    <a href="{{ route('business.viewClosure', encrypt($row->id)) }}" class="btn btn-info btn-sm" onclick="">View</a>
@endif
