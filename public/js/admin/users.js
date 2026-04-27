class UsersHandler {
    constructor() {
        this.csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        this.init();
    }

    init() {
        this.loadUsers();
        document.getElementById('searchInput')?.addEventListener('input', () => this.loadUsers());
    }

    async loadUsers() {
        const search = document.getElementById('searchInput')?.value || '';

        try {
            const params = new URLSearchParams({ search });
            const response = await fetch(`/admin/users/data?${params}`);
            const data = await response.json();
            this.renderTable(data.data);
        } catch (error) {
            console.error('Error loading users:', error);
        }
    }

    renderTable(users) {
        const tbody = document.getElementById('usersTableBody');
        if (!users.length) {
            tbody.innerHTML = '<tr><td colspan="6" class="empty-state">No users found</td></tr>';
            return;
        }

        tbody.innerHTML = users.map(u => `
            <tr>
                <td>${u.name}</td>
                <td>${u.email}</td>
                <td>${u.phone || '-'}</td>
                <td>${u.orders_count || 0}</td>
                <td>${new Date(u.created_at).toLocaleDateString()}</td>
            </tr>
        `).join('');
    }
}

let usersHandler;

document.addEventListener('DOMContentLoaded', () => {
    usersHandler = new UsersHandler();
});

function viewUser(id) {
    console.log('View user:', id);
}