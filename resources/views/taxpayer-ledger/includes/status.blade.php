@if(isset($row->status))
    @if($row->status === 'approved')
        <span class="badge badge-success py-1 px-2">
                <i class="bi bi-check-circle-fill mr-1"></i>
                {{ strtoupper($row->status) }}
</span>
    @elseif($row->status === 'pending')
        <span class="badge badge-warning py-1 px-2">
                <i class="bi bi-check-circle-fill mr-1"></i>
                {{ strtoupper($row->status) }}
</span>
    @elseif($row->status === 'cancelled')
        <span class="badge badge-danger py-1 px-2">
                <i class="bi bi-check-circle-fill mr-1"></i>
                {{ strtoupper($row->status) }}
</span>
    @else
        <span class="badge badge-warning py-1 px-2">
                <i class="bi bi-check-circle-fill mr-1"></i>
                {{ strtoupper($row->status) ?? 'N/A' }}
</span>
    @endif
@else
    <span class="badge badge-warning py-1 px-2">
                <i class="bi bi-check-circle-fill mr-1"></i>
    N/A
@endif



