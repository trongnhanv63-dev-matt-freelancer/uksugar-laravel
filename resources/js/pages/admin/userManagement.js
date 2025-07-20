export default function userManagement(initialData) {
  return {
    // --- State Properties ---
    users: [],
    pagination: null,
    roles: [],
    fetchUrl: '',
    loading: false,

    // --- Filter & Sort Properties ---
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
      this.roles = initialData.roles || [];
      this.fetchUrl = initialData.fetchUrl || '';

      if (this.pagination && this.pagination.links) {
        this.pagination.links = this.cleanPaginationLinks(this.pagination.links);
      }

      // --- THE FIX: Watch for changes in filters ---
      // Use a single watcher for all filter properties.
      // This is more efficient than multiple watchers.
      this.$watch('[search, selectedRole, selectedStatus]', () => {
        // When a filter changes, reset to page 1 and fetch data
        this.fetchData(1);
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
          this.pagination = {
            ...data,
            links: this.cleanPaginationLinks(data.links),
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
