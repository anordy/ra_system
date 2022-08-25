<a href="{{ route('debts.debt.recovery', encrypt($row->id)) }}" class="btn btn-outline-primary btn-sm">
    <i class="bi bi-eye-fill mr-1"></i> Assign Recovery Measure
</a>

<a href="{{ route('debts.debt.show', encrypt($row->id)) }}" class="btn btn-outline-primary btn-sm">
    <i class="bi bi-eye-fill mr-1"></i> Show
</a>