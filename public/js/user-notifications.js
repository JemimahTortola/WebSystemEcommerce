// User Notification System - EXACTLY the same as Admin
let allNotifications = [];

// Initialize notification system (same as admin)
function initNotifications() {
    const notifBtn = document.getElementById('notificationBtn');
    const notifDropdown = document.getElementById('notifDropdown');

    if (notifBtn && notifDropdown) {
        notifBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            notifDropdown.classList.toggle('active');
            if (notifDropdown.classList.contains('active')) {
                loadNotifications();
            }
        });

        document.addEventListener('click', (e) => {
            if (!notifDropdown.contains(e.target)) {
                notifDropdown.classList.remove('active');
            }
        });

        loadNotifications();
        setInterval(() => loadNotifications(), 30000);
    }
}

// Load notifications (same as admin)
async function loadNotifications() {
    try {
        const response = await fetch('/notifications/data', {
            headers: { 'Accept': 'application/json' }
        });
        const data = await response.json();
        // Handle both array and object response
        allNotifications = Array.isArray(data) ? data : (data.data || []);
        renderNotifications();
        updateBadge();
    } catch (error) {
        console.error('Error loading notifications:', error);
    }
}

// Render notifications in dropdown (same as admin)
function renderNotifications() {
    const list = document.getElementById('notifList');
    if (!list) return;

    if (!allNotifications.length) {
        list.innerHTML = '<div class="notif-empty">No notifications</div>';
        return;
    }

    list.innerHTML = allNotifications.map(notif => `
        <div class="notif-item ${notif.is_read ? '' : 'unread'}" onclick="markRead(${notif.id})">
            <div class="notif-title">${notif.title}</div>
            <div class="notif-message">${notif.message}</div>
            <div class="notif-time">${new Date(notif.created_at).toLocaleString()}</div>
        </div>
    `).join('');
}

// Mark single notification as read (same as admin)
async function markRead(id) {
    try {
        await fetch('/notifications/mark-read', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ id })
        });
        loadNotifications();
    } catch (error) {
        console.error('Error marking notification as read:', error);
    }
}

// Mark all as read (same as admin)
async function markAllRead() {
    try {
        await fetch('/notifications/read-all', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        loadNotifications();
    } catch (error) {
        console.error('Error marking all as read:', error);
    }
}

// Update badge count (same as admin)
function updateBadge() {
    const unreadCount = allNotifications.filter(n => !n.is_read).length;
    const badge = document.getElementById('notifBadge');
    if (badge) {
        badge.textContent = unreadCount;
        badge.style.display = unreadCount > 0 ? 'block' : 'none';
    }
}

// Load notifications on page load and check every 30 seconds (same as admin)
document.addEventListener('DOMContentLoaded', () => {
    // Only run if notification elements exist (user is logged in)
    if (document.getElementById('notifDropdown') && document.getElementById('notifBadge')) {
        initNotifications();
    }
});
