@if ($row->status === \App\Models\BusinessStatus::APPROVED)
    <span class="badge badge-success py-1 px-2"
          style="border-radius: 1rem; background: #72DC3559; color: #319e0a; font-size: 85%">
                <i class="bi bi-check-circle-fill mr-1"></i>
                {{ __('Active') }}
            </span>
@elseif($row->status === \App\Models\BusinessStatus::PENDING)
    <span class="badge badge-danger py-1 px-2"
          style="border-radius: 1rem; background: rgba(53,220,220,0.35); color: #1caecf; font-size: 85%">
        <i class="bi bi-clock-history mr-1"></i>
        {{ __('Pending') }}
    </span>
@elseif($row->status === \App\Models\BusinessStatus::DEREGISTERED)
    <span class="badge badge-danger py-1 px-2"
          style="border-radius: 1rem; background: rgba(220,53,53,0.35); color: #cf1c1c; font-size: 85%">
        <i class="bi bi-record-circle mr-1"></i>
        {{ __('De-registered') }}
    </span>
@elseif($row->status === \App\Models\BusinessStatus::REJECTED)
    <span class="badge badge-danger py-1 px-2"
          style="border-radius: 1rem; background: rgba(220,53,53,0.35); color: #cf1c1c; font-size: 85%">
        <i class="bi bi-record-circle mr-1"></i>
        {{ __('Rejected') }}
    </span>
@else
    <span class="badge badge-danger py-1 px-2"
          style="border-radius: 1rem; background: rgba(220,53,53,0.35); color: #cf1c1c; font-size: 85%">
        <i class="bi bi-record-circle mr-1"></i>
        Unknown Status
    </span>
@endif
