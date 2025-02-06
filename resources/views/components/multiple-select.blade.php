@props(['col' => 4, 'name', 'options', 'label' => '', 'accessor' => 'name', 'value' => 'id'])

<div class="col-md-{{ $col ? $col : '4' }} form-group">
    <label for="{{ $name }}">
        {{ $label ? $label : ucwords(Str::of($name)->kebab()->replace('_', ' ')) }}
        {{ $attributes->get('required') ? '*' : '' }}
    </label>
    <select multiple {{ $attributes }} id="{{ $name }}" wire:model="{{ $name }}" class="form-control {{ $errors->has($name) ?'is-invalid' : '' }}">
        <option value="">Choose...</option>
        @foreach($options ?? [] as $option)
            <option value="{{ $option->$value }}">{{ $option->$accessor }}</option>
        @endforeach
    </select>
    @error($name)
        <div class="invalid-feedback">
            {{ $message }}
        </div>
    @enderror
</div>