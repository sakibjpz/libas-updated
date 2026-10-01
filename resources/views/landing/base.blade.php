@php use Illuminate\Support\Str; @endphp
<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $product->meta_title ?: ($product->name . ' | LibasBD') }}</title>
    <meta name="description" content="{{ $product->meta_description ?: Str::limit(strip_tags((string) $product->description), 160, '') }}">
    <link rel="canonical" href="{{ rtrim(config('app.url'), '/') . route('landing.show', $product, false) }}">
    <meta property="og:type" content="product">
    <meta property="og:title" content="{{ $product->name }}">
    <meta property="og:description" content="{{ $product->meta_description ?: Str::limit(strip_tags((string) $product->description), 160, '') }}">
    <meta property="og:url" content="{{ rtrim(config('app.url'), '/') . route('landing.show', $product, false) }}">
    <meta property="og:image" content="{{ $product->image_url }}">
    <script>window.LIBAS_BASE = "{{ rtrim(url('/'), '/') }}";</script>
    <script>
        document.addEventListener('error', function (e) {
            var t = e.target;
            if (t && t.tagName === 'IMG' && !t.dataset.imgFallback) {
                t.dataset.imgFallback = '1';
                t.src = '{{ asset('images/placeholder.png') }}';
            }
        }, true);

        // Fill missing input placeholders from their label (or humanized name)
        document.addEventListener('DOMContentLoaded', function () {
            var sel = 'input:not([type=hidden]):not([type=checkbox]):not([type=radio]):not([type=file]):not([type=submit]):not([type=button]):not([placeholder]), textarea:not([placeholder])';
            document.querySelectorAll(sel).forEach(function (el) {
                var label = el.id ? document.querySelector('label[for="' + el.id + '"]') : null;
                if (!label && el.parentElement) label = el.parentElement.querySelector('label');
                if (!label && el.closest('.form-group, .mb-3, .cp-field, .form-field')) label = el.closest('.form-group, .mb-3, .cp-field, .form-field').querySelector('label');
                if (label) {
                    el.placeholder = label.textContent.replace(/[*:]/g, '').trim();
                } else if (el.name) {
                    el.placeholder = 'Enter ' + el.name.replace(/[\[\]_-]+/g, ' ').trim();
                }
            });
        });
    </script>
    <style>
        @font-face {
            font-family: 'SolaimanLipi';
            src: url('{{ asset('fonts/solaimanlipi-normal-v1.0.woff2') }}') format('woff2');
            font-weight: 400; font-style: normal; font-display: swap;
        }
        @font-face {
            font-family: 'SolaimanLipi';
            src: url('{{ asset('fonts/solaimanlipi-bold-v1.0.woff2') }}') format('woff2');
            font-weight: 600 700; font-style: normal; font-display: swap;
        }
    </style>
    @if(config('services.facebook.pixel_id'))
    <script defer>
        !function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
        n.callMethod.apply(n,arguments):n.queue.push(arguments)};
        if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
        t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window, document,'script',
        'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '{{ config('services.facebook.pixel_id') }}');
        fbq('track', 'PageView');
        fbq('track', 'ViewContent', {
            content_name: @json($product->name),
            content_ids: @json([(string) $product->id]),
            content_type: 'product',
            value: {{ $product->price }},
            currency: 'BDT'
        }, {eventID: '{{ $pixelEventId ?? '' }}'});
    </script>
    <noscript><img height="1" width="1" style="display:none"
        src="https://www.facebook.com/tr?id={{ config('services.facebook.pixel_id') }}&ev=PageView&noscript=1"
        alt="" /></noscript>
    @endif
    <script type="application/ld+json">{!! json_encode([
        '@context' => 'https://schema.org',
        '@type' => 'Product',
        'name' => $product->name,
        'image' => [$product->image_url],
        'description' => Str::limit(strip_tags((string) $product->description), 300, ''),
        'brand' => ['@type' => 'Brand', 'name' => $product->brand ?: 'LibasBD'],
        'offers' => ['@type' => 'Offer', 'priceCurrency' => 'BDT', 'price' => $product->price,
            'availability' => ($product->stock ?? 0) > 0 ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock'],
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'SolaimanLipi', 'Hind Siliguri', 'Segoe UI', system-ui, sans-serif;
            background: var(--lp-bg);
            color: var(--lp-text);
            line-height: 1.6;
        }
        img { max-width: 100%; display: block; }

        .lp-topbar {
            background: var(--lp-bar-bg);
            color: var(--lp-bar-text);
            text-align: center;
            font-size: 13px;
            font-weight: 600;
            padding: 10px 15px;
            letter-spacing: .3px;
        }
        .lp-topbar i { margin-right: 6px; }

        /* Scrolling announcement */
        .lp-marquee { overflow: hidden; white-space: nowrap; }
        .lp-marquee > span {
            display: inline-block;
            padding-left: 100%;
            animation: lpMarquee 20s linear infinite;
        }
        .lp-marquee:hover > span { animation-play-state: paused; }
        @keyframes lpMarquee { to { transform: translateX(-100%); } }

        /* ── Sticky header ── */
        .lp-header {
            position: sticky;
            top: 0;
            z-index: 500;
            background: var(--lp-surface);
            border-bottom: 1px solid var(--lp-chip-border);
            box-shadow: 0 4px 20px rgba(0,0,0,.08);
        }
        .lp-header__inner {
            max-width: 1200px;
            margin: 0 auto;
            padding: 10px 18px;
            display: flex;
            align-items: center;
            gap: 24px;
        }
        .lp-logo { flex-shrink: 0; display: inline-block; }
        .lp-logo img {
            height: 42px; width: auto; display: block;
            transition: transform .3s ease, filter .3s ease;
        }
        .lp-logo:hover img { transform: scale(1.06); filter: drop-shadow(0 4px 10px rgba(0,0,0,.18)); }
        .lp-header .lp-nav { flex: 1; border-bottom: none; background: transparent; }
        .lp-header[data-bg="dark"] { border-bottom-color: rgba(255,255,255,.12); }
        .lp-header[data-bg="dark"] .lp-nav-link { color: #f1ede4; }
        .lp-header[data-bg="dark"] .lp-nav-link:hover,
        .lp-header[data-bg="dark"] .lp-nav-item.active > .lp-nav-link { color: #f5d98a; }
        .lp-header[data-bg="dark"] .lp-nav-sub { background: #1e1a12; border-color: rgba(255,255,255,.12); }
        .lp-header[data-bg="dark"] .lp-nav-sub-link { color: #e8e2d4; }
        .lp-header[data-bg="dark"] .lp-nav-sub-link:hover { color: #f5d98a; }
        .lp-header-cta {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            background: var(--lp-btn-bg);
            color: var(--lp-btn-text);
            padding: 11px 22px;
            border-radius: 10px;
            font-weight: 700;
            font-size: 14px;
            text-decoration: none;
            white-space: nowrap;
            box-shadow: 0 6px 16px var(--lp-btn-shadow);
            transition: all .2s ease;
            animation: lpCtaPulse 2.4s ease-in-out infinite;
        }
        .lp-header-cta:hover { transform: translateY(-2px); filter: brightness(1.07); }
        @keyframes lpCtaPulse {
            0%, 100% { box-shadow: 0 6px 16px var(--lp-btn-shadow); }
            50% { box-shadow: 0 6px 22px var(--lp-btn-shadow), 0 0 0 5px var(--lp-accent-soft); }
        }
        html { scroll-behavior: smooth; }

        /* ── Gallery thumbs + video ── */
        .lp-thumbs {
            display: flex;
            gap: 10px;
            margin-top: 12px;
            flex-wrap: wrap;
        }
        .lp-thumb {
            width: 64px; height: 64px;
            border-radius: 10px;
            border: 2px solid var(--lp-chip-border);
            background: var(--lp-surface);
            overflow: hidden;
            cursor: pointer;
            padding: 0;
            transition: all .15s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .lp-thumb img { width: 100%; height: 100%; object-fit: cover; }
        .lp-thumb:hover { border-color: var(--lp-accent); }
        .lp-thumb.is-active { border-color: var(--lp-accent); box-shadow: 0 0 0 3px var(--lp-accent-soft); }
        .lp-thumb--video { background: var(--lp-bar-bg); color: var(--lp-bar-text); font-size: 16px; }
        .lp-thumb--video:hover { background: var(--lp-accent); color: var(--lp-btn-text); }

        .lp-video-embed {
            position: absolute; inset: 0; z-index: 3; background: #000;
        }
        .lp-video-embed iframe { width: 100%; height: 100%; border: 0; display: block; }

        .lp-wrap { max-width: 1080px; margin: 0 auto; padding: 0 18px; }

        /* ── Hero ── */
        .lp-hero {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            align-items: center;
            padding: 42px 0 30px;
        }
        .lp-hero__img {
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 20px 50px rgba(0,0,0,.22);
            position: relative;
        }
        .lp-hero__img img { width: 100%; height: 100%; object-fit: contain; aspect-ratio: 4/5; background: #f4f4f5; }
        .lp-badge {
            position: absolute; top: 16px; left: 16px;
            background: var(--lp-badge-bg); color: #fff;
            font-size: 13px; font-weight: 700;
            padding: 7px 14px; border-radius: 30px;
        }
        .lp-cat { color: var(--lp-accent); font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; }
        .lp-title { font-size: clamp(24px, 3.4vw, 34px); font-weight: 800; line-height: 1.25; margin: 8px 0 14px; }
        .lp-price { display: flex; align-items: baseline; gap: 14px; margin-bottom: 18px; }
        .lp-price__now { font-size: 34px; font-weight: 800; color: var(--lp-accent); }
        .lp-price__was { font-size: 19px; color: var(--lp-muted); text-decoration: line-through; }
        .lp-off { background: var(--lp-badge-bg); color:#fff; font-size:12px; font-weight:700; padding:4px 10px; border-radius: 20px; }

        .lp-points { list-style: none; margin-bottom: 22px; }
        .lp-points li { padding: 6px 0; font-size: 15px; display: flex; gap: 10px; align-items: flex-start; }
        .lp-points i { color: var(--lp-accent); margin-top: 4px; }

        .lp-opt-label { font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: .5px; color: var(--lp-muted); margin-bottom: 8px; display: block; }
        .lp-opts { display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 16px; }
        .lp-chip {
            padding: 9px 18px; border: 1.5px solid var(--lp-chip-border); border-radius: 8px;
            background: var(--lp-surface); color: var(--lp-text); font-size: 14px; cursor: pointer; transition: .15s;
        }
        .lp-chip.sel { border-color: var(--lp-accent); background: var(--lp-accent-soft); color: var(--lp-accent); font-weight: 700; }
        .lp-swatch { width: 34px; height: 34px; border-radius: 50%; border: 2px solid var(--lp-chip-border); cursor: pointer; transition: .15s; }
        .lp-swatch.sel { border-color: var(--lp-accent); box-shadow: 0 0 0 3px var(--lp-accent-soft); }

        .lp-qty { display: inline-flex; align-items: center; border: 1.5px solid var(--lp-chip-border); border-radius: 8px; overflow: hidden; margin-bottom: 18px; }
        .lp-qty button { width: 40px; height: 42px; border: none; background: var(--lp-accent-soft); color: var(--lp-accent); font-size: 14px; cursor: pointer; }
        .lp-qty input { width: 56px; height: 42px; border: none; text-align: center; font-size: 16px; font-weight: 700; background: var(--lp-surface); color: var(--lp-text); }

        .lp-ctas { display: flex; gap: 12px; flex-wrap: wrap; }
        .lp-btn {
            flex: 1; min-width: 170px;
            padding: 16px 20px; border: none; border-radius: 10px;
            font-size: 16px; font-weight: 700; cursor: pointer;
            display: flex; align-items: center; justify-content: center; gap: 10px;
            transition: .18s; text-decoration: none; text-align: center;
        }
        .lp-btn--primary { background: var(--lp-btn-bg); color: var(--lp-btn-text); box-shadow: 0 8px 22px var(--lp-btn-shadow); }
        .lp-btn--primary:hover { transform: translateY(-2px); filter: brightness(1.05); }
        .lp-btn--ghost { background: transparent; color: var(--lp-accent); border: 2px solid var(--lp-accent); }
        .lp-btn--ghost:hover { background: var(--lp-accent); color: #fff; }
        .lp-btn:disabled { opacity: .6; cursor: not-allowed; transform: none; }

        .lp-err { color: #e63950; font-size: 13px; margin: 0 0 12px; display: none; align-items: center; gap: 6px; font-weight: 600; }
        .lp-err--shake { animation: lpShake .4s ease; }
        @keyframes lpShake { 0%,100%{transform:translateX(0)} 25%{transform:translateX(-6px)} 75%{transform:translateX(6px)} }

        /* ── Trust strip ── */
        .lp-trust {
            display: grid; grid-template-columns: repeat(4, 1fr); gap: 14px;
            padding: 26px 0;
        }
        .lp-trust__item {
            background: var(--lp-surface); border-radius: 12px; padding: 18px 14px;
            text-align: center; border: 1px solid var(--lp-chip-border);
        }
        .lp-trust__item i { font-size: 22px; color: var(--lp-accent); margin-bottom: 8px; }
        .lp-trust__item b { display: block; font-size: 14px; }
        .lp-trust__item span { font-size: 12px; color: var(--lp-muted); }

        /* ── Description ── */
        .lp-desc {
            background: var(--lp-surface); border-radius: 16px; padding: 32px;
            margin-bottom: 30px; border: 1px solid var(--lp-chip-border);
        }
        .lp-desc h2 { font-size: 21px; margin-bottom: 12px; color: var(--lp-accent); }
        .lp-desc p, .lp-desc div { font-size: 15.5px; color: var(--lp-muted); }

        /* ── Bottom CTA ── */
        .lp-cta {
            background: var(--lp-hero-bg);
            border-radius: 16px; padding: 36px 28px; text-align: center; margin-bottom: 36px;
        }
        .lp-cta h3 { font-size: 22px; margin-bottom: 6px; color: var(--lp-cta-text, var(--lp-text)); }
        .lp-cta p { color: var(--lp-muted); margin-bottom: 18px; }
        .lp-cta .lp-btn { max-width: 340px; margin: 0 auto; }

        .lp-footer {
            text-align: center; padding: 22px 0; font-size: 13px; color: var(--lp-muted);
            border-top: 1px solid var(--lp-chip-border);
        }
        .lp-footer a { color: var(--lp-accent); text-decoration: none; }

        .lp-toast {
            position: fixed; top: 18px; right: 18px; z-index: 999;
            background: #fff; color: #1d1912; padding: 13px 20px; border-radius: 9px;
            box-shadow: 0 8px 26px rgba(0,0,0,.2); display: none;
            align-items: center; gap: 10px; font-size: 14px;
        }
        .lp-toast.show { display: flex; }
        .lp-toast--success { border-left: 4px solid #10b981; }
        .lp-toast--error { border-left: 4px solid #e63950; }

        /* ── Horizontal nav (desktop) ── */
        .lp-nav { display: none; border-bottom: 1px solid var(--lp-chip-border); background: var(--lp-bg); }
        @media (min-width: 768px) { .lp-nav { display: block; } }
        .lp-nav-list { display: flex; flex-wrap: wrap; justify-content: center; gap: 8px; list-style: none; }
        .lp-nav-item { position: relative; }
        .lp-nav-link {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 14px 20px; font-size: 14.5px; font-weight: 500;
            color: var(--lp-text); text-decoration: none;
            position: relative;
            transition: color .15s;
        }
        .lp-nav-link::after {
            content: '';
            position: absolute;
            bottom: 0; left: 50%;
            width: 0; height: 2px;
            background: var(--lp-accent);
            transform: translateX(-50%);
            transition: width .25s ease;
        }
        .lp-nav-link:hover::after,
        .lp-nav-item.active > .lp-nav-link::after { width: 100%; }
        .lp-nav-link:hover { color: var(--lp-accent); }
        .lp-nav-item.active > .lp-nav-link { color: var(--lp-accent); }
        .lp-nav-caret { font-size: 11px; transition: transform .15s; }
        .lp-nav-item:hover .lp-nav-caret, .lp-nav-item.open .lp-nav-caret { transform: rotate(180deg); }
        .lp-nav-sub {
            position: absolute; top: 100%; left: 0; min-width: 190px;
            background: var(--lp-bg); border: 1px solid var(--lp-chip-border);
            box-shadow: 0 14px 34px rgba(0,0,0,.18); border-radius: 0 0 10px 10px;
            list-style: none; padding: 6px 0; z-index: 999;
            opacity: 0; visibility: hidden;
            transform: translateY(8px);
            transition: opacity .2s ease, transform .2s ease, visibility .2s;
            pointer-events: none;
        }
        .lp-nav-item:hover > .lp-nav-sub, .lp-nav-item.open > .lp-nav-sub {
            opacity: 1; visibility: visible; transform: translateY(0); pointer-events: auto;
        }
        .lp-nav-sub-link {
            display: block; padding: 9px 18px; font-size: 14px;
            color: var(--lp-text); text-decoration: none; white-space: nowrap;
        }
        .lp-nav-sub-link:hover { color: var(--lp-accent); }

        @media (max-width: 820px) {
            .lp-hero { grid-template-columns: 1fr; gap: 24px; }
            .lp-trust { grid-template-columns: repeat(2, 1fr); }
        }
    </style>
    @yield('theme_css')
</head>
<body>

<div class="lp-topbar">
    <div class="lp-marquee"><span><i class="fas fa-truck-fast"></i> ক্যাশ অন ডেলিভারি — সারা দেশে হোম ডেলিভারি &nbsp;•&nbsp; 100% অরিজিনাল প্রোডাক্ট &nbsp;•&nbsp; ৭ দিনের সহজ রিটার্ন &nbsp;•&nbsp; সাপোর্ট সকাল ৯টা–রাত ১০টা</span></div>
</div>

<header class="lp-header">
    <div class="lp-header__inner">
        <a class="lp-logo" href="{{ url('/') }}" aria-label="LibasBD Home">
            <img src="{{ asset('images/logos/logo.png') }}" alt="LibasBD" id="lpLogoImg">
        </a>

        @if(isset($navMenus) && $navMenus->count())
        <nav class="lp-nav" aria-label="প্রধান মেনু">
    <ul class="lp-nav-list">
        @php
            $lpNavActive = function ($menu) {
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
            <li class="lp-nav-item {{ $submenus->count() ? 'has-sub' : '' }} {{ $lpNavActive($menu) ? 'active' : '' }}">
                <a href="{{ $menu->getUrl() }}" class="lp-nav-link">
                    {{ $menu->title }}
                    @if($submenus->count())
                        <i class="fas fa-chevron-down lp-nav-caret"></i>
                    @endif
                </a>
                @if($submenus->count())
                    <ul class="lp-nav-sub">
                        @foreach($submenus as $sub)
                            <li><a href="{{ $sub->getUrl() }}" class="lp-nav-sub-link">{{ $sub->title }}</a></li>
                        @endforeach
                    </ul>
                @endif
            </li>
        @endforeach
        </nav>
        @endif

        <a href="#lp-order" class="lp-header-cta"><i class="fas fa-bolt"></i> অর্ডার করুন</a>
    </div>
</header>

<div class="lp-wrap">

    <section class="lp-hero" id="lp-order">
        <div class="lp-hero__media">
            <div class="lp-hero__img">
                @if($product->discount)
                    <span class="lp-badge">-{{ $product->discount }}% OFF</span>
                @endif
                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" id="lpMainImg">
                @if($product->youtube_embed_url)
                    <div class="lp-video-embed" id="lpVideoEmbed" style="display:none;">
                        <iframe id="lpVideoIframe" data-src="{{ $product->youtube_embed_url }}?autoplay=1&rel=0"
                                title="{{ $product->name }} — video" frameborder="0"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                allowfullscreen></iframe>
                    </div>
                @endif
            </div>

            @if(count($product->gallery_urls) > 0 || $product->youtube_embed_url)
            <div class="lp-thumbs">
                <button type="button" class="lp-thumb is-active" data-type="image" data-src="{{ $product->image_url }}" aria-label="Main image">
                    <img src="{{ $product->image_url }}" alt="" loading="lazy">
                </button>
                @foreach($product->gallery_urls as $galleryUrl)
                <button type="button" class="lp-thumb" data-type="image" data-src="{{ $galleryUrl }}" aria-label="Gallery image">
                    <img src="{{ $galleryUrl }}" alt="" loading="lazy">
                </button>
                @endforeach
                @if($product->youtube_embed_url)
                <button type="button" class="lp-thumb lp-thumb--video" data-type="video" aria-label="Play video">
                    <i class="fas fa-play"></i>
                </button>
                @endif
            </div>
            @endif
        </div>

        <div class="lp-hero__info">
            <span class="lp-cat">{{ $product->category_name ?: 'LibasBD' }}</span>
            <h1 class="lp-title">{{ $product->name }}</h1>

            <div class="lp-price">
                <span class="lp-price__now">৳{{ number_format($product->price) }}</span>
                @if($product->original_price && $product->original_price > $product->price)
                    <span class="lp-price__was">৳{{ number_format($product->original_price) }}</span>
                @endif
            </div>

            <ul class="lp-points">
                <li><i class="fas fa-circle-check"></i> ১০০% অরিজিনাল ও প্রিমিয়াম কোয়ালিটি</li>
                <li><i class="fas fa-circle-check"></i> ঢাকায় ১–৩ দিনে, সারা দেশে ৩–৭ দিনে ডেলিভারি</li>
                <li><i class="fas fa-circle-check"></i> ৭ দিনের সহজ রিটার্ন ও এক্সচেঞ্জ</li>
                <li><i class="fas fa-circle-check"></i> ক্যাশ অন ডেলিভারি, bKash, Nagad ও কার্ড পেমেন্ট</li>
            </ul>

            @if($product->sizes && $product->sizes->count())
            <span class="lp-opt-label">Size</span>
            <div class="lp-opts" id="lpSizes">
                @foreach($product->sizes as $s)
                    <button type="button" class="lp-chip" data-id="{{ $s->id }}">{{ $s->name }}</button>
                @endforeach
            </div>
            @endif

            @if($product->colors && $product->colors->count())
            <span class="lp-opt-label">Color</span>
            <div class="lp-opts" id="lpColors">
                @foreach($product->colors as $c)
                    <button type="button" class="lp-swatch" data-id="{{ $c->id }}" title="{{ $c->name }}" style="background: {{ $c->hex_code }}"></button>
                @endforeach
            </div>
            @endif

            <span class="lp-opt-label">Quantity</span>
            <div class="lp-qty">
                <button type="button" id="lpMinus"><i class="fas fa-minus"></i></button>
                <input type="number" id="lpQty" value="1" min="1" max="99">
                <button type="button" id="lpPlus"><i class="fas fa-plus"></i></button>
            </div>

            <p class="lp-err" id="lpErr"><i class="fas fa-exclamation-circle"></i> <span></span></p>

            <div class="lp-ctas">
                <button type="button" class="lp-btn lp-btn--primary" id="lpOrderNow">
                    <i class="fas fa-bolt"></i> এখনই অর্ডার করুন
                </button>
                <button type="button" class="lp-btn lp-btn--ghost" id="lpAddCart">
                    <i class="fas fa-shopping-bag"></i> Add to Cart
                </button>
            </div>
        </div>
    </section>

    <section class="lp-trust">
        <div class="lp-trust__item"><i class="fas fa-truck-fast"></i><b>দ্রুত ডেলিভারি</b><span>সারা দেশে</span></div>
        <div class="lp-trust__item"><i class="fas fa-money-bill-wave"></i><b>Cash on Delivery</b><span>ঘরে বসে পেমেন্ট</span></div>
        <div class="lp-trust__item"><i class="fas fa-shield-halved"></i><b>১০০% অথেনটিক</b><span>কোয়ালিটি গ্যারান্টি</span></div>
        <div class="lp-trust__item"><i class="fas fa-rotate-left"></i><b>৭ দিনে রিটার্ন</b><span>সহজ এক্সচেঞ্জ</span></div>
    </section>

    @if($product->description)
    <section class="lp-desc">
        <h2>প্রোডাক্ট ডিটেইলস</h2>
        <div>{!! nl2br(e($product->description)) !!}</div>
    </section>
    @endif

    <section class="lp-cta">
        <h3>স্টক সীমিত — আজই অর্ডার করুন!</h3>
        <p>ক্যাশ অন ডেলিভারিতে ঘরে বসে পেয়ে যান আপনার পছন্দের প্রোডাক্ট</p>
        <button type="button" class="lp-btn lp-btn--primary" id="lpOrderNow2">
            <i class="fas fa-bolt"></i> এখনই অর্ডার করুন
        </button>
    </section>

    <footer class="lp-footer">
        <a href="{{ url('/') }}"><i class="fas fa-store"></i> LibasBD</a> —
        House-3, Road-1, Block-A, Bochila City Developers Ltd., Bosila, Mahammadpur, Dhaka-1207 |
        <i class="fas fa-phone"></i> 01333257604
    </footer>
</div>

<div class="lp-toast" id="lpToast"><i class="fas fa-check-circle"></i><span></span></div>

<script>
(function () {
    var productId  = {{ $product->id }};
    var hasSizes   = {{ ($product->sizes && $product->sizes->count()) ? 'true' : 'false' }};
    var hasColors  = {{ ($product->colors && $product->colors->count()) ? 'true' : 'false' }};
    var sizeId = null, colorId = null;
    var qty = document.getElementById('lpQty');
    var err = document.getElementById('lpErr');
    var toast = document.getElementById('lpToast');

    function pick(rowId, cb) {
        var row = document.getElementById(rowId);
        if (!row) return;
        row.querySelectorAll('button').forEach(function (b) {
            b.addEventListener('click', function () {
                row.querySelectorAll('button').forEach(function (x) { x.classList.remove('sel'); });
                b.classList.add('sel');
                cb(b.dataset.id);
            });
        });
    }
    pick('lpSizes',  function (id) { sizeId = id; });
    pick('lpColors', function (id) { colorId = id; });

    document.getElementById('lpMinus').addEventListener('click', function () { qty.value = Math.max(1, (+qty.value || 1) - 1); });
    document.getElementById('lpPlus').addEventListener('click',  function () { qty.value = Math.min(99, (+qty.value || 1) + 1); });

    function showErr(msg) {
        err.querySelector('span').textContent = msg;
        err.style.display = 'flex';
        err.scrollIntoView({ behavior: 'smooth', block: 'center' });
        err.classList.add('lp-err--shake');
        setTimeout(function () { err.classList.remove('lp-err--shake'); }, 500);
    }

    function showToast(msg, type) {
        toast.querySelector('span').textContent = msg;
        toast.className = 'lp-toast show lp-toast--' + type;
        setTimeout(function () { toast.classList.remove('show'); }, 3000);
    }

    function submit(btn, goCheckout) {
        if (hasSizes && !sizeId)  { showErr('সাইজ সিলেক্ট করুন'); return; }
        if (hasColors && !colorId) { showErr('কালার সিলেক্ট করুন'); return; }
        err.style.display = 'none';

        btn.disabled = true;
        var orig = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing…';

        fetch((window.LIBAS_BASE || '') + '/cart/add/' + productId, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                quantity: Math.max(1, +qty.value || 1),
                size_id: sizeId,
                color_id: colorId,
                redirect_to_checkout: goCheckout ? 1 : undefined
            })
        })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            if (data && data.redirect) { window.location.href = data.redirect; return; }
            if (data && data.success) {
                showToast('কার্টে যোগ হয়েছে!', 'success');
                if (typeof fbq !== 'undefined' && data.pixelEvent) {
                    fbq('track', 'AddToCart', data.pixelEvent.data, {eventID: data.pixelEvent.event_id});
                }
                btn.innerHTML = '<i class="fas fa-check"></i> Added!';
                setTimeout(function () { btn.innerHTML = orig; btn.disabled = false; }, 2000);
            } else {
                showToast('ব্যর্থ হয়েছে, আবার চেষ্টা করুন', 'error');
                btn.innerHTML = orig; btn.disabled = false;
            }
        })
        .catch(function () {
            showToast('সমস্যা হয়েছে, আবার চেষ্টা করুন', 'error');
            btn.innerHTML = orig; btn.disabled = false;
        });
    }

    document.getElementById('lpOrderNow').addEventListener('click',  function () { submit(this, true); });
    document.getElementById('lpOrderNow2').addEventListener('click', function () { submit(this, true); });
    document.getElementById('lpAddCart').addEventListener('click',  function () { submit(this, false); });

    // ── Header background auto-adjusts to the logo's dominant color ──
    (function () {
        var img = document.getElementById('lpLogoImg');
        var header = document.querySelector('.lp-header');
        if (!img || !header) return;

        function tune() {
            try {
                var size = 48;
                var c = document.createElement('canvas');
                c.width = size; c.height = size;
                var ctx = c.getContext('2d');
                ctx.drawImage(img, 0, 0, size, size);
                var px = ctx.getImageData(0, 0, size, size).data;

                var r = 0, g = 0, b = 0, n = 0;
                for (var i = 0; i < px.length; i += 4) {
                    if (px[i + 3] > 60) { r += px[i]; g += px[i + 1]; b += px[i + 2]; n++; }
                }
                if (!n) return;
                r /= n; g /= n; b /= n;

                var lum = (0.2126 * r + 0.7152 * g + 0.0722 * b) / 255;

                if (lum > 0.5) {
                    // Light logo → dark header tinted in the logo's own hue
                    var d1 = [Math.round(r * .16), Math.round(g * .14), Math.round(b * .11)];
                    var d2 = [Math.round(r * .28), Math.round(g * .24), Math.round(b * .18)];
                    header.style.background = 'linear-gradient(135deg, rgb(' + d2.join(',') + '), rgb(' + d1.join(',') + '))';
                    header.dataset.bg = 'dark';
                } else {
                    // Dark logo → light header tinted in the logo's hue
                    var l1 = [Math.min(255, Math.round(r * .15 + 232)), Math.min(255, Math.round(g * .15 + 226)), Math.min(255, Math.round(b * .15 + 214))];
                    header.style.background = 'rgb(' + l1.join(',') + ')';
                    header.dataset.bg = 'light';
                }
            } catch (e) { /* keep default theme header color */ }
        }

        if (img.complete && img.naturalWidth) { tune(); }
        else { img.addEventListener('load', tune); }
    })();

    // ── Gallery thumbnails + YouTube video ──
    var lpMainImg = document.getElementById('lpMainImg');
    var lpVideoEmbed = document.getElementById('lpVideoEmbed');
    var lpVideoIframe = document.getElementById('lpVideoIframe');

    document.querySelectorAll('.lp-thumb').forEach(function (thumb) {
        thumb.addEventListener('click', function () {
            document.querySelectorAll('.lp-thumb').forEach(function (t) { t.classList.remove('is-active'); });
            this.classList.add('is-active');

            if (this.dataset.type === 'video' && lpVideoEmbed && lpVideoIframe) {
                if (!lpVideoIframe.src) lpVideoIframe.src = lpVideoIframe.dataset.src;
                lpVideoEmbed.style.display = 'block';
                if (lpMainImg) lpMainImg.style.visibility = 'hidden';
            } else {
                if (lpVideoEmbed && lpVideoIframe) {
                    lpVideoEmbed.style.display = 'none';
                    lpVideoIframe.src = '';
                }
                if (lpMainImg && this.dataset.src) {
                    lpMainImg.style.visibility = 'visible';
                    lpMainImg.src = this.dataset.src;
                }
            }
        });
    });
})();
</script>
</body>
</html>
