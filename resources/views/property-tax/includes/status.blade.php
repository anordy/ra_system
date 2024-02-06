@if ($row->status === \App\Enum\PropertyStatus::APPROVED)
    <span class="badge badge-success py-1 px-2"
          style="border-radius: 1rem; background: #72DC3559; color: #319e0a; font-size: 85%">
        <i class="bi bi-check-circle-fill mr-1"></i>
        Approved
    </span>
@elseif($row->status === \App\Enum\PropertyStatus::PENDING)
    <span class="badge badge-warning py-1 px-2"
          style="border-radius: 1rem; background: rgba(53,220,220,0.35); color: #1caecf; font-size: 85%">
        <i class="bi bi-clock-history mr-1"></i>
        Pending
    </span>
@elseif($row->status === \App\Enum\PropertyStatus::CORRECTION)
    <span class="badge badge-warning py-1 px-2"
          style="border-radius: 1rem; background: rgba(53,220,220,0.35); color: #1caecf; font-size: 85%">
        <i class="bi bi-clock-history mr-1"></i>
        On Correction
    </span>
@else
    <span class="badge badge-info py-1 px-2"
          style="border-radius: 1rem; background: rgba(53,220,220,0.35); color: #1caecf; font-size: 85%">
        <i class="bi bi-clock-history mr-1"></i>
        Unknown Status
    </span>
@endif
