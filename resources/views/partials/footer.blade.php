<footer class="footer">
    <div class="container">
        <div class="footer-content">
            <div class="footer-brand footer-column">
                <h3>Flour<span>ista</span></h3>
                <p>Beautiful handcrafted floral arrangements for every occasion in Butuan City. Sourced fresh, delivered with love.</p>
            </div>

            <div class="footer-column">
                <h4>Quick Links</h4>
                <ul>
                    <li><a href="{{ route('shop') }}">Shop</a></li>
                    <li><a href="{{ route('about') }}">About Us</a></li>
                    <li><a href="{{ route('contact') }}">Contact</a></li>
                    <li><a href="{{ route('track-order') }}">Track Order</a></li>
                </ul>
            </div>

            <div class="footer-column">
                <h4>Customer Service</h4>
                <ul>
                    <li><a href="{{ route('delivery-areas') }}">Delivery Areas</a></li>
                    <li><a href="{{ route('privacy') }}">Privacy Policy</a></li>
                    <li><a href="{{ route('terms') }}">Terms of Service</a></li>
                </ul>
            </div>

            <div class="footer-column footer-contact">
                <h4>Contact Us</h4>
                <p>📍 Butuan City, Philippines</p>
                <p>📞 +63 XXX-XXX-XXXX</p>
                <p>✉️ hello@flourista.com</p>
            </div>
        </div>

        <div class="footer-bottom">
            <p>&copy; {{ date('Y') }} Flourista. All rights reserved.</p>
            <div class="social-links">
                <a href="#" aria-label="Facebook">f</a>
                <a href="#" aria-label="Instagram">📷</a>
                <a href="#" aria-label="Twitter">𝕏</a>
            </div>
        </div>
    </div>
</footer>