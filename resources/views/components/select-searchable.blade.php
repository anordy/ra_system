@props([
    'options',
    'name',
    'col' => 4,
    'placeholder' => 'Select an option',
    'label' => '',
    'showLabel' => 'true',
    'optionNameAccessor' => 'name',
    'optionValueAccessor' => 'id'
])

<div x-data="{
        open: false,
        search: '',
        selectedId: @entangle($name),
        options: @js($options),
        get filteredOptions() {
            if (!this.search.trim()) return this.options;
            return this.options.filter(option => option['{{ $optionNameAccessor }}'].toLowerCase().includes(this.search.toLowerCase()));
        },
        get selectedOption() {
            if (!this.selectedId) return null;
            return this.options.find(option => option['{{ $optionValueAccessor }}'] == this.selectedId);
        },
        selectOption(option) {
            this.selectedId = option['{{ $optionValueAccessor }}'];
            this.search = '';
            this.open = false;
        }
    }"
     x-init="$watch('selectedId', value => open = false)"
     class="col-md-{{ $col }}"
>
    @if($showLabel == 'true')
        <label class="form-label">
            {{ $label ? $label : ucwords(Str::of($name)->kebab()->replace('_', ' ')) }}
            {{ $attributes->get('required') ? '*' : '' }}
        </label>
    @endif
    <div  class="position-relative relative">
        <button @click="open = !open" type="button"
                class="form-control d-flex justify-content-between @error($name) border-danger @endif">
            <span class="block truncate text-start" x-text="selectedOption ? selectedOption['{{ $optionNameAccessor }}'] : '{{ $placeholder }}'"></span>
            <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2">
                @error($name)
                    <i class="bi bi-exclamation-circle text-danger"></i>
                @else
                    <i class="bi bi-chevron-expand"></i>
                @endif
            </span>
        </button>

        <div x-show="open"
             x-transition
             @click.away="open = false" x-cloak
             class="position-absolute z-9999 w-100 overflow-auto bg-white text-base shadow-lg">
            <input type="text"
                   x-trap="open"
                   x-model="search"
                   placeholder="Type here to search..."
                   class="form-control border-0 border-bottom border-dark-subtle bg-white pb-2 ps-3 text-left">
            <ul class="max-height-50vh overflow-auto ps-0 mb-0">
                <template x-for="option in filteredOptions"
                          :key="option['{{ $optionValueAccessor }}']">
                    <li @click="selectOption(option);"
                        class="cursor-pointer list-unstyled select-none searchable-item py-2 ps-3 pr-9 text-start"
                        :class="{'bg-primary-subtle fw-bold': selectedOption && selectedOption['{{ $optionValueAccessor }}'] == option['{{ $optionValueAccessor }}']}">
                        <span x-text="option['{{ $optionNameAccessor }}']" class="font-normal block truncate"></span>
                    </li>
                </template>
            </ul>
        </div>

        @error($name)
            <div class="text-danger pt-1 small">
                {{ $message }}
            </div>
        @enderror
    </div>
</div>
