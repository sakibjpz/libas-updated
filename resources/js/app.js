// ========================================
// PICKABOO-STYLE E-COMMERCE MAIN JAVASCRIPT
// ========================================

import './bootstrap';

// Mobile menu toggle functionality
document.addEventListener('DOMContentLoaded', function() {
    console.log('Pickaboo-style e-commerce loaded');
    
    // Mobile menu toggle (will be implemented when we create header)
    const mobileMenuToggle = () => {
        const menuBtn = document.querySelector('.mobile-menu-btn');
        const mainNav = document.querySelector('.main-nav');
        
        if (menuBtn && mainNav) {
            menuBtn.addEventListener('click', function() {
                mainNav.classList.toggle('active');
                document.body.classList.toggle('menu-open');
            });
        }
    };
    
    // Cart functionality placeholder
    const initializeCart = () => {
        const cartButtons = document.querySelectorAll('.add-to-cart');
        
        cartButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const productId = this.dataset.productId;
                console.log('Adding product to cart:', productId);
                // Will be connected to your backend CartController
            });
        });
    };
    
    // Search functionality placeholder
    const initializeSearch = () => {
        const searchInput = document.querySelector('.search-input');
        const searchBtn = document.querySelector('.search-btn');
        
        if (searchInput && searchBtn) {
            searchBtn.addEventListener('click', function() {
                const query = searchInput.value.trim();
                if (query) {
                    window.location.href = `/products?search=${encodeURIComponent(query)}`;
                }
            });
            
            searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    const query = this.value.trim();
                    if (query) {
                        window.location.href = `/products?search=${encodeURIComponent(query)}`;
                    }
                }
            });
        }
    };
    
    // Initialize all functions
    mobileMenuToggle();
    initializeCart();
    initializeSearch();
});
