@props(['col' => 4, 'name', 'label' => '', 'type' => 'text', 'helper' => '', 'disabled' => false])

<div class="col-md-{{ $col ? $col : '4' }} form-group">
    <label for="{{ $name }}" class="{{ $helper ? 'd-flex justify-content-between' : '' }}">
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
    <input {{ $attributes }} id="{{ $name }}" type="{{ $type }}" wire:model.defer="{{ $name }}" class="form-control {{ $errors->has($name) ?'is-invalid' : '' }}" @if($disabled) disabled @endif>
    @error($name)
        <div class="invalid-feedback">
            {{ $message }}
        </div>
    @enderror
</div>