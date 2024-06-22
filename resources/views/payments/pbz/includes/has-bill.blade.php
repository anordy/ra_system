@if ($row->bill)
    <span class="badge badge-success py-1 px-2">
        <i class="bi bi-check-circle-fill"></i>
        YES
    </span>
@else
    <span class="badge badge-danger py-1 px-2">
        <i class="bi bi-x-circle-fill"></i>
        NO
    </span>
@endif