<div>
        {{--    <form wire:submit.prevent='submitForm'>--}}
        @livewire($currentStepName, $currentStepState, key($currentStepName))
        {{--    </form>--}}
</div>