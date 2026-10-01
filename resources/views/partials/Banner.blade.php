<!-- Main Banner Section -->
<section class="banner-section">
    <div class="banner-container">
       <!-- Left Sidebar Categories (Desktop Only) -->
<aside class="desktop-category-sidebar">
    <div class="dcat-header">
        <i class="fas fa-layer-group"></i>
        <span>All Categories</span>
        <i class="fas fa-chevron-down dcat-header__arrow"></i>
    </div>
    <nav class="category-nav">
        <ul class="category-list">
            @if(isset($categories) && $categories->count() > 0)
    @foreach($categories->whereNull('parent_id') as $categoryItem)
    @php
        $submenus = $categoryItem->children ?? collect();
    @endphp
    <li class="category-item {{ $submenus->count() ? 'has-dropdown' : '' }}">
        <a href="{{ url('/products') }}?category={{ $categoryItem->slug }}" class="category-item-link">
            <span class="cat-name">{{ $categoryItem->name }}</span>
            @if($submenus->count())
                <i class="fas fa-chevron-right dropdown-icon"></i>
            @endif
        </a>

        @if($submenus->count() > 0)
        <div class="category-products-dropdown">
            <div class="dropdown-cat-title">
                <span>{{ $categoryItem->name }}</span>
                <a href="{{ url('/products') }}?category={{ $categoryItem->slug }}">View All <i class="fas fa-arrow-right"></i></a>
            </div>
            <div class="dropdown-products-list">
                @foreach($submenus as $sub)
                <a href="{{ url('/products') }}?category={{ $sub->slug }}" class="dropdown-product-link">
                    <span class="dp-info">
                        <span class="dp-name">{{ $sub->name }}</span>
                    </span>
                </a>
                @endforeach
            </div>
        </div>
        @endif
    </li>
    @endforeach
@else
                <li class="category-item">
                    <a href="{{ url('/products') }}" class="category-item-link">
                        <span class="cat-name">All Products</span>
                    </a>
                </li>
            @endif
        </ul>
    </nav>
</aside>
        <!-- Main Banner Slider -->
        <div class="banner-slider">
            <div class="slider-wrapper">
                @if(isset($banners) && $banners->count() > 0)
                    @foreach($banners as $index => $banner)
                    <div class="slide {{ $loop->first ? 'active' : '' }}">
                        <div class="slide-content">
                            @if($banner->link)
                                <a href="{{ $banner->link }}">
                            @endif
                            
                            <img src="{{ $banner->image_url }}"
                                 alt="{{ $banner->title }}"
                                 class="slide-image"
                                 title="{{ $banner->title }}"
                                 @if($loop->first) fetchpriority="high" @endif
                                 decoding="async">
                            
                            @if($banner->link)
                                </a>
                            @endif
                        </div>
                    </div>
                    @endforeach
                @else
                    <!-- Fallback banner if no banners in database -->
                    <div class="slide active">
                        <div class="slide-content">
                            <img src="https://images.unsplash.com/photo-1593784991095-a205069470b6?w=1200&h=400&fit=crop"
                                 alt="Default Banner"
                                 class="slide-image">
                        </div>
                    </div>
                @endif
            </div>

            <!-- Slider Navigation -->
            <button class="slider-nav prev" aria-label="Previous Slide">
                <i class="fas fa-chevron-left"></i>
            </button>
            <button class="slider-nav next" aria-label="Next Slide">
                <i class="fas fa-chevron-right"></i>
            </button>

            <!-- Slider Dots -->
            @if(isset($banners) && $banners->count() > 0)
            <div class="slider-dots">
                @foreach($banners as $index => $banner)
                <button class="dot {{ $loop->first ? 'active' : '' }}" data-slide="{{ $index }}"></button>
                @endforeach
            </div>
            @else
            <div class="slider-dots">
                <button class="dot active" data-slide="0"></button>
            </div>
            @endif
        </div>
    </div>
</section>

<!-- Trust Features Strip -->
<section class="trust-strip">
    <div class="container">
        <div class="trust-grid">
            <div class="trust-item">
                <i class="fas fa-money-bill-wave"></i>
                <div><strong>ক্যাশ অন ডেলিভারি</strong><span>Cash on Delivery</span></div>
            </div>
            <div class="trust-item">
                <i class="fas fa-truck-fast"></i>
                <div><strong>সারা দেশে ডেলিভারি</strong><span>Nationwide Home Delivery</span></div>
            </div>
            <div class="trust-item">
                <i class="fas fa-shield-halved"></i>
                <div><strong>100% অরিজিনাল</strong><span>Authentic Products</span></div>
            </div>
            <div class="trust-item">
                <i class="fas fa-headset"></i>
                <div><strong>সকাল ৯টা থেকে রাত ১০টা</strong><span>Customer Support</span></div>
            </div>
        </div>
    </div>
</section>

<style>
.trust-strip { background: #fff; border-bottom: 1px solid #f3e8cd; }
.trust-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 14px;
    padding: 16px 0;
}
.trust-item {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
    padding: 8px 10px;
}
.trust-item i {
    font-size: 26px;
    color: #a07f2a;
    flex-shrink: 0;
}
.trust-item strong {
    display: block;
    font-size: 14px;
    color: #1d1912;
    line-height: 1.3;
}
.trust-item span {
    display: block;
    font-size: 11.5px;
    color: #a39a82;
}
@media (max-width: 767px) {
    .trust-grid { grid-template-columns: repeat(2, 1fr); gap: 10px; padding: 12px 0; }
    .trust-item { justify-content: flex-start; }
    .trust-item i { font-size: 22px; }
}
</style>

<!-- Product Category Cards Section -->
<section class="category-cards-section">
    <div class="container">
        <div class="category-cards-slider">
            <button class="cards-nav prev-card" aria-label="Previous">
                <i class="fas fa-chevron-left"></i>
            </button>

            <div class="category-cards-wrapper">
            <div class="category-cards">
    @if(isset($categories) && $categories->count() > 0)
        @foreach($categories->whereNull('parent_id') as $categoryItem)
        @php
            // Parent cards count their own + subcategory products; subcategory cards count their own
            $productCount = $categoryItem->parent_id
                ? ($categoryItem->products_count ?? 0)
                : (($categoryItem->products_count ?? 0) + $categoryItem->children->sum('products_count'));
        @endphp
        <div class="category-card">
            <a href="{{ url('/products') }}?category={{ $categoryItem->slug }}">
                <div class="card-image">
                    @if($categoryItem->image)
                        <img src="{{ $categoryItem->image_url }}" alt="{{ $categoryItem->name }}" loading="lazy" decoding="async">
                    @else
                        <img src="{{ asset('images/placeholder.png') }}" alt="{{ $categoryItem->name }}" loading="lazy" decoding="async">
                    @endif
                </div>
                <div class="card-info">
                    <h3>{{ $categoryItem->name }}</h3>
                    <p class="card-count">{{ $productCount }} Products</p>
                </div>
            </a>
        </div>
        @endforeach
    @else
        <!-- Fallback if no categories -->
        <div class="category-card">
            <a href="{{ url('/products') }}">
                <div class="card-image">
                    <img src="https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=300&h=300&fit=crop" alt="All Products">
                </div>
                <div class="card-info">
                    <h3>Browse All Products</h3>
                    <p class="card-count">Shop Now</p>
                </div>
            </a>
        </div>
    @endif
</div>
            </div>

            <button class="cards-nav next-card" aria-label="Next">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>
    </div>
</section>

<style>
/* ── Standard carousel: sliding track, full uncropped images ── */
.banner-slider { touch-action: pan-y; align-self: center; background: transparent; }
.banner-slider .slider-wrapper {
    display: flex;
    height: auto;
    min-height: 0;
    transition: transform .55s cubic-bezier(.4, 0, .2, 1);
    will-change: transform;
}
.banner-slider .slider-wrapper.dragging { transition: none; cursor: grabbing; }
.banner-slider .slide {
    position: static;
    top: auto;
    left: auto;
    flex: 0 0 100%;
    width: 100%;
    height: auto;
    opacity: 1;
    visibility: visible;
    transition: none;
    user-select: none;
}
.banner-slider .slide {
    aspect-ratio: 8 / 3;
    max-height: 460px;
}
.banner-slider .slide-content { height: 100%; }
.banner-slider .slide-content > a { display: block; height: 100%; }
.banner-slider .slide-image {
    width: 100%;
    height: 100%;
    object-fit: fill;
    display: block;
    -webkit-user-drag: none;
}
</style>

<script>
// Banner Slider — standard sliding carousel
(function () {
    var slider = document.querySelector('.banner-slider');
    if (!slider) return;

    var track   = slider.querySelector('.slider-wrapper');
    var slides  = Array.from(track.querySelectorAll('.slide'));
    var dots    = Array.from(slider.querySelectorAll('.slider-dots .dot'));
    var prevBtn = slider.querySelector('.slider-nav.prev');
    var nextBtn = slider.querySelector('.slider-nav.next');
    if (!slides.length) return;

    var index = 0, timer = null;
    var dragging = false, startX = 0, deltaX = 0, moved = false;

    slides.forEach(function (s) {
        s.querySelectorAll('img').forEach(function (img) { img.draggable = false; });
    });

    function go(n) {
        index = (n + slides.length) % slides.length;
        track.style.transform = 'translateX(' + (-index * 100) + '%)';
        slides.forEach(function (s, k) { s.classList.toggle('active', k === index); });
        dots.forEach(function (d, k) { d.classList.toggle('active', k === index); });
    }

    function startAuto() {
        stopAuto();
        timer = setInterval(function () { go(index + 1); }, 5000);
    }
    function stopAuto() { if (timer) clearInterval(timer); timer = null; }

    if (nextBtn) nextBtn.addEventListener('click', function () { go(index + 1); startAuto(); });
    if (prevBtn) prevBtn.addEventListener('click', function () { go(index - 1); startAuto(); });
    dots.forEach(function (d, k) {
        d.addEventListener('click', function () { go(k); startAuto(); });
    });

    // Pause on hover & when tab hidden
    slider.addEventListener('mouseenter', stopAuto);
    slider.addEventListener('mouseleave', startAuto);
    document.addEventListener('visibilitychange', function () {
        document.hidden ? stopAuto() : startAuto();
    });

    // Swipe / drag (pointer events cover touch + mouse)
    track.addEventListener('pointerdown', function (e) {
        if (e.pointerType === 'mouse' && e.button !== 0) return;
        dragging = true; moved = false;
        startX = e.clientX; deltaX = 0;
        track.classList.add('dragging');
        track.setPointerCapture(e.pointerId);
        stopAuto();
    });
    track.addEventListener('pointermove', function (e) {
        if (!dragging) return;
        deltaX = e.clientX - startX;
        if (Math.abs(deltaX) > 8) moved = true;
        track.style.transform = 'translateX(calc(' + (-index * 100) + '% + ' + deltaX + 'px))';
    });
    function endDrag() {
        if (!dragging) return;
        dragging = false;
        track.classList.remove('dragging');
        if (Math.abs(deltaX) > 60) {
            go(index + (deltaX < 0 ? 1 : -1));
        } else {
            go(index); // snap back
        }
        deltaX = 0;
        startAuto();
    }
    track.addEventListener('pointerup', endDrag);
    track.addEventListener('pointercancel', endDrag);

    // Suppress link clicks after a real drag
    slider.addEventListener('click', function (e) {
        if (moved) { e.preventDefault(); e.stopPropagation(); moved = false; }
    }, true);

    go(0);
    startAuto();
})();

// Category Cards Slider
const cardsWrapper = document.querySelector('.category-cards');
const prevCard = document.querySelector('.prev-card');
const nextCard = document.querySelector('.next-card');

let scrollAmount = 0;

nextCard.addEventListener('click', () => {
    const cardWidth = document.querySelector('.category-card').offsetWidth + 20; // card width + gap
    cardsWrapper.scrollBy({ left: cardWidth * 2, behavior: 'smooth' });
});

prevCard.addEventListener('click', () => {
    const cardWidth = document.querySelector('.category-card').offsetWidth + 20;
    cardsWrapper.scrollBy({ left: -cardWidth * 2, behavior: 'smooth' });
});

// Show/hide navigation buttons based on scroll position
function updateCardNavButtons() {
    const maxScroll = cardsWrapper.scrollWidth - cardsWrapper.clientWidth;
    
    if (cardsWrapper.scrollLeft <= 0) {
        prevCard.style.opacity = '0.3';
        prevCard.style.pointerEvents = 'none';
    } else {
        prevCard.style.opacity = '1';
        prevCard.style.pointerEvents = 'auto';
    }
    
    if (cardsWrapper.scrollLeft >= maxScroll - 10) {
        nextCard.style.opacity = '0.3';
        nextCard.style.pointerEvents = 'none';
    } else {
        nextCard.style.opacity = '1';
        nextCard.style.pointerEvents = 'auto';
    }
}

cardsWrapper.addEventListener('scroll', updateCardNavButtons);
window.addEventListener('resize', updateCardNavButtons);
updateCardNavButtons();
</script>