// Handles admin layout interactions (sidebar toggle, dropdowns, notifications)
class AdminLayout {
    constructor() {
        // Get DOM elements
        this.sidebar = document.querySelector('.admin-sidebar');
        this.overlay = document.querySelector('.overlay');
        this.menuToggle = document.querySelector('.menu-toggle');
        this.csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        this.init();
    }

    // Initialize all event listeners
    init() {
        this.bindEvents();
        this.initDropdowns();
        this.initNotifications();
    }

    // Set up sidebar toggle, overlay click, and ESC key
    bindEvents() {
        if (this.menuToggle) {
            this.menuToggle.addEventListener('click', () => this.toggleSidebar());
        }

        if (this.overlay) {
            this.overlay.addEventListener('click', () => this.closeSidebar());
        }

        // Close sidebar when ESC key is pressed
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                this.closeSidebar();
            }
        });
    }

    // Toggles sidebar open/closed (mobile)
    toggleSidebar() {
        this.sidebar?.classList.toggle('open');
        this.overlay?.classList.toggle('active');
    }

    // Closes sidebar
    closeSidebar() {
        this.sidebar?.classList.remove('open');
        this.overlay?.classList.remove('active');
    }

    // Initializes dropdown menus in sidebar (if any)
    initDropdowns() {
        const dropdownToggles = document.querySelectorAll('.nav-item[data-dropdown]');

        dropdownToggles.forEach(toggle => {
            toggle.addEventListener('click', (e) => {
                e.preventDefault();
                const dropdown = toggle.nextElementSibling;
                if (dropdown) {
                    dropdown.classList.toggle('open');
                    toggle.classList.toggle('open');
                }
            });
        });
    }

    // Initializes notification bell and dropdown
    initNotifications() {
        const notifBtn = document.getElementById('notificationBtn');
        const notifDropdown = document.getElementById('notifDropdown');
        const notifList = document.getElementById('notifList');

        if (notifBtn && notifDropdown) {
            // Toggle dropdown on bell click
            notifBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                notifDropdown.classList.toggle('active');
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', (e) => {
                if (!notifDropdown.contains(e.target)) {
                    notifDropdown.classList.remove('active');
                }
            });

            // Load notifications and refresh every 30 seconds
            this.loadNotifications();
            setInterval(() => this.loadNotifications(), 30000);
        }
    }

    // Fetches notifications from server
    async loadNotifications() {
        try {
            const response = await fetch('/admin/notifications/data');
            const data = await response.json();
            this.renderNotifications(data.notifications, data.unread_count);
        } catch (error) {
            console.error('Error loading notifications:', error);
        }
    }

    // Updates notification badge and list
    renderNotifications(notifications, unreadCount) {
        const badge = document.getElementById('notifBadge');
        const list = document.getElementById('notifList');

        if (badge) {
            badge.textContent = unreadCount;
            badge.style.display = unreadCount > 0 ? 'block' : 'none';
        }

        if (list) {
            if (!notifications || notifications.length === 0) {
                list.innerHTML = '<div class="notif-empty">No notifications</div>';
                return;
            }

            // Create HTML for each notification
            list.innerHTML = notifications.map(notif => `
                <div class="notif-item ${notif.is_read ? '' : 'unread'}" onclick="markRead(${notif.id})">
                    <div class="notif-title">${notif.title}</div>
                    <div class="notif-message">${notif.message}</div>
                    <div class="notif-time">${new Date(notif.created_at).toLocaleString()}</div>
                </div>
            `).join('');
        }
    }
}

// Marks a single notification as read
async function markRead(id) {
    try {
        await fetch('/admin/notifications/mark-read', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
            },
            body: JSON.stringify({ id }),
        });
    } catch (error) {
        console.error('Error marking notification as read:', error);
    }
}

// Marks all notifications as read
async function markAllRead() {
    try {
        await fetch('/admin/notifications/read-all', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
            },
        });
        window.location.reload(); // Refresh page
    } catch (error) {
        console.error('Error marking all as read:', error);
    }
}

// Initialize when page loads
document.addEventListener('DOMContentLoaded', () => {
    new AdminLayout();
});