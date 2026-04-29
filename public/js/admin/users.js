// Handles all user-related functionality (ban, unban, list users)
class UsersHandler {
    constructor() {
        // Get CSRF token for security
        this.csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        // Track which user we're currently banning
        this.currentUserId = null;
        this.init();
    }

    // Initialize - load users and set up search
    init() {
        this.loadUsers();
        // Reload users when search input changes
        document.getElementById('searchInput')?.addEventListener('input', () => this.loadUsers());
    }

    // Loads users from the server with search and pagination
    async loadUsers(page = 1) {
        const search = document.getElementById('searchInput')?.value || '';

        try {
            const params = new URLSearchParams({ search, page });
            const response = await fetch(`/admin/users/data?${params}`);
            const data = await response.json();

            // Update user count and display the table
            document.getElementById('userCount').textContent = data.total || 0;
            this.renderTable(data.data || data);
        } catch (error) {
            console.error('Error loading users:', error);
        }
    }

    // Displays users in the HTML table with online/banned status
    renderTable(users) {
        const tbody = document.getElementById('usersTableBody');
        if (!users.length) {
            tbody.innerHTML = '<tr><td colspan="7" class="empty-state">No users found</td></tr>';
            return;
        }

        tbody.innerHTML = users.map(u => {
            // Calculate if user is online (active in last 5 minutes)
            const lastActivity = u.last_activity_at ? new Date(u.last_activity_at) : null;
            const bannedUntil = u.banned_until ? new Date(u.banned_until) : null;
            const now = new Date();
            
            const isOnline = u.is_online && lastActivity && (now - lastActivity) < (5 * 60000);
            const isBanned = bannedUntil && bannedUntil > now;

            // Choose the right status badge color
            let statusBadge = '';
            if (isBanned) {
                statusBadge = '<span class="status-badge" style="background: #ef4444; color: white;">Banned</span>';
            } else if (isOnline) {
                statusBadge = '<span class="status-badge" style="background: #10b981; color: white;">Online</span>';
            } else {
                statusBadge = '<span class="status-badge" style="background: #6b7280; color: white;">Offline</span>';
            }

            // Create the table row
            return `
                <tr>
                    <td>${u.name}</td>
                    <td>${u.email}</td>
                    <td>${u.phone || '-'}</td>
                    <td>${u.orders_count || 0}</td>
                    <td>${statusBadge}</td>
                    <td>${new Date(u.created_at).toLocaleDateString()}</td>
                    <td>
                        ${isBanned ? 
                            `<button class="btn btn-secondary btn-sm" onclick="unbanUser(${u.id})">Unban</button>` :
                            `<button class="btn btn-danger btn-sm" onclick="showBanModal(${u.id}, '${u.name}')">Ban</button>`
                        }
                    </td>
                </tr>
            `;
        }).join('');
    }

    // Bans a user for a specified number of days
    async banUser(userId, days, reason) {
        try {
            const response = await fetch(`/admin/users/${userId}/ban`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken,
                },
                body: JSON.stringify({ days, reason }),
            });
            if (response.ok) {
                this.loadUsers(); // Reload the user list
                closeBanModal(); // Close the ban modal
            }
        } catch (error) {
            console.error('Error banning user:', error);
        }
    }

    // Unbans a user (removes their ban)
    async unbanUser(userId) {
        try {
            const response = await fetch(`/admin/users/${userId}/unban`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': this.csrfToken,
                },
            });
            if (response.ok) {
                this.loadUsers(); // Reload the user list
            }
        } catch (error) {
            console.error('Error unbanning user:', error);
        }
    }
}

// Create a global instance of UsersHandler
let usersHandler;

// Initialize when page loads
document.addEventListener('DOMContentLoaded', () => {
    usersHandler = new UsersHandler();
});

// Global functions that HTML buttons call
function showBanModal(userId, userName) {
    usersHandler.currentUserId = userId;
    document.getElementById('banModal').classList.add('active');
}

function closeBanModal() {
    document.getElementById('banModal').classList.remove('active');
    usersHandler.currentUserId = null;
}

// Confirms and applies the ban
function confirmBan() {
    const days = document.getElementById('banDays').value;
    const reason = document.getElementById('banReason').value;
    if (usersHandler.currentUserId) {
        usersHandler.banUser(usersHandler.currentUserId, days, reason);
    }
}

// Unbans a user after confirmation
function unbanUser(userId) {
    if (confirm('Are you sure you want to unban this user?')) {
        usersHandler.unbanUser(userId);
    }
}