@if($row->no_of_days == null)
    <span class="badge badge-info py-1 px-2"  style="border-radius: 1rem; background: #3582dc59; color: #0a5e9e; font-size: 85%">
        <i class="bi bi-check-circle-fill mr-1"></i>
        Not applicable
    </span>
@else
    <span >
        {{$row->no_of_days}}
        @if($row->duration== 'yearly')
            years
        @elseif($row->duration== 'monthly')
            months
        @else
            days
        @endif
    </span>
@endif