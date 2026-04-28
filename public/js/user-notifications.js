// User Notification System - Separate from Admin
let userNotifications = [];

// Toggle notification dropdown for users
function toggleUserNotificationDropdown() {
    const dropdown = document.getElementById('userNotificationDropdown');
    if (dropdown) {
        dropdown.classList.toggle('active');
        if (dropdown.classList.contains('active')) {
            loadUserNotifications();
        }
    }
}

// Close dropdown when clicking outside
document.addEventListener('click', function(e) {
    if (!e.target.closest('.user-notification-wrapper')) {
        document.getElementById('userNotificationDropdown')?.classList.remove('active');
    }
});

// Load user notifications
async function loadUserNotifications() {
    try {
        const response = await fetch('/notifications/data', {
            headers: { 'Accept': 'application/json' }
        });
        userNotifications = await response.json();
        renderUserNotifications();
        updateUserNotificationBadge();
    } catch (error) {
        console.error('Error loading user notifications:', error);
    }
}

// Render notifications in dropdown
function renderUserNotifications() {
    const list = document.getElementById('userNotificationList');
    if (!list) return;
    
    if (!userNotifications.length) {
        list.innerHTML = '<div class="notif-empty">No notifications</div>';
        return;
    }
    
    list.innerHTML = userNotifications.map(notif => `
        <div class="notif-item ${notif.is_read ? '' : 'unread'}" onclick="markUserNotificationRead(${notif.id})">
            <div class="notif-icon">${getNotificationIcon(notif.type)}</div>
            <div class="notif-content">
                <div class="notif-title">${notif.title}</div>
                <div class="notif-message">${notif.message}</div>
                <div class="notif-time">${timeAgo(notif.created_at)}</div>
            </div>
        </div>
    `).join('');
}

// Get icon based on notification type
function getNotificationIcon(type) {
    const icons = {
        'payment_verified': '✅',
        'payment_rejected': '❌',
        'order_status': '📦',
        'order_cancelled': '⚠️',
        'welcome': '🎉',
    };
    return icons[type] || '🔔';
}

// Time ago helper
function timeAgo(datetime) {
    const now = new Date();
    const date = new Date(datetime);
    const seconds = Math.floor((now - date) / 1000);
    
    if (seconds < 60) return 'Just now';
    if (seconds < 3600) return Math.floor(seconds / 60) + 'm ago';
    if (seconds < 86400) return Math.floor(seconds / 3600) + 'h ago';
    return Math.floor(seconds / 86400) + 'd ago';
}

// Mark single notification as read
async function markUserNotificationRead(id) {
    try {
        await fetch('/notifications/mark-read', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ id })
        });
        loadUserNotifications();
    } catch (error) {
        console.error('Error marking notification as read:', error);
    }
}

// Mark all as read
async function markAllUserNotificationsRead() {
    try {
        await fetch('/notifications/read-all', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        loadUserNotifications();
    } catch (error) {
        console.error('Error marking all as read:', error);
    }
}

// Update badge count
function updateUserNotificationBadge() {
    const unreadCount = userNotifications.filter(n => !n.is_read).length;
    const badge = document.getElementById('userNotificationBadge');
    if (badge) {
        badge.textContent = unreadCount;
        badge.style.display = unreadCount > 0 ? 'block' : 'none';
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', () => {
    // Load notifications on page load
    loadUserNotifications();
    
    // Check for new notifications every 30 seconds
    setInterval(loadUserNotifications, 30000);
});
