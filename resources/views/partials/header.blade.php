<header class="main-header">
    <div class="container">
        <!-- Top Header Bar -->
        <div class="header-top">
            <!-- Mobile Menu Toggle -->
            <button class="mobile-menu-toggle" aria-label="মেনু খুলুন">
                <i class="fas fa-bars"></i>
            </button>

            <!-- Logo -->
<div class="logo">
    <a href="{{ url('/') }}" class="logo-link">
        <img src="{{ asset('images/logos/logo.png') }}" alt="LibasBD" class="logo-img">
    </a>
</div>

<style>
.logo-link {
    display: inline-flex;
    align-items: center;
    text-decoration: none;
}

.logo-img {
    height: 55px;
    width: auto;
    display: block;
}

@media (max-width: 767px) {
    .logo-img {
        height: 40px;
    }
}
</style>

            <!-- Search Bar (Desktop) -->
            <div class="search-container">
                <form action="{{ url('/products') }}" method="GET" class="search-form">
                    <div class="search-input-group">
                        <input type="text" 
                               name="search" 
                               class="search-input" 
                               placeholder="Search for products, brands and more"
                               autocomplete="off"
                               aria-label="পণ্য খুঁজুন">
                        <button type="submit" class="search-button" aria-label="অনুসন্ধান করুন">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
            </div>

            <!-- User Actions -->
            <div class="user-actions">
                <!-- Track Parcel -->
                <a href="{{ route('track') }}" class="login-btn">
                    <i class="fas fa-box-open"></i> Track Order
                </a>

                <!-- Cart -->
                <a href="{{ url('/cart') }}" class="cart-link" aria-label="কার্ট দেখুন">
                    <i class="fas fa-shopping-cart"></i>
                    <span class="cart-text">Cart</span>
                    @php
                        $cartCount = 0;
                        $cart = session()->get('cart', []);
                        foreach ($cart as $item) {
                            $cartCount += $item['quantity'] ?? 1;
                        }
                    @endphp
                    @if($cartCount > 0)
                        <span class="cart-count-badge">{{ $cartCount }}</span>
                    @endif
                </a>
            </div>
        </div>

        <!-- Mobile Search Bar -->
        <div class="mobile-search-container">
            <form action="{{ url('/products') }}" method="GET" class="search-form">
                <div class="search-input-group">
                    <input type="text" 
                           name="search" 
                           class="search-input" 
                           placeholder="Search for products, brands and more"
                           autocomplete="off"
                           aria-label="পণ্য খুঁজুন">
                    <button type="submit" class="search-button" aria-label="অনুসন্ধান করুন">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>
        </div>

    </div>

    <!-- Desktop Horizontal Navigation -->
    <nav class="main-nav" aria-label="প্রধান মেনু">
            <ul class="main-nav-list">
                @if(isset($navMenus) && $navMenus->count())
                    @php
                        $navIsActive = function ($menu) {
                            $match = function ($u) {
                                if ($u === '#') return false;
                                if (!\Illuminate\Support\Str::startsWith($u, 'http')) $u = url($u);
                                return rtrim(request()->fullUrl(), '/') === rtrim($u, '/')
                                    || (!request()->getQueryString() && rtrim(request()->url(), '/') === rtrim($u, '/'));
                            };
                            if ($match($menu->getUrl())) return true;
                            return $menu->children->contains(fn ($c) => $match($c->getUrl()));
                        };
                    @endphp
                    @foreach($navMenus as $menu)
                        @php $submenus = $menu->children; @endphp
                        <li class="main-nav-item {{ $submenus->count() ? 'has-sub' : '' }} {{ $navIsActive($menu) ? 'active' : '' }}">
                            <a href="{{ $menu->getUrl() }}" class="main-nav-link" {{ $submenus->count() ? 'aria-haspopup="true" aria-expanded="false"' : '' }}>
                                {{ $menu->title }}
                                @if($submenus->count())
                                    <i class="fas fa-chevron-down main-nav-caret"></i>
                                @endif
                            </a>
                            @if($submenus->count())
                                <ul class="main-nav-sub">
                                    @foreach($submenus as $sub)
                                        <li><a href="{{ $sub->getUrl() }}" class="main-nav-sub-link">{{ $sub->title }}</a></li>
                                    @endforeach
                                </ul>
                            @endif
                        </li>
                    @endforeach
                @elseif(isset($categories) && $categories->count())
                    @foreach($categories as $categoryItem)
                        <li class="main-nav-item">
                            <a href="{{ route('products.index') }}?category={{ urlencode($categoryItem->slug) }}" class="main-nav-link">{{ $categoryItem->name }}</a>
                        </li>
                    @endforeach
                @endif
            </ul>
        </nav>
</header>

<!-- Category Navigation Sidebar (Mobile) -->
<div class="category-sidebar">
    <div class="sidebar-header">
        <h3>Categories</h3>
        <button class="sidebar-close" aria-label="বন্ধ করুন">
            <i class="fas fa-times"></i>
        </button>
    </div>
    <nav class="sidebar-nav">
        <ul class="category-menu">
            @if(isset($categories) && $categories->count())
                @foreach($categories->whereNull('parent_id') as $categoryItem)
                    @php $submenus = $categoryItem->children ?? collect(); @endphp
                    <li class="category-with-dropdown">
                        @if($submenus->count())
                            <button type="button" class="category-link" onclick="toggleCat(this)">
                                <span>{{ $categoryItem->name }}</span>
                                <i class="fas fa-chevron-right dropdown-arrow"></i>
                            </button>
                            <div class="category-dropdown" style="display:none;">
                                <div class="dropdown-products-list">
                                    <a href="{{ route('products.index') }}?category={{ urlencode($categoryItem->slug) }}" class="dropdown-product-link">
                                        <span>All {{ $categoryItem->name }}</span>
                                    </a>
                                    @foreach($submenus as $sub)
                                        <a href="{{ route('products.index') }}?category={{ urlencode($sub->slug) }}" class="dropdown-product-link">
                                            <span>{{ $sub->name }}</span>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <a href="{{ route('products.index') }}?category={{ urlencode($categoryItem->slug) }}" class="category-link">
                                <span>{{ $categoryItem->name }}</span>
                            </a>
                        @endif
                    </li>
                @endforeach
            @endif
            <li class="category-with-dropdown">
                <a href="{{ route('products.index') }}" class="category-link">
                    <span>All Products</span>
                </a>
            </li>
        </ul>
    </nav>
</div>

<!-- Sidebar Overlay -->
<div class="sidebar-overlay"></div>

<style>
    .cart-link {
        position: relative;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
    
    .cart-count-badge {
        position: absolute;
        top: -8px;
        right: -8px;
        background: #ef4444;
        color: white;
        border-radius: 50%;
        width: 18px;
        height: 18px;
        font-size: 11px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
    }

    button.category-link {
        background: none;
        border: none;
        width: 100%;
        cursor: pointer;
        font-family: inherit;
        touch-action: manipulation;
        -webkit-tap-highlight-color: transparent;
    }

    .category-with-dropdown.open .dropdown-arrow {
        transform: rotate(90deg);
    }

    /* Desktop horizontal navigation */
    .main-nav {
        display: none;
        background: #fff;
        border-bottom: 1px solid #efe7d5;
    }
    @media (min-width: 768px) {
        .main-nav { display: block; }
    }
    .main-nav-list {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 8px;
        list-style: none;
        margin: 0;
        padding: 0;
    }
    .main-nav-item { position: relative; }
    .main-nav-link {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 14px 20px;
        font-size: 15px;
        font-weight: 500;
        color: #1d1912;
        text-decoration: none;
        border-bottom: 2px solid transparent;
        margin-bottom: -1px;
        transition: color .15s, border-color .15s;
    }
    .main-nav-link:hover { color: #9c7c33; border-bottom-color: #9c7c33; }
    .main-nav-item.active > .main-nav-link {
        color: #9c7c33;
        border-bottom-color: #9c7c33;
    }
    .main-nav-caret { font-size: 11px; transition: transform .15s; }
    .main-nav-item.open > .main-nav-link .main-nav-caret,
    .main-nav-item:hover > .main-nav-link .main-nav-caret { transform: rotate(180deg); }
    .main-nav-sub {
        position: absolute;
        top: 100%;
        left: 0;
        min-width: 200px;
        background: #fff;
        border: 1px solid #f3e8cd;
        border-radius: 0 0 10px 10px;
        box-shadow: 0 12px 32px rgba(29, 25, 18, 0.15);
        list-style: none;
        margin: 0;
        padding: 6px 0;
        display: none;
        z-index: 1000;
    }
    .main-nav-item:hover > .main-nav-sub,
    .main-nav-item.open > .main-nav-sub { display: block; }
    .main-nav-sub-link {
        display: block;
        padding: 10px 18px;
        font-size: 14px;
        color: #1d1912;
        text-decoration: none;
        white-space: nowrap;
    }
    .main-nav-sub-link:hover { background: #faf6ec; color: #9c7c33; }

    /* Hide hamburger on desktop */
    @media (min-width: 768px) {
        .mobile-menu-toggle {
            display: none;
        }
    }
</style>

<script>
// Mobile Menu Toggle
const menuToggle = document.querySelector('.mobile-menu-toggle');
const sidebar = document.querySelector('.category-sidebar');
const sidebarClose = document.querySelector('.sidebar-close');
const overlay = document.querySelector('.sidebar-overlay');

menuToggle.addEventListener('click', () => {
    sidebar.classList.add('active');
    overlay.classList.add('active');
    document.body.style.overflow = 'hidden';
});

sidebarClose.addEventListener('click', closeSidebar);
overlay.addEventListener('click', closeSidebar);

function closeSidebar() {
    sidebar.classList.remove('active');
    overlay.classList.remove('active');
    document.body.style.overflow = '';
}

// Category Dropdown Toggle
// Directly controls style.display on the dropdown div — no CSS class involved
function toggleCat(btn) {
    var li = btn.parentElement;
    var dropdown = li.querySelector('.category-dropdown');

    // If no dropdown exists for this category, do nothing
    if (!dropdown) return;

    // Close all OTHER dropdowns first
    var allDropdowns = document.querySelectorAll('.category-dropdown');
    for (var i = 0; i < allDropdowns.length; i++) {
        if (allDropdowns[i] !== dropdown) {
            allDropdowns[i].style.display = 'none';
            // Also remove .open from their parent li for chevron rotation
            allDropdowns[i].parentElement.classList.remove('open');
        }
    }

    // Now toggle THIS one
    if (dropdown.style.display === 'block') {
        // Currently open — close it
        dropdown.style.display = 'none';
        li.classList.remove('open');
    } else {
        // Currently closed — open it
        dropdown.style.display = 'block';
        li.classList.add('open');
    }
}

// Desktop nav: click-toggle for parents with submenus (touch devices / '#' links)
document.querySelectorAll('.main-nav-item.has-sub > .main-nav-link').forEach(function (link) {
    link.addEventListener('click', function (e) {
        if (link.getAttribute('href') === '#') e.preventDefault();
        var item = link.parentElement;
        var wasOpen = item.classList.contains('open');
        document.querySelectorAll('.main-nav-item.open').forEach(function (el) {
            el.classList.remove('open');
            el.querySelector('.main-nav-link').setAttribute('aria-expanded', 'false');
        });
        if (!wasOpen) {
            item.classList.add('open');
            link.setAttribute('aria-expanded', 'true');
        }
    });
});

document.addEventListener('click', function (e) {
    if (!e.target.closest('.main-nav-item')) {
        document.querySelectorAll('.main-nav-item.open').forEach(function (el) {
            el.classList.remove('open');
            el.querySelector('.main-nav-link').setAttribute('aria-expanded', 'false');
        });
    }
});
</script>

<style>
.search-input-group { position: relative; }

.search-suggestions {
    position: absolute;
    top: calc(100% + 6px);
    left: 0;
    right: 0;
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 12px 32px rgba(29, 25, 18, 0.18);
    overflow-y: auto;
    max-height: 380px;
    z-index: 1001;
    display: none;
}
.search-suggestions.open { display: block; }

.sg-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 10px 14px;
    text-decoration: none;
    color: #1d1912;
}
.sg-item:hover, .sg-item.active { background: #faf6ec; }

.sg-thumb {
    width: 42px;
    height: 42px;
    object-fit: cover;
    border-radius: 6px;
    flex-shrink: 0;
    background: #f3e8cd;
}
.sg-info { flex: 1; min-width: 0; }
.sg-name {
    display: block;
    font-size: 14px;
    font-weight: 500;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.sg-name mark { background: #f3e8cd; color: #9c7c33; padding: 0; }
.sg-cat { font-size: 11px; color: #a39a82; }

.sg-price {
    font-size: 13px;
    font-weight: 600;
    color: #9c7c33;
    white-space: nowrap;
}
.sg-price del { color: #a39a82; font-weight: 400; margin-left: 5px; font-size: 11px; }

.sg-status {
    padding: 14px;
    font-size: 13px;
    color: #a39a82;
    text-align: center;
}

.sg-all {
    display: block;
    padding: 10px 14px;
    text-align: center;
    font-size: 13px;
    font-weight: 600;
    color: #9c7c33;
    background: #faf6ec;
    text-decoration: none;
    border-top: 1px solid #f3e8cd;
}
.sg-all:hover { background: #f3e8cd; }
</style>

<script>
(function () {
    document.querySelectorAll('.search-input-group').forEach(function (group) {
        var input = group.querySelector('.search-input');
        if (!input) return;

        var box = document.createElement('div');
        box.className = 'search-suggestions';
        group.appendChild(box);

        var timer = null, lastQuery = '', activeIdx = -1, items = [];

        function close() { box.classList.remove('open'); activeIdx = -1; }
        function esc(s) {
            return String(s).replace(/[&<>"']/g, function (c) {
                return {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c];
            });
        }
        function highlight(name, q) {
            var safe = q.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
            return esc(name).replace(new RegExp('(' + safe + ')', 'ig'), '<mark>$1</mark>');
        }

        function render(list, q) {
            if (!list.length) {
                box.innerHTML = '<div class="sg-status">No products found for &quot;' + esc(q) + '&quot;</div>';
                items = [];
            } else {
                box.innerHTML = list.map(function (p) {
                    return '<a href="' + p.url + '" class="sg-item">' +
                        '<img src="' + p.image + '" class="sg-thumb" alt="" loading="lazy">' +
                        '<span class="sg-info">' +
                            '<span class="sg-name">' + highlight(p.name, q) + '</span>' +
                            (p.category ? '<span class="sg-cat">' + esc(p.category) + '</span>' : '') +
                        '</span>' +
                        '<span class="sg-price">&#2547;' + p.price +
                            (p.original_price > p.price ? '<del>&#2547;' + p.original_price + '</del>' : '') +
                        '</span>' +
                    '</a>';
                }).join('') +
                '<a href="' + (window.LIBAS_BASE || '') + '/products?search=' + encodeURIComponent(q) + '" class="sg-all">See all results for &quot;' + esc(q) + '&quot;</a>';
                items = Array.from(box.querySelectorAll('.sg-item'));
            }
            box.classList.add('open');
        }

        input.addEventListener('input', function () {
            var q = input.value.trim();
            clearTimeout(timer);
            activeIdx = -1;
            if (q.length < 2) { close(); lastQuery = ''; return; }
            if (q === lastQuery) { box.classList.add('open'); return; }
            timer = setTimeout(function () {
                lastQuery = q;
                box.innerHTML = '<div class="sg-status">Searching&hellip;</div>';
                box.classList.add('open');
                fetch((window.LIBAS_BASE || '') + '/search/suggestions?q=' + encodeURIComponent(q), { headers: { 'Accept': 'application/json' } })
                    .then(function (r) { return r.json(); })
                    .then(function (data) { if (input.value.trim() === q) render(data, q); })
                    .catch(function () { box.innerHTML = '<div class="sg-status">Search unavailable</div>'; });
            }, 250);
        });

        input.addEventListener('keydown', function (e) {
            if (!box.classList.contains('open') || !items.length) {
                if (e.key === 'Escape') close();
                return;
            }
            if (e.key === 'ArrowDown' || e.key === 'ArrowUp') {
                e.preventDefault();
                activeIdx = e.key === 'ArrowDown'
                    ? (activeIdx + 1) % items.length
                    : (activeIdx - 1 + items.length) % items.length;
                items.forEach(function (el, i) { el.classList.toggle('active', i === activeIdx); });
            } else if (e.key === 'Enter' && activeIdx >= 0) {
                e.preventDefault();
                window.location.href = items[activeIdx].href;
            } else if (e.key === 'Escape') {
                close();
            }
        });

        input.addEventListener('blur', function () { setTimeout(close, 150); });
        input.addEventListener('focus', function () {
            if (box.innerHTML.trim() && lastQuery.length >= 2) box.classList.add('open');
        });
    });
})();
</script>