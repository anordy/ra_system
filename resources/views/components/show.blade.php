@props(['col' => 3, 'label', 'value', 'type' => 'default'])

<div class="col-md-{{ $col }} mb-3">
    <span class="fw-bold text-uppercase">{{ $label }}</span>
    <p class="my-1">{{ $value }}</p>
</div>
