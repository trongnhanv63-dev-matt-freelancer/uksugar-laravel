{{--
  MODIFIED: Wrapped the entire <nav> element in a <template> tag
  and moved the x-if directive to it. This resolves the Alpine warning.
--}}
<template x-if="pagination && pagination.last_page > 1">
  <nav
    role="navigation"
    aria-label="Pagination Navigation"
    class="flex items-center justify-between"
  >
    <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
      {{-- "Showing X to Y of Z results" text --}}
      <div>
        <p class="text-sm text-gray-700 leading-5">
          Showing
          <span
            class="font-medium"
            x-text="pagination.from"
          ></span>
          to
          <span
            class="font-medium"
            x-text="pagination.to"
          ></span>
          of
          <span
            class="font-medium"
            x-text="pagination.total"
          ></span>
          results
        </p>
      </div>

      {{-- Page links --}}
      <div>
        <span class="relative z-0 inline-flex shadow-sm rounded-md">
          <button
            @click="fetchData(pagination.current_page - 1)"
            :disabled="pagination.current_page <= 1"
            class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
          >
            <svg
              class="h-5 w-5"
              xmlns="http://www.w3.org/2000/svg"
              viewBox="0 0 20 20"
              fill="currentColor"
            >
              <path
                fill-rule="evenodd"
                d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                clip-rule="evenodd"
              />
            </svg>
          </button>

          <template
            x-for="(link, index) in pagination.links"
            :key="index"
          >
            <div>
              <template x-if="! isNaN(link.label)">
                <button
                  @click="fetchData(link.label)"
                  :class="{ 'z-10 bg-black text-white border-black': link.active, 'bg-white text-gray-700 hover:bg-gray-50 border-gray-300': !link.active }"
                  class="relative -ml-px inline-flex items-center px-4 py-2 border text-sm font-medium"
                  x-text="link.label"
                ></button>
              </template>
              <template x-if="isNaN(link.label)">
                <span
                  class="relative -ml-px inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700"
                >
                  ...
                </span>
              </template>
            </div>
          </template>

          <button
            @click="fetchData(pagination.current_page + 1)"
            :disabled="pagination.current_page >= pagination.last_page"
            class="relative -ml-px inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
          >
            <svg
              class="h-5 w-5"
              xmlns="http://www.w3.org/2000/svg"
              viewBox="0 0 20 20"
              fill="currentColor"
            >
              <path
                fill-rule="evenodd"
                d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                clip-rule="evenodd"
              />
            </svg>
          </button>
        </span>
      </div>
    </div>
  </nav>
</template>
