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
    fetchController: null,

    init() {
      const urlParams = new URLSearchParams(window.location.search);
      const shouldRestore = urlParams.get('restore_state') === 'true';
      const savedState = sessionStorage.getItem(this.stateKey);

      if (shouldRestore && savedState) {
        const state = JSON.parse(savedState);
        this.search = state.search || '';
        this.filters = state.filters || {};
        this.sortBy = state.sortBy || config.defaultSortBy;
        this.sortDirection = state.sortDirection || config.defaultSortDirection;
        this.fetchData(state.page || 1);
        history.replaceState(null, '', window.location.pathname);
      } else {
        sessionStorage.removeItem(this.stateKey);
        this.items = this.config.initialData.data || [];
        this.pagination = this.config.initialData || null;
        if (this.pagination) {
          this.saveState();
        }
      }

      if (this.pagination && this.pagination.links) {
        this.pagination.links = this.cleanPaginationLinks(this.pagination.links);
      }

      this.initSelectFilters();

      this.$watch(
        '[search, filters, sortBy, sortDirection]',
        () => {
          this.$nextTick(() => this.fetchData(1));
        },
        { deep: true }
      );
    },

    saveState() {
      const state = {
        search: this.search,
        filters: this.filters,
        sortBy: this.sortBy,
        sortDirection: this.sortDirection,
        page: this.pagination ? this.pagination.current_page : 1,
      };
      sessionStorage.setItem(this.stateKey, JSON.stringify(state));
    },

    initSelectFilters() {
      this.config.filters.forEach((filter) => {
        if (filter.type === 'select') {
          const el = this.$root.querySelector(`#${filter.id}`);
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
      if (this.fetchController) {
        this.fetchController.abort();
      }
      this.fetchController = new AbortController();
      const signal = this.fetchController.signal;

      this.loading = true;
      const params = new URLSearchParams({
        page: page,
        search: this.search,
        sort_by: this.sortBy,
        sort_direction: this.sortDirection,
        ...this.filters,
      });

      fetch(`${this.config.apiUrl}?${params}`, { signal })
        .then((response) => response.json())
        .then((data) => {
          this.items = data.data;
          this.pagination = data.meta
            ? {
                ...data.meta,
                links: this.cleanPaginationLinks(data.meta.links),
              }
            : null;
          this.saveState();
          this.loading = false;
        })
        .catch((error) => {
          if (error.name === 'AbortError') {
            console.log('Fetch aborted');
            return;
          }
          console.error('There was a problem with the fetch operation:', error);
          alert('Could not load data. Please try again later.');
          this.items = [];
          this.pagination = null;
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
