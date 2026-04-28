// User Notification System - EXACTLY the same as Admin
let allNotifications = [];

// Toggle notification dropdown (same as admin)
function toggleNotificationDropdown() {
    const dropdown = document.getElementById('notifDropdown');
    if (dropdown) {
        dropdown.classList.toggle('active');
        if (dropdown.classList.contains('active')) {
            loadNotifications();
        }
    }
}

// Close dropdown when clicking outside
document.addEventListener('click', function(e) {
    if (!e.target.closest('.notification-wrapper')) {
        document.getElementById('notifDropdown')?.classList.remove('active');
    }
});

// Load notifications (same as admin)
async function loadNotifications() {
    try {
        const response = await fetch('/notifications/data', {
            headers: { 'Accept': 'application/json' }
        });
        allNotifications = await response.json();
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
            <div class="notif-icon">${getIcon(notif.type)}</div>
            <div class="notif-content">
                <div class="notif-title">${notif.title}</div>
                <div class="notif-message">${notif.message}</div>
                <div class="notif-time">${timeAgo(notif.created_at)}</div>
            </div>
        </div>
    `).join('');
}

// Get icon based on notification type (same as admin)
function getIcon(type) {
    const icons = {
        'payment_verified': '✅',
        'order_status': '🚚',
        'order_cancelled': '⛔️',
        'welcome': '🎉',
    };
    return icons[type] || '🔔';
}

// Time ago helper (same as admin)
function timeAgo(datetime) {
    const now = new Date();
    const date = new Date(datetime);
    const seconds = Math.floor((now - date) / 1000);
    
    if (seconds < 60) return 'Just now';
    if (seconds < 3600) return Math.floor(seconds / 60) + 'm ago';
    if (seconds < 86400) return Math.floor(seconds / 3600) + 'h ago';
    return Math.floor(seconds / 86400) + 'd ago';
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
    loadNotifications();
    setInterval(loadNotifications, 30000);
});
