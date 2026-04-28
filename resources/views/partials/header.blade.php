<header class="header">
    <div class="header-inner">
        <a href="{{ route('home') }}" class="logo">
            Flour<span>ista</span>
        </a>
        
        <nav class="header-nav">
            <a href="{{ route('shop') }}">Shop</a>
            <a href="{{ route('about') }}">About</a>
            <a href="{{ route('contact') }}">Contact</a>
            <a href="{{ route('delivery-areas') }}">Delivery Areas</a>
        </nav>
        
        <div class="header-right">
            <a href="{{ route('cart') }}" class="header-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="9" cy="21" r="1"></circle>
                    <circle cx="20" cy="21" r="1"></circle>
                    <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                </svg>
                <span class="cart-badge" id="cartCount">0</span>
            </a>
            
            @auth
            <div class="notification-wrapper">
                <button class="header-icon" id="notificationBtn" title="Notifications">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0 1 18 14.158V11a6.002 6.002 0 0 0-4-5.659V5a2 2 0 1 0-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 1 1-6 0v-1m6 0H9" />
                    </svg>
                    <span class="notification-badge" id="notifBadge" style="display: none;">0</span>
                </button>
                <div class="notification-dropdown" id="notifDropdown">
                    <div class="notif-header">
                        <h4>Notifications</h4>
                        <button onclick="markAllRead()">Mark all read</button>
                    </div>
                    <div class="notif-list" id="notifList">
                        <div class="notif-empty">No notifications</div>
                    </div>
                </div>
            </div>
            
            <div class="user-dropdown">
                <button class="user-toggle">
                    <span class="user-avatar">{{ substr(Auth::user()->name, 0, 1) }}</span>
                    <span class="user-name">{{ Auth::user()->name }}</span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="6 9 12 15 18 9"></polyline>
                    </svg>
                </button>
                <div class="dropdown-menu">
                    <a href="{{ route('profile') }}">My Profile</a>
                    <a href="{{ route('orders') }}">My Orders</a>
                    <a href="{{ route('wishlist') }}">My Wishlist</a>
                    <a href="{{ route('profile.addresses') }}">My Address</a>
                    <hr>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item">Logout</button>
                    </form>
                </div>
            </div>
            @else
            <a href="{{ route('login') }}" class="btn-login">Login</a>
            @endauth
        </div>
    </div>
</header>