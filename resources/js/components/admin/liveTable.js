/**
 * File: resources/js/components/admin/liveTable.js
 * A reusable Alpine.js component for a dynamic data table.
 */
import TomSelect from 'tom-select';

export default function liveTable(config) {
  return {
    items: [],
    pagination: null,
    loading: false,
    config: config,
    search: '',
    filters: {},
    sortBy: config.defaultSortBy || 'created_at',
    sortDirection: config.defaultSortDirection || 'desc',

    init() {
      this.items = this.config.initialData.data || [];
      this.pagination = this.config.initialData || null;
      this.pagination.links = this.cleanPaginationLinks(this.pagination.links);
      this.initSelectFilters();

      this.$watch(
        '[search, filters]',
        () => {
          this.fetchData(1);
        },
        { deep: true }
      );
    },

    initSelectFilters() {
      this.config.filters.forEach((filter) => {
        if (filter.type === 'select') {
          const el = document.getElementById(filter.id);
          if (el) {
            this.filters[filter.key] = '';
            new TomSelect(el, {
              onChange: (value) => {
                this.filters[filter.key] = value;
              },
            });
          }
        }
      });
    },

    fetchData(page = 1) {
      this.loading = true;
      const params = new URLSearchParams({
        page: page,
        search: this.search,
        sort_by: this.sortBy,
        sort_direction: this.sortDirection,
        ...this.filters,
      });

      fetch(`${this.config.apiUrl}?${params}`)
        .then((response) => response.json())
        .then((data) => {
          this.items = data.data;
          this.pagination = {
            ...data.meta,
            links: this.cleanPaginationLinks(data.meta.links),
          };
          this.loading = false;
        });
    },

    handleSort(columnKey) {
      if (this.sortBy === columnKey) {
        this.sortDirection = this.sortDirection === 'asc' ? 'desc' : 'asc';
      } else {
        this.sortBy = columnKey;
        this.sortDirection = 'asc';
      }
      this.fetchData(1);
    },

    cleanPaginationLinks(links) {
      if (!Array.isArray(links)) return [];
      return links.map((link) => ({
        ...link,
        label: link.label.replace(/&laquo;|&raquo;/g, '').trim(),
      }));
    },
  };
}
