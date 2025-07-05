// This function defines our Alpine.js component.
// It takes initial data passed from the Blade view.
const userManagement = (initialData) => ({
    // --- DATA PROPERTIES ---
    users: initialData.users,
    pagination: initialData.pagination,
    roles: initialData.roles,
    search: '',
    selectedRole: '',
    selectedStatus: '',
    sortBy: 'created_at',
    sortDirection: 'desc',
    loading: false,

    // --- METHODS ---

    // This method is called when the component is initialized.
    init() {
        // Read initial filters from the URL query string if they exist
        const urlParams = new URLSearchParams(window.location.search);
        this.search = urlParams.get('search') || '';
        this.selectedRole = urlParams.get('role') || '';
        this.selectedStatus = urlParams.get('status') || '';
    },

    // The core method to fetch data from the API
    async fetchData() {
        this.loading = true;

        // Build the query parameters
        const params = new URLSearchParams({
            search: this.search,
            role: this.selectedRole,
            status: this.selectedStatus,
            sort_by: this.sortBy,
            sort_direction: this.sortDirection,
            page: this.pagination.current_page || 1,
        });

        // Update the browser's URL without reloading the page
        const newUrl = `${window.location.pathname}?${params}`;
        window.history.pushState({}, '', newUrl);

        try {
            // Fetch data from our dedicated API route
            const response = await fetch(`/api/admin/users?${params}`, {
                headers: { Accept: 'application/json' },
            });
            const data = await response.json();

            // Update component data with the new data from the API
            this.users = data.data;
            this.pagination = data;
        } catch (error) {
            console.error('Error fetching users:', error);
            alert('An error occurred while fetching data.');
        } finally {
            this.loading = false;
        }
    },

    // Method to handle column sorting
    handleSort(column) {
        if (this.sortBy === column) {
            this.sortDirection = this.sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            this.sortBy = column;
            this.sortDirection = 'asc';
        }
        this.fetchData();
    },

    // Method to handle pagination clicks
    goToPage(page) {
        if (!page || page === this.pagination.current_page) return;
        this.pagination.current_page = page;
        this.fetchData();
    },
});

// Make the component function available globally for Alpine to use
window.userManagement = userManagement;
