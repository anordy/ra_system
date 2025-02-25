@props([
    'col' => 4,
    'name',
    'options',
    'label' => '',
    'accessor' => 'name',
    'value' => 'id',
    'live' => 'false',
    'showDefault' => 'true',
    'placeholder' => 'Choose..',
    'showLabel' => true,
    'mb' => 3
])

<div class="col-md-{{ $col ? $col : '4' }} form-group">
    @if($showLabel)
        <label for="{{ $name }}" class="form-label">
            {{ $label ? $label : ucwords(Str::of($name)->kebab()->replace('_', ' ')) }}
            {{ $attributes->get('required') ? '*' : '' }}
        </label>
    @endif
    <select {{ $attributes }} id="{{ $name }}" wire:model{{ $live == 'true' ? '.live' : '' }}="{{ $name }}"
            class="form-control mb-{{$mb}} {{ $errors->has($name) ?'is-invalid' : '' }}">
        @if($showDefault=='true')
            <option value="">{{$placeholder}}</option>
        @endif
        @foreach($options as $option)
            <option value="{{ encryptPayload($option->$value) }}">{{ $option->$accessor }}</option>
        @endforeach
    </select>

    @error($name)
    <div class="invalid-feedback">
        {{ $message }}
    </div>
    @enderror
</div>
