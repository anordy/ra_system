@php
    $reopening_date = \Carbon\Carbon::create($row->reopening_date);
    $opening_date = \Carbon\Carbon::create($row->opening_date);
@endphp

@if ($reopening_date->lt($opening_date))
    <span class="badge badge-success py-1 px-2"
        style="border-radius: 1rem; background: #72DC3559; color: #319e0a; font-size: 85%">
        <i class="bi bi-check-circle-fill mr-1"></i>
        Reopened Before
    </span>
@else
    <span class="badge badge-danger py-1 px-2"
        style="border-radius: 1rem; background: rgba(53,220,220,0.35); color: #1caecf; font-size: 85%">
        <i class="bi bi-clock-history mr-1"></i>
        Reopened After
    </span>
@endif
