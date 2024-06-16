@if ($value === 'complete')
    <span class="badge badge-success py-1 px-2 green-status">
        {{ __('PAID') }}
    </span>
@else
    <span class="badge badge-success py-1 px-2 danger-status">
        {{ $value }}
    </span>
@endif
