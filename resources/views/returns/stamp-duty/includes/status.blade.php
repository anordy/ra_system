@if ($value === 'complete')
    <span class="badge badge-success py-1 px-2"  style="border-radius: 1rem; background: #72DC3559; color: #319e0a; font-size: 85%">
    {{ __('PAID') }}
</span>
@else
    <span class="badge badge-success py-1 px-2"  style="border-radius: 1rem; background: #dc354559; color: #cf1c2d;; font-size: 85%">
    {{ $value }}
</span>
@endif
