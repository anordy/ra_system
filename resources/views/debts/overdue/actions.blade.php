<a href="{{ route('debts.debt.showOverdue', encrypt($row->id)) }}" class="btn btn-outline-primary btn-sm">
    <i class="bi bi-eye-fill mr-1"></i> View
</a>
@if ($row->recovery_measure_status == 'none')
    <a href="{{ route('debts.debt.recovery', encrypt($row->id)) }}" class="btn btn-outline-primary btn-sm">
        <i class="bi bi-arrows-collapse mr-1"></i> Recovery Measure
    </a>
@endif
