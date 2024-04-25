@if($statement->status === \App\Enum\StatementStatus::FAILED_SUBMISSION)
    <div class="px-3">
        <button class="btn btn-outline-dark mr-2" wire:click="confirmSubmission('Yes, Resend')" wire:loading.attr="disable">
            <i class="bi bi-arrow-repeat mr-2" wire:loading.remove wire:target="confirmSubmission"></i>
            <i class="spinner-border spinner-border-sm mr-2" role="status" wire:loading wire:target="submit"></i>
            Retry Submission
        </button>
    </div>
@endif