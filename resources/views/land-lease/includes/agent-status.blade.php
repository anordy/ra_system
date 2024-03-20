@if ($row->status=='ACTIVE')
    <span class="badge badge-success py-1 px-2 green-status">
        {{-- <i class="bi bi-check-circle-fill mr-1"></i> --}}
        ACTIVE
    </span>
@else
    <span class="badge badge-danger py-1 px-2 danger-status">
        {{-- <i class="bi bi-x-circle-fill mr-1"></i> --}}
        INACTIVE
    </span>
@endif
