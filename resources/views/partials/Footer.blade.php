<style>
/* Mobile: Quick Links + Categories on the same row */
@media (max-width: 768px) {
    .footer-grid { grid-template-columns: 1fr 1fr !important; }
    .footer-grid .footer-column:nth-child(1),
    .footer-grid .footer-column:nth-child(4) { grid-column: 1 / -1; }
}
</style>
<footer class="main-footer">
    <div class="footer-top">
        <div class="container">
            <div class="footer-grid">
                <!-- Company Info -->
                <div class="footer-column">
                    <div class="footer-logo">
                        <img src="{{ asset('images/logos/logo.png') }}" alt="LibasBD" style="height: 50px; width: auto;">
                        <p class="tagline">Your Trusted Online Shopping Destination</p>
                    </div>
                    <p class="footer-description">
                        We provide high-quality products with best prices, fast delivery,
                        and excellent customer service. Your satisfaction is our priority.
                    </p>
                    <div class="social-links">
                        <a href="https://www.facebook.com/libasbd0" target="_blank" class="social-link" aria-label="Facebook">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="social-link" aria-label="Instagram">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="social-link" aria-label="Twitter">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="social-link" aria-label="YouTube">
                            <i class="fab fa-youtube"></i>
                        </a>
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="footer-column">
                    <h3 class="footer-title">Quick Links</h3>
                    <ul class="footer-links">
                        <li><a href="{{ url('/') }}">Home</a></li>
                        <li><a href="{{ url('/products') }}">All Products</a></li>
                        <li><a href="{{ url('/about') }}">About Us</a></li>
                        <li><a href="{{ url('/contact') }}">Contact Us</a></li>
                        <li><a href="{{ url('/delivery') }}">Delivery Info</a></li>
                        <li><a href="{{ url('/refund') }}">Refund Policy</a></li>
                        <li><a href="{{ url('/privacy') }}">Privacy Policy</a></li>
                        <li><a href="{{ url('/terms') }}">Terms & Conditions</a></li>
                    </ul>
                </div>

                <!-- Categories -->
                <div class="footer-column">
                    <h3 class="footer-title">Categories</h3>
                    <ul class="footer-links">
                        @if(isset($categories) && $categories->count() > 0)
                            @foreach($categories->whereNull('parent_id')->take(6) as $categoryItem)
                                <li><a href="{{ url('/products') }}?category={{ $categoryItem->slug }}">{{ $categoryItem->name }}</a></li>
                            @endforeach
                        @else
                            <li><a href="{{ url('/products') }}">All Products</a></li>
                        @endif
                    </ul>
                </div>

                <!-- Contact & Newsletter -->
                <div class="footer-column">
                    <h3 class="footer-title">Contact Info</h3>
                    <ul class="contact-info">
                        <li>
                            <i class="fas fa-map-marker-alt"></i>
                            <span>House-3, Road-1, Block-A,<br>Bochila City Developers Ltd.,<br>Bosila, Mahammadpur, Dhaka-1207</span>
                        </li>
                        <li>
                            <i class="fas fa-phone"></i>
                            <span>01333257604</span>
                        </li>
                        <li>
                            <i class="fas fa-envelope"></i>
                            <span>support@libasbd.com</span>
                        </li>
                        <li>
                            <i class="fas fa-clock"></i>
                            <span>Open: 9AM - 10PM (Everyday)</span>
                        </li>
                    </ul>

                    <div class="contact-us-btn">
                        <h4>Need Help?</h4>
                        <p>Our customer support team is here to help you</p>
                        <a href="{{ url('/contact') }}" class="btn-contact">
                            <i class="fas fa-headset"></i> Contact Us
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="footer-bottom">
        <div class="container">
            <div class="footer-bottom-content">
                <p class="copyright">
                    &copy; {{ date('Y') }} libasbd. All rights reserved.
                </p>
            </div>
        </div>
    </div>
</footer>

<style>
.main-footer {
    background: linear-gradient(135deg, #241d10 0%, #14100a 100%);
    color: #fff;
    margin-top: 60px;
}

.footer-top {
    padding: 60px 0 40px;
}

.footer-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 40px;
}

.footer-column h2 {
    font-size: 24px;
    font-weight: 700;
    margin-bottom: 10px;
}

.footer-logo .tagline {
    color: #94a3b8;
    font-size: 14px;
    margin-bottom: 20px;
}

.footer-description {
    color: #94a3b8;
    line-height: 1.6;
    margin-bottom: 25px;
}

.social-links {
    display: flex;
    gap: 12px;
}

.social-link {
    width: 40px;
    height: 40px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    transition: all 0.3s ease;
}

.social-link:hover {
    background: linear-gradient(135deg, #d4b25f 0%, #8a6d2f 100%);
    transform: translateY(-3px);
}

.footer-title {
    font-size: 18px;
    font-weight: 600;
    margin-bottom: 20px;
    color: #fff;
}

.footer-links {
    list-style: none;
    padding: 0;
    margin: 0;
}

.footer-links li {
    margin-bottom: 12px;
}

.footer-links a {
    color: #94a3b8;
    text-decoration: none;
    transition: all 0.3s ease;
}

.footer-links a:hover {
    color: #fff;
    padding-left: 5px;
}

.contact-info {
    list-style: none;
    padding: 0;
    margin: 0;
}

.contact-info li {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    margin-bottom: 15px;
    color: #94a3b8;
}

.contact-info i {
    color: #d4b25f;
    margin-top: 3px;
}

.contact-us-btn {
    background: rgba(255, 255, 255, 0.05);
    padding: 20px;
    border-radius: 12px;
    margin-top: 25px;
}

.contact-us-btn h4 {
    font-size: 16px;
    font-weight: 600;
    margin-bottom: 8px;
}

.contact-us-btn p {
    color: #94a3b8;
    font-size: 14px;
    margin-bottom: 15px;
}

.btn-contact {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 20px;
    background: linear-gradient(135deg, #d4b25f 0%, #8a6d2f 100%);
    color: #fff;
    text-decoration: none;
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-contact:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(212, 178, 95, 0.4);
}

.footer-bottom {
    background: rgba(0, 0, 0, 0.2);
    padding: 20px 0;
}

.footer-bottom-content {
    display: flex;
    justify-content: center;
    align-items: center;
}

.copyright {
    color: #94a3b8;
    font-size: 14px;
}

@media (max-width: 768px) {
    .footer-grid {
        grid-template-columns: 1fr;
        gap: 30px;
    }

    .footer-top {
        padding: 40px 0 30px;
    }
}
</style>