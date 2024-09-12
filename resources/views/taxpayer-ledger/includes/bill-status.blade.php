@if(isset($row->latestBill->status))
    @if($row->latestBill->status === 'paid')
        <span class="badge badge-success py-1 px-2">
                <i class="bi bi-check-circle-fill mr-1"></i>
                {{ strtoupper($row->latestBill->status) }}
</span>
    @elseif($row->latestBill->status === 'pending')
        <span class="badge badge-warning py-1 px-2">
                <i class="bi bi-check-circle-fill mr-1"></i>
                {{ strtoupper($row->latestBill->status) }}
</span>
    @elseif($row->latestBill->status === 'cancelled')
        <span class="badge badge-danger py-1 px-2">
                <i class="bi bi-check-circle-fill mr-1"></i>
                {{ strtoupper($row->latestBill->status) }}
</span>
    @else
        <span class="badge badge-warning py-1 px-2">
                <i class="bi bi-check-circle-fill mr-1"></i>
                {{ strtoupper($row->latestBill->status) ?? 'N/A' }}
</span>
    @endif
@else
    <span class="badge badge-warning py-1 px-2">
                <i class="bi bi-check-circle-fill mr-1"></i>
    N/A
@endif



