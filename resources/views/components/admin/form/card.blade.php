@props([
  'action',
  'method' => 'POST',
])

<form
  action="{{ $action }}"
  method="POST"
>
  @csrf
  @if (strtoupper($method) !== 'POST')
    @method($method)
  @endif

  <div class="bg-white p-6 rounded-xl shadow-lg">
    {{ $slot }}
  </div>
</form>
