@if ($value === \App\Enum\BillStatus::COMPLETE)
    <span class="badge badge-success py-1 px-2">
        {{ __('PAID') }}
    </span>
@else
    <span class="badge badge-success py-1 px-2">
        {{ $value }}
    </span>
@endif
