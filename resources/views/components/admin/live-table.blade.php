@props([
  'title',
  'description',
  'columns',
  'filters',
  'initialData',
  'apiUrl',
  'createUrl',
  'createPermission',
])

{{-- x-data chỉ gọi tên component, không truyền dữ liệu --}}
<div x-data="liveTable">
  {{-- Page Header --}}
  <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
    <div>
      <h1 class="text-2xl font-bold text-gray-900">{{ $title }}</h1>
      <p class="mt-1 text-sm text-gray-500">{{ $description }}</p>
    </div>
    @can($createPermission)
      <div class="mt-4 sm:mt-0">
        <a
          href="{{ $createUrl }}"
          class="inline-flex items-center justify-center px-4 py-2 bg-black text-white text-sm font-semibold rounded-lg hover:bg-gray-800 shadow-sm transition-colors"
        >
          <svg
            class="w-5 h-5 mr-2"
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24"
            stroke-width="1.5"
            stroke="currentColor"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              d="M12 6v12m6-6H6"
            />
          </svg>
          Add New
        </a>
      </div>
    @endcan
  </div>

  {{-- Filters and Search Section --}}
  <div class="bg-white p-4 rounded-xl shadow-lg mb-6">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
      @foreach ($filters as $filter)
        @if ($filter['type'] === 'search')
          <div class="md:col-span-2 relative">
            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
              <svg
                class="w-5 h-5 text-gray-400"
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke-width="1.5"
                stroke="currentColor"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"
                />
              </svg>
            </div>
            <input
              x-model.debounce.500ms="search"
              type="text"
              placeholder="{{ $filter['placeholder'] }}"
              class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-black focus:border-black transition-colors"
            />
          </div>
        @elseif ($filter['type'] === 'select')
          <div>
            <div wire:ignore>
              <select
                id="{{ $filter['id'] }}"
                placeholder="{{ $filter['placeholder'] }}"
              >
                <option value="">{{ $filter['placeholder'] }}</option>
                @foreach ($filter['options'] as $option)
                  <option value="{{ $option['value'] }}">{{ $option['text'] }}</option>
                @endforeach
              </select>
            </div>
          </div>
        @endif
      @endforeach
    </div>
  </div>

  {{-- Table --}}
  <div
    class="bg-white shadow-md rounded-lg overflow-x-auto relative"
    :class="{ 'min-h-[200px]': loading }"
  >
    <div
      x-show="loading"
      x-transition
      class="absolute inset-0 bg-white bg-opacity-75 flex items-center justify-center z-10"
    >
      <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-black"></div>
    </div>
    <table
      class="w-full text-sm text-left text-gray-500 divide-y divide-gray-200"
      x-show="!loading"
    >
      <thead class="text-xs text-gray-700 uppercase bg-gray-50">
        <tr>
          @foreach ($columns as $column)
            <th
              scope="col"
              class="px-6 py-3 border-r border-gray-200 {{ $column['sortable'] ? 'cursor-pointer' : '' }}"
              @if($column['sortable']) @click="handleSort('{{ $column['key'] }}')" @endif
            >
              <div class="flex items-center justify-between">
                <span>{{ $column['label'] }}</span>
                @if ($column['sortable'])
                  <span class="ml-2 flex flex-col">
                    <svg
                      class="w-2.5 h-2.5 -mb-0.5"
                      :class="{ 'text-gray-900': sortBy === '{{ $column['key'] }}' && sortDirection === 'asc', 'text-gray-400': sortBy !== '{{ $column['key'] }}' || sortDirection !== 'asc' }"
                      xmlns="http://www.w3.org/2000/svg"
                      fill="currentColor"
                      viewBox="0 0 24 24"
                    >
                      <path d="M12 4l-8 8h16l-8-8z"></path>
                    </svg>
                    <svg
                      class="w-2.5 h-2.5"
                      :class="{ 'text-gray-900': sortBy === '{{ $column['key'] }}' && sortDirection === 'desc', 'text-gray-400': sortBy !== '{{ $column['key'] }}' || sortDirection !== 'desc' }"
                      xmlns="http://www.w3.org/2000/svg"
                      fill="currentColor"
                      viewBox="0 0 24 24"
                    >
                      <path d="M12 20l8-8H4l8 8z"></path>
                    </svg>
                  </span>
                @endif
              </div>
            </th>
          @endforeach

          <th
            scope="col"
            class="px-6 py-3"
          >
            <span class="sr-only">Actions</span>
          </th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-200">
        <template x-if="!loading && items.length === 0">
          <tr>
            <td
              colspan="{{ count($columns) + 1 }}"
              class="px-6 py-24 text-center text-gray-500"
            >
              No items found.
            </td>
          </tr>
        </template>
        <template
          x-for="item in items"
          :key="item.id"
        >
          {{ $slot }}
        </template>
      </tbody>
    </table>
  </div>

  {{-- Pagination --}}
  <div class="mt-4"><x-admin.pagination /></div>
</div>

{{--
  THE FIX: Initialize the component safely using the alpine:init event.
  This is the robust pattern that avoids all syntax errors.
--}}
<script>
  document.addEventListener('alpine:init', () => {
    Alpine.data('liveTable', () =>
      liveTable({
        initialData: @json($initialData),
        apiUrl: @json($apiUrl),
        filters: @json($filters),
        defaultSortBy: 'created_at',
        defaultSortDirection: 'desc',
      })
    );
  });
</script>
