
<a href="{{ route('settings.dual-control-activities.show', encrypt($value)) }}" class="btn btn-outline-primary btn-sm">
    <i class="bi bi-eye-fill mr-1"></i>@if($row->status == 'pending') View & Approve @else View @endif
</a>