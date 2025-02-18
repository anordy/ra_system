<<<<<<< HEAD
@props(['col' => 4, 'name', 'options', 'label' => '', 'accessor' => 'name', 'value' => 'id'])
=======
<<<<<<< HEAD
@props(['col' => 4, 'name', 'options', 'label' => '', 'accessor' => 'name', 'value' => 'id', 'disabled' => false])
=======
@props(['col' => 4, 'name', 'options', 'label' => '', 'accessor' => 'name', 'value' => 'id'])
>>>>>>> 888f827d6 (merge from feature/mvr_transfer_ownership)
>>>>>>> a44bf408c (merge from feature/mvr_transfer_ownership)

<div class="col-md-{{ $col ? $col : '4' }} form-group">
    <label for="{{ $name }}">
        {{ $label ? $label : ucwords(Str::of($name)->kebab()->replace('_', ' ')) }}
        {{ $attributes->get('required') ? '*' : '' }}
    </label>
<<<<<<< HEAD
    <select {{ $attributes }} id="{{ $name }}" wire:model="{{ $name }}" class="form-control {{ $errors->has($name) ?'is-invalid' : '' }}">
=======
<<<<<<< HEAD
    <select {{ $attributes }} id="{{ $name }}" wire:model="{{ $name }}" class="form-control {{ $errors->has($name) ?'is-invalid' : '' }}" @if($disabled) disabled @endif>
=======
    <select {{ $attributes }} id="{{ $name }}" wire:model="{{ $name }}" class="form-control {{ $errors->has($name) ?'is-invalid' : '' }}">
>>>>>>> 888f827d6 (merge from feature/mvr_transfer_ownership)
>>>>>>> a44bf408c (merge from feature/mvr_transfer_ownership)
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