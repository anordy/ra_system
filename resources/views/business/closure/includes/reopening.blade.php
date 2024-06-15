@php
    $reopening_date = \Carbon\Carbon::create($row->reopening_date);
    $opening_date = \Carbon\Carbon::create($row->opening_date);
@endphp

@if ($reopening_date->lt($opening_date))
    <span class="badge badge-success py-1 px-2 green-status">
        <i class="bi bi-check-circle-fill mr-1"></i>
        Reopened Before
    </span>
@else
    <span class="badge badge-danger py-1 px-2 pending-status">
        <i class="bi bi-clock-history mr-1"></i>
        Reopened After
    </span>
@endif
