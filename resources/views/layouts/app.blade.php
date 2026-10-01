<!DOCTYPE html>
<html lang="bn" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'LibasBD - Premium Women\'s Fashion Online Shopping Bangladesh')</title>
    <meta name="description" content="@yield('description', 'LibasBD - আপনার বিশ্বস্ত অনলাইন ফ্যাশন স্টোর। প্রিমিয়াম বোরকা, আবায়া, থ্রি পিস, কসমেটিকস ও আরও অনেক কিছু সেরা দামে। সারা দেশে হোম ডেলিভারি ও ক্যাশ অন ডেলিভারি।')">
    <meta name="keywords" content="@yield('keywords', 'Premium Burkha Bangladesh, Buy Burkha Online BD, Abaya Online Bangladesh, Premium Abaya, Luxury Burkha, Dubai Abaya, Saudi Burkha, Jilbab Bangladesh, Niqab Online, Hijab Online BD, Premium 3 Piece Bangladesh, Pakistani 3 Piece, Unstitched Three Piece, Salwar Kameez Bangladesh, Kameez Online BD, Original Cosmetics Bangladesh, Authentic Cosmetics, Skin Care Bangladesh, Women\'s Fashion Bangladesh, Online Fashion Store Bangladesh, Modest Fashion Bangladesh, Muslim Women Clothing, Islamic Clothing BD, Eid Collection Bangladesh, Wedding Abaya, Party Wear Burkha, Men Fashion Bangladesh, Men Clothing Online BD, Kids Fashion Bangladesh, Kids Wear Online BD, Boys Dress Online, Girls Dress Online BD, Sherwani Premium Bangladesh, Sherwani Online BD, Panjabi Online Bangladesh, Family Fashion Store BD, বোরকা অনলাইন, আবায়া বাংলাদেশ, থ্রি পিস অনলাইন, কসমেটিকস অনলাইন, শেরওয়ানি, পাঞ্জাবি অনলাইন, Cash on Delivery Bangladesh, LibasBD, libasbd.com')">
    <meta name="robots" content="@yield('robots', 'index, follow, max-image-preview:large')">
    <meta name="theme-color" content="#c9a24b">
    <link rel="canonical" href="@yield('canonical', rtrim(config('app.url'), '/') . '/' . ltrim(request()->path(), '/'))">

    <!-- Open Graph -->
    <meta property="og:site_name" content="LibasBD">
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:title" content="@yield('title', 'LibasBD - Premium Women\'s Fashion Online Shopping Bangladesh')">
    <meta property="og:description" content="@yield('description', 'LibasBD - আপনার বিশ্বস্ত অনলাইন ফ্যাশন স্টোর। প্রিমিয়াম বোরকা, আবায়া, থ্রি পিস, কসমেটিকস ও আরও অনেক কিছু সেরা দামে। সারা দেশে হোম ডেলিভারি ও ক্যাশ অন ডেলিভারি।')">
    <meta property="og:url" content="@yield('canonical', rtrim(config('app.url'), '/') . '/' . ltrim(request()->path(), '/'))">
    <meta property="og:image" content="@yield('og_image', asset('images/logos/logo.png'))">
    <meta property="og:locale" content="bn_BD">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('title', 'LibasBD - Premium Women\'s Fashion Online Shopping Bangladesh')">
    <meta name="twitter:description" content="@yield('description', 'LibasBD - আপনার বিশ্বস্ত অনলাইন ফ্যাশন স্টোর। প্রিমিয়াম বোরকা, আবায়া, থ্রি পিস, কসমেটিকস ও আরও অনেক কিছু সেরা দামে। সারা দেশে হোম ডেলিভারি ও ক্যাশ অন ডেলিভারি।')">
    <meta name="twitter:image" content="@yield('og_image', asset('images/logos/logo.png'))">

    @php
        $seoOrganization = [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => 'LibasBD',
            'url' => url('/'),
            'logo' => asset('images/logos/logo.png'),
            'sameAs' => ['https://www.facebook.com/libasbd0'],
        ];
        $seoWebsite = [
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            'name' => 'LibasBD',
            'url' => url('/'),
            'potentialAction' => [
                '@type' => 'SearchAction',
                'target' => ['@type' => 'EntryPoint', 'urlTemplate' => url('/products') . '?search={search_term_string}'],
                'query-input' => 'required name=search_term_string',
            ],
        ];
        $seoStore = [
            '@context' => 'https://schema.org',
            '@type' => 'Store',
            'name' => 'LibasBD',
            'url' => url('/'),
            'image' => asset('images/logos/logo.png'),
            'telephone' => '+8801333257604',
            'email' => 'support@libasbd.com',
            'address' => [
                '@type' => 'PostalAddress',
                'streetAddress' => 'House-3, Road-1, Block-A, Bochila City Developers Ltd., Bosila, Mahammadpur',
                'addressLocality' => 'Dhaka',
                'postalCode' => '1207',
                'addressCountry' => 'BD',
            ],
            'openingHours' => 'Mo-Su 09:00-22:00',
            'priceRange' => '৳৳',
        ];
    @endphp
    <script type="application/ld+json">{!! json_encode($seoOrganization, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
    <script type="application/ld+json">{!! json_encode($seoWebsite, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
    <script type="application/ld+json">{!! json_encode($seoStore, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
    @stack('schema')

    <!-- DNS Prefetch -->
    <link rel="dns-prefetch" href="https://fonts.googleapis.com">
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link rel="dns-prefetch" href="https://cdnjs.cloudflare.com">

    <!-- Preconnect for fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <!-- SolaimanLipi Bangla font (self-hosted) -->
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
        @font-face {
            font-family: 'SolaimanLipi';
            src: url('{{ asset('fonts/solaimanlipi-thin-v1.0.woff2') }}') format('woff2');
            font-weight: 100 300; font-style: normal; font-display: swap;
        }
        html body { font-family: 'SolaimanLipi', 'Noto Sans Bengali', 'Segoe UI', sans-serif; }
    </style>

    <!-- Fonts with display swap -->
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" media="print" onload="this.media='all'">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/logos/favicon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logos/favicon.png') }}">
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
<!-- Google tag (gtag.js) Start -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-DXPK4322G7"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-DXPK4322G7');
</script>
<!-- Google tag (gtag.js) End -->
    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Chat buttons fixed positioning - must load before other CSS */
        .chat-buttons-container {
            position: fixed !important;
            bottom: 20px !important;
            right: 20px !important;
            left: auto !important;
            z-index: 999999 !important;
            pointer-events: auto !important;
        }
        .whatsapp-float {
            background-color: #25d366 !important;
            width: 60px !important;
            height: 60px !important;
            border-radius: 50% !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            box-shadow: 0 4px 10px rgba(0,0,0,0.2) !important;
            text-decoration: none !important;
            transition: all 0.3s ease !important;
        }
        .whatsapp-float i {
            color: white !important;
            font-size: 35px !important;
        }
        .whatsapp-float:hover {
            background-color: #128C7E !important;
            transform: scale(1.1) !important;
        }
    </style>

    <!-- Google Analytics -->
    @if(env('GOOGLE_ANALYTICS_ID'))
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ env('GOOGLE_ANALYTICS_ID') }}"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', '{{ env('GOOGLE_ANALYTICS_ID') }}');
    </script>
    @endif
    <!-- End Google Analytics -->

    <!-- Facebook Pixel Code -->
    @if(config('services.facebook.pixel_id'))
    <script>
        !function(f,b,e,v,n,t,s)
        {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
        n.callMethod.apply(n,arguments):n.queue.push(arguments)};
        if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
        n.queue=[];t=b.createElement(e);t.async=!0;
        t.src=v;s=b.getElementsByTagName(e)[0];
        s.parentNode.insertBefore(t,s)}(window, document,'script',
        'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '{{ config('services.facebook.pixel_id') }}');
        fbq('track', 'PageView');
    </script>
    <noscript><img height="1" width="1" style="display:none"
        src="https://www.facebook.com/tr?id={{ config('services.facebook.pixel_id') }}&ev=PageView&noscript=1"
        alt="Facebook Pixel"/>
    </noscript>
    @endif
    <!-- End Facebook Pixel Code -->

    @stack('styles')
</head>
<body>
    <!-- Container for WhatsApp button -->
    <div class="chat-buttons-container" style="position: fixed !important; bottom: 20px !important; right: 20px !important; left: auto !important; z-index: 999999 !important; pointer-events: auto !important;">
        <!-- WhatsApp Button -->
        <a href="https://wa.me/{{ \App\Models\SiteSetting::query()->value('whatsapp_phone') ?? '8801333257604' }}"
           class="whatsapp-float"
           target="_blank"
           rel="noopener noreferrer">
            <i class="fab fa-whatsapp"></i>
        </a>
    </div>

    <div id="app">
        <!-- Header -->
        @include('partials.header')

        <main>
            @yield('content')
        </main>

        <!-- Footer -->
        @include('partials.Footer')
    </div>

<style>
html {
    height: 100%;
    position: relative;
}

body {
    min-height: 100%;
    opacity: 0;
    animation: fadeIn 0.5s ease-in-out forwards;
    position: relative;
}

@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

main {
    animation: slideIn 0.6s ease-out;
}

@keyframes slideIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}
</style>

    @include('partials.cart-options-modal')

    @stack('scripts')
</body>
</html>
