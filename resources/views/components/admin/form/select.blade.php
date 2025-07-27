@props([
  'name',
  'id' => $name,
  'label',
  'disabled' => false,
])

<div>
  <label
    for="{{ $id }}"
    class="block text-sm font-medium leading-6 text-gray-900"
  >
    {{ $label }}
  </label>
  <div class="mt-2">
    <select
      name="{{ $name }}"
      id="{{ $id }}"
      {{ $disabled ? 'disabled' : '' }}
    >
      {{ $slot }}
    </select>
  </div>
</div>
