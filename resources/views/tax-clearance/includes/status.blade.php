@if ($value === 'requested')
    <span class="badge badge-warning py-1 px-2"
        style="border-radius: 1rem; background: #d97706; color: #facc15; font-size: 85%">
        {{ $value }}
    </span>
@elseif ($value === 'denied')
    <span class="badge badge-success py-1 px-2"
        style="border-radius: 1rem; background: #72DC3559; color: #319e0a; font-size: 85%">
        {{ $value }}
    </span>
@elseif ($value === 'approved')
    <span class="badge badge-danger py-1 px-2"
        style="border-radius: 1rem; background: #dc354559; color: #cf1c2d;; font-size: 85%">
        {{ $value }}
    </span>
@endif