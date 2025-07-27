@props([
  'title',
  'description' => null,
])

<div class="border-b border-gray-200 pb-5 mb-6">
  <h2 class="text-xl font-semibold leading-7 text-gray-900">{{ $title }}</h2>
  @if ($description)
    <p class="mt-1 text-sm leading-6 text-gray-500">{{ $description }}</p>
  @endif
</div>
