// Notification badge updater - runs on all user pages
function updateNotificationBadge() {
    const badge = document.getElementById('notifCount');
    if (!badge) return;

    fetch('/notifications/data', {
        headers: { 'Accept': 'application/json' }
    })
    .then(response => response.json())
    .then(notifications => {
        const unreadCount = notifications.filter(n => !n.is_read).length;
        badge.textContent = unreadCount;
        badge.style.display = unreadCount > 0 ? 'block' : 'none';
    })
    .catch(error => console.error('Error checking notifications:', error));
}

// Update badge on page load
document.addEventListener('DOMContentLoaded', updateNotificationBadge);

// Check for new notifications every 30 seconds
setInterval(updateNotificationBadge, 30000);
