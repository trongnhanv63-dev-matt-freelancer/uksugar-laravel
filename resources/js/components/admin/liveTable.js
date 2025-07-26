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
    stateKey: config.stateKey || 'liveTableState',

    init() {
      const savedState = sessionStorage.getItem(this.stateKey);

      if (savedState) {
        const state = JSON.parse(savedState);
        this.search = state.search || '';
        this.filters = state.filters || {};
        this.sortBy = state.sortBy || config.defaultSortBy;
        this.sortDirection = state.sortDirection || config.defaultSortDirection;
        sessionStorage.removeItem(this.stateKey); // Xóa sau khi khôi phục
        this.fetchData(state.page || 1); // Tải lại dữ liệu với trang đã lưu
      } else {
        this.items = this.config.initialData.data || [];
        this.pagination = this.config.initialData || null;
      }

      if (this.pagination && this.pagination.links) {
        this.pagination.links = this.cleanPaginationLinks(this.pagination.links);
      }

      this.initSelectFilters();

      this.$watch(
        '[search, filters]',
        () => {
          this.$nextTick(() => this.fetchData(1));
        },
        { deep: true }
      );
    },

    saveStateAndRedirect(url) {
      const state = {
        search: this.search,
        filters: this.filters,
        sortBy: this.sortBy,
        sortDirection: this.sortDirection,
        page: this.pagination ? this.pagination.current_page : 1,
      };
      sessionStorage.setItem(this.stateKey, JSON.stringify(state));
      window.location.href = url;
    },

    initSelectFilters() {
      this.config.filters.forEach((filter) => {
        if (filter.type === 'select') {
          const el = document.getElementById(filter.id);
          if (el) {
            if (typeof this.filters[filter.key] === 'undefined') {
              this.filters[filter.key] = '';
            }
            new TomSelect(el, {
              items: [this.filters[filter.key]],
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
          this.pagination = data.meta
            ? {
                ...data.meta,
                links: this.cleanPaginationLinks(data.meta.links),
              }
            : null;
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
