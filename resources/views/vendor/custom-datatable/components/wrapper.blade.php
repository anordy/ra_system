@props(['component'])

@php
    $refresh = $this->getRefreshStatus();
    $theme = $component->getTheme();
@endphp

<div>
    <div {{ $attributes->merge($this->getComponentWrapperAttributes()) }}
        @if ($component->hasRefresh()) wire:poll{{ $component->getRefreshOptions() }} @endif
        @if ($component->isFilterLayoutSlideDown()) wire:ignore.self @endif>

        @include('livewire-tables::includes.debug')
        @include('livewire-tables::includes.offline')

        {{ $slot }}
    </div>
</div>
