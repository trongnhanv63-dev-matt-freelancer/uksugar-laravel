export default function userManagement(initialData) {
  return {
    users: [],
    pagination: null,
    roles: [],
    fetchUrl: '',
    loading: false,
    search: '',
    selectedRole: '',
    selectedStatus: '',
    sortBy: 'created_at',
    sortDirection: 'desc',

    // --- Helper Functions ---
    cleanPaginationLinks(links) {
      if (!Array.isArray(links)) return [];
      return links.map((link) => ({
        ...link,
        label: link.label.replace(/&laquo;|&raquo;/g, '').trim(),
      }));
    },

    // --- Main Logic ---
    init() {
      // Initialize state from server
      if (!initialData || typeof initialData !== 'object') {
        console.error('Initial data is missing or malformed!');
        return;
      }
      this.users = initialData.users || [];
      this.pagination = initialData.pagination || null;
      this.fetchUrl = initialData.fetchUrl || '';

      if (this.pagination && this.pagination.links) {
        this.pagination.links = this.cleanPaginationLinks(this.pagination.links);
      }

      this.initRoleFilter();
      this.initStatusFilter();

      // Use a single watcher for all filter properties.
      // This is more efficient than multiple watchers.
      this.$watch('[search, selectedRole, selectedStatus]', () => {
        // When a filter changes, reset to page 1 and fetch data
        this.fetchData(1);
      });
    },

    initRoleFilter() {
      new TomSelect('#role-filter-select', {
        onChange: (value) => {
          this.selectedRole = value;
        },
      });
    },

    initStatusFilter() {
      new TomSelect('#status-filter-select', {
        onChange: (value) => {
          this.selectedStatus = value;
        },
      });
    },

    fetchData(page = 1) {
      this.loading = true;

      const params = new URLSearchParams({
        page: page,
        search: this.search,
        role: this.selectedRole,
        status: this.selectedStatus,
        sort_by: this.sortBy,
        sort_direction: this.sortDirection,
      });

      fetch(`${this.fetchUrl}?${params}`)
        .then((response) => {
          if (!response.ok) throw new Error('Network response was not ok');
          return response.json();
        })
        .then((data) => {
          this.users = data.data;

          // This is the crucial fix: we flatten the structure from the API
          // to match the structure from the initial server render.
          this.pagination = {
            ...data.meta, // Spread the meta object to get top-level keys
            links: this.cleanPaginationLinks(data.meta.links), // Use the links array from meta
          };

          this.loading = false;
        })
        .catch((error) => {
          this.loading = false;
          console.error('Fetch error:', error);
          alert('An error occurred while fetching data.');
        });
    },

    handleSort(column) {
      if (this.sortBy === column) {
        this.sortDirection = this.sortDirection === 'asc' ? 'desc' : 'asc';
      } else {
        this.sortBy = column;
        this.sortDirection = 'asc';
      }
      // Sorting also triggers a fetch
      this.fetchData(1);
    },
  };
}
