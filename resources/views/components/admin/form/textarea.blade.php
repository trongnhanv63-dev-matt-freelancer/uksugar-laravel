@props([
  'name',
  'label',
  'value' => '',
  'required' => false,
  'disabled' => false,
  'rows' => 4,
])

<div>
  <label
    for="{{ $name }}"
    class="block text-sm font-medium leading-6 text-gray-900"
  >
    {{ $label }}
  </label>
  <div class="mt-2">
    <textarea
      name="{{ $name }}"
      id="{{ $name }}"
      rows="{{ $rows }}"
      {{ $required ? 'required' : '' }}
      {{ $disabled ? 'disabled' : '' }}
      {{ $attributes->merge(['class' => 'block w-full rounded-md border-0 px-3 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:outline-none focus:ring-1 focus:ring-inset focus:ring-purple-500 sm:text-sm sm:leading-6 disabled:cursor-not-allowed disabled:bg-gray-50 disabled:text-gray-500']) }}
    >
{{ old($name, $value) }}</textarea
    >
  </div>
</div>
