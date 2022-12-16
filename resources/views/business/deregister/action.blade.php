@if ($row->status === 'pending')
    <a href="{{ route('business.viewDeregistration', encrypt($row->id)) }}" class="btn btn-outline-info btn-sm" onclick="">
        View & Approve
    </a>
@else
    <a href="{{ route('business.viewDeregistration', encrypt($row->id)) }}" class="btn btn-outline-info btn-sm" onclick="">View</a>
@endif
