@extends('layouts.app')

@section('title', 'Notifications - Flourista')

@section('content')
<div class="page-header">
    <h2>Notifications</h2>
    <button class="btn btn-secondary" onclick="markAllRead()">Mark All as Read</button>
</div>

<div class="notifications-list" id="notificationsList">
    <!-- Notifications will be loaded here -->
</div>
@endsection

@section('scripts')
<script>
let allNotifications = [];

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

function renderNotifications() {
    const list = document.getElementById('notificationsList');
    
    if (!allNotifications.length) {
        list.innerHTML = '<div class="empty-state">No notifications yet</div>';
        return;
    }
    
    list.innerHTML = allNotifications.map(notif => `
        <div class="notification-item ${notif.is_read ? '' : 'unread'}" onclick="markRead(${notif.id})">
            <div class="notification-icon">
                ${getIcon(notif.type)}
            </div>
            <div class="notification-content">
                <h4>${notif.title}</h4>
                <p>${notif.message}</p>
                <span class="notification-time">${new Date(notif.created_at).toLocaleString()}</span>
            </div>
            ${notif.is_read ? '' : '<div class="unread-dot"></div>'}
        </div>
    `).join('');
}

function getIcon(type) {
    const icons = {
        'payment_verified': '✅',
        'order_status': '📦',
        'order_cancelled': '❌',
        'welcome': '🎉',
    };
    return icons[type] || '🔔';
}

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

function updateBadge() {
    const unreadCount = allNotifications.filter(n => !n.is_read).length;
    const badge = document.getElementById('notifCount');
    if (badge) {
        badge.textContent = unreadCount;
        badge.style.display = unreadCount > 0 ? 'block' : 'none';
    }
}

// Check for new notifications every 30 seconds
setInterval(loadNotifications, 30000);

// Load notifications on page load
document.addEventListener('DOMContentLoaded', loadNotifications);
</script>
@endsection
