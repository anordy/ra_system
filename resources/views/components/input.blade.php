@props([
        'col' => 4,
        'name',
        'label' => '',
        'type' => 'text',
        'helper' => '',
        'showLabel' => 'true',
        'mb' => 3,
        'live' => false,
    ])

<div class="col-md-{{ $col ? $col : '4' }} form-group mb-{{ $mb }}">
    @if($showLabel == 'true')
        <label for="{{ $name }}" class="{{ $helper ? 'd-flex justify-content-between' : '' }} form-label">
        <span>
            {{ $label ? $label : ucwords(Str::of($name)->kebab()->replace('_', ' ')) }}
            {{ $attributes->get('required') ? '*' : '' }}
        </span>
            @if($helper)
                <a type="button" class="" data-toggle="tooltip" data-placement="top" title="{{ $helper }}">
                    <i class="bi bi-question-circle"></i>
                </a>
            @endif
        </label>
    @endif

    @if($live)
        <input {{ $attributes }} id="{{ $name }}" type="{{ $type }}"
               wire:model.live="{{ $name }}"
               class="form-control {{ $errors->has($name) ?'is-invalid' : '' }}">
    @else
        <input {{ $attributes }} id="{{ $name }}" type="{{ $type }}"
               wire:model="{{ $name }}"
               class="form-control {{ $errors->has($name) ?'is-invalid' : '' }}">
    @endif

    @error($name)
    <div class="invalid-feedback">
        {{ $message }}
    </div>
    @enderror
</div>
