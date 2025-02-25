@props(['col' => 3, 'label', 'url', 'targeted' => true])

<div class="col-md-{{ $col }}">
    <div style="background: #faf5f5; color: #036a9e; border: .5px solid #036a9e24;" class="p-2 mb-3 d-flex rounded-sm align-items-center">
        <i class="bi bi-file-earmark-pdf-fill px-2" style="font-size: x-large"></i>
        <a href="{{ $url }}" style="font-weight: 500;" class="ml-1" @if($targeted) target="_blank" @endif>
            {{ $label }}
            <i class="bi bi-arrow-up-right-square ml-1"></i>
        </a>
    </div>
</div>
