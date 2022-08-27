{{-- @if($business_id && $business_status === \App\Models\BusinessStatus::CORRECTION)
     <livewire:approval.approval-processing modelName='App\Models\Business' modelId="{{ $business_id }}" />
@endif --}}

<div class="steps my-3 bg-white">
    <div class="steps-header">
        <div class="progress">
            <div class="progress-bar" role="progressbar" style="width: {{ $width }}%" aria-valuenow="{{ $width  }} }}"
                aria-valuemin="0" aria-valuemax="100"></div>
        </div>
    </div>
    <div class="steps-body">
        @foreach ($steps as $step)
            <div class="step {{ $step->isCurrent() ? 'step-active':''}}">
                <span class="step-icon">
                    <i class="{{ $step->icon ? $step->icon : 'fas fa-user'}}"></i>
                </span>
                {{ $step->label }}
            </div>
        @endforeach
    </div>
</div>

