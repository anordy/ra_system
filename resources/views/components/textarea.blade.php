@props(['col' => 4, 'name', 'label' => '', 'type' => 'text', 'helper' => '', 'value' => ''])

<div class="col-md-{{ $col ? $col : '4' }} form-group mb-3">
    <label for="{{ $name }}" class="{{ $helper ? 'd-flex justify-content-between' : '' }} form-label">
        <span>
            {{ $label ? $label : ucwords(Str::of($name)->kebab()->replace('_', ' ')) }}
            {{ $attributes->get('required') ? '*' : '' }}
        </span>
        @if($helper)
            <a type="button" class="" data-toggle="tooltip" data-placement="top" title="Tooltip on topTooltip on topTooltip on topTooltip on topTooltip on top">
                <i class="bi bi-question-circle"></i>
            </a>
        @endif
    </label>
    <textarea {{ $attributes }} id="{{ $name }}" type="{{ $type }}" wire:model.defer="{{ $name }}" class="form-control {{ $errors->has($name) ?'is-invalid' : '' }}">{{ $value }}</textarea>
    @error($name)
    <div class="invalid-feedback">
        {{ $message }}
    </div>
    @enderror
</div>
