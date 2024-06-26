<div>

    <div class="col-md-12 font-weight-bold mb-2 mt-4 px-0">{{__('PAYMENT SUMMARY')}}</div>

    <div class="col-md-12 px-0">
        <table class="table table-bordered">
            <tbody>
            <tr>
                <th width="20%">{{__('Bill Description')}}</th>
                <td colspan="2">{{ $taxType->name }} return for {{ $companyName }} - {{ $fillingMonth->name }}
                    {{ $fillingMonth->year->name }} </td>
            </tr>
            <tr>
                <th width="20%">{{ __('Bill Item') }}</th>
                <td>{{ $taxType->name }} {{ __('Return Amount') }}</td>
                <th class="text-right">{{ number_format($total, 2) ?? 0 }}</th>
            </tr>
            <tr class="bg-secondary">
                <th colspan="2">{{ __('Total Billed Amount') }}</th>
                <th class="text-right">
                    {{ number_format($total) ?? 0 }}
                    {{ $taxTypeCurrency }}</th>
            </tr>
            </tbody>
        </table>
    </div>


    <div class="col-md-12 text-right">
        <a wire:click='toggleSummary(false)' class="btn btn-danger mr-2">{{ __('Back') }}</a>
        <button type="button" class="btn btn-primary ml-2 px-5" wire:click='submit' wire:loading.attr="disabled">
            <div wire:loading.delay wire:target="submit">
                <div class="spinner-border mr-1 spinner-border-sm text-light" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>
            {{ __('Submit') }}
        </button>
    </div>
</div>
