class UsersHandler {
    constructor() {
        this.csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        this.currentUserId = null;
        this.init();
    }

    init() {
        this.loadUsers();
        document.getElementById('searchInput')?.addEventListener('input', () => this.loadUsers());
    }

    async loadUsers(page = 1) {
        const search = document.getElementById('searchInput')?.value || '';

        try {
            const params = new URLSearchParams({ search, page });
            const response = await fetch(`/admin/users/data?${params}`);
            const data = await response.json();

            document.getElementById('userCount').textContent = data.total || 0;
            this.renderTable(data.data || data);
        } catch (error) {
            console.error('Error loading users:', error);
        }
    }

    renderTable(users) {
        const tbody = document.getElementById('usersTableBody');
        if (!users.length) {
            tbody.innerHTML = '<tr><td colspan="7" class="empty-state">No users found</td></tr>';
            return;
        }

        tbody.innerHTML = users.map(u => {
            const lastActivity = u.last_activity_at ? new Date(u.last_activity_at) : null;
            const bannedUntil = u.banned_until ? new Date(u.banned_until) : null;
            const now = new Date();
            
            const isOnline = u.is_online && lastActivity && (now - lastActivity) < (5 * 60000);
            const isBanned = bannedUntil && bannedUntil > now;

            let statusBadge = '';
            if (isBanned) {
                statusBadge = '<span class="status-badge" style="background: #ef4444; color: white;">Banned</span>';
            } else if (isOnline) {
                statusBadge = '<span class="status-badge" style="background: #10b981; color: white;">Online</span>';
            } else {
                statusBadge = '<span class="status-badge" style="background: #6b7280; color: white;">Offline</span>';
            }

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
                this.loadUsers();
                closeBanModal();
            }
        } catch (error) {
            console.error('Error banning user:', error);
        }
    }

    async unbanUser(userId) {
        try {
            const response = await fetch(`/admin/users/${userId}/unban`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': this.csrfToken,
                },
            });
            if (response.ok) {
                this.loadUsers();
            }
        } catch (error) {
            console.error('Error unbanning user:', error);
        }
    }
}

let usersHandler;

document.addEventListener('DOMContentLoaded', () => {
    usersHandler = new UsersHandler();
});

function showBanModal(userId, userName) {
    usersHandler.currentUserId = userId;
    document.getElementById('banModal').classList.add('active');
}

function closeBanModal() {
    document.getElementById('banModal').classList.remove('active');
    usersHandler.currentUserId = null;
}

function confirmBan() {
    const days = document.getElementById('banDays').value;
    const reason = document.getElementById('banReason').value;
    if (usersHandler.currentUserId) {
        usersHandler.banUser(usersHandler.currentUserId, days, reason);
    }
}

function unbanUser(userId) {
    if (confirm('Are you sure you want to unban this user?')) {
        usersHandler.unbanUser(userId);
    }
}