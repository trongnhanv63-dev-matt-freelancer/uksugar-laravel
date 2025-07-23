@props([
  'cancelUrl',
  'submitText' => 'Save',
  'disabled' => false,
])

<div class="mt-8 pt-6 border-t border-gray-200 flex items-center justify-end gap-x-6">
  <a
    href="{{ $cancelUrl }}"
    class="text-sm font-semibold leading-6 text-gray-900"
  >
    Cancel
  </a>
  <button
    type="submit"
    {{ $disabled ? 'disabled' : '' }}
    class="rounded-md bg-black px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-gray-800 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-black disabled:opacity-50 disabled:cursor-not-allowed"
  >
    {{ $submitText }}
  </button>
</div>
