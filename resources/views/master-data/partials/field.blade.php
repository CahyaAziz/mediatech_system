{{--
    Field form master data.
    Parameter: name, label, type (text|email|number|textarea|select|checkbox), value,
    required, full, placeholder, options (list atau [value => label]), hint, maxlength.
--}}
@php
    $type = $type ?? 'text';
    $required = $required ?? false;
    $placeholder = $placeholder ?? '';
    $current = old($name, $value ?? null);
    $inputClass = 'w-full rounded-lg border bg-white px-3 text-[12px] outline-none focus:border-primary-300 focus:ring-2 focus:ring-primary-50 ' .
        ($errors->has($name) ? 'border-error-500' : 'border-[#dedfe6]');
@endphp

@if ($type === 'checkbox')
    <label
        class="flex cursor-pointer items-start gap-3 rounded-lg border border-[#eceef3] p-3 transition hover:border-primary-100 hover:bg-[#fafbff] {{ ($full ?? false) ? 'sm:col-span-2' : '' }}">
        <input type="hidden" name="{{ $name }}" value="0">
        <input type="checkbox" name="{{ $name }}" value="1" @checked((bool) $current)
            class="mt-0.5 h-4 w-4 rounded border-[#bfc3d2] text-primary-500 focus:ring-primary-100">
        <span class="min-w-0 flex-1"><span
                class="block text-[11px] font-bold text-neutral-800">{{ $label }}</span>
            @if (!empty($hint))
                <span class="mt-1 block text-[10px] leading-relaxed text-neutral-500">{{ $hint }}</span>
            @endif
        </span>
    </label>
@else
    <div class="{{ ($full ?? false) ? 'sm:col-span-2' : '' }}">
        <label for="{{ $name }}" class="mb-2 block text-[11px] font-bold text-neutral-700">{{ $label }}
            @if ($required)
                <span class="text-[#b54740]">*</span>
            @endif
        </label>
        @if ($type === 'textarea')
            <textarea id="{{ $name }}" name="{{ $name }}" @if ($required) required @endif
                @isset($maxlength) maxlength="{{ $maxlength }}" @endisset placeholder="{{ $placeholder }}"
                class="{{ $inputClass }} min-h-20 py-2.5">{{ $current }}</textarea>
        @elseif ($type === 'select')
            <select id="{{ $name }}" name="{{ $name }}" @if ($required) required @endif
                class="{{ $inputClass }} h-10 text-neutral-700">
                @if ($placeholder !== '')
                    <option value="">{{ $placeholder }}</option>
                @endif
                @foreach ($options as $optionValue => $optionLabel)
                    @php $optionValue = array_is_list($options) ? $optionLabel : $optionValue; @endphp
                    <option value="{{ $optionValue }}" @selected((string) $current === (string) $optionValue)>
                        {{ $optionLabel }}</option>
                @endforeach
            </select>
        @else
            <input id="{{ $name }}" name="{{ $name }}" type="{{ $type }}" value="{{ $current }}"
                placeholder="{{ $placeholder }}" @if ($required) required @endif
                @isset($maxlength) maxlength="{{ $maxlength }}" @endisset
                @if ($type === 'number') min="0" step="any" inputmode="decimal" @endif
                class="{{ $inputClass }} h-10">
        @endif
        @if (!empty($hint))
            <p class="mt-1 text-[10px] leading-relaxed text-neutral-500">{{ $hint }}</p>
        @endif
        @error($name)
            <p class="mt-1 text-[10px] text-[#b54740]">{{ $message }}</p>
        @enderror
    </div>
@endif
