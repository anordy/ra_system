<a href="{{ route('payments.recons', encrypt($row->id)) }}" class="btn btn-info btn-sm">
    <i class="bi bi-eye-fill mr-1"></i>
    View
</a>

@if ($row->reconcstscode != 7101)
    <button class="btn btn-success btn-sm" wire:click="triggerResendReconModal({{ $row->id }})"><i class="bi bi-arrow-repeat"></i> Resend</button>
@endif
