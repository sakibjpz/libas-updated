@extends('layouts.app')

@section('title', 'About Us - libasbd')
@section('description', 'Learn about LibasBD - your trusted online shopping destination in Bangladesh for premium burkha, 3 piece, cosmetics and more. Quality products, fast delivery, excellent service.')

@section('content')

<link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:ital,wght@0,300;0,400;0,500;1,300&display=swap" rel="stylesheet">

<div class="ab-root">

    <!-- Hero -->
    <section class="ab-hero">
        <div class="ab-hero__bg">
            <div class="ab-hero__orb ab-hero__orb--1"></div>
            <div class="ab-hero__orb ab-hero__orb--2"></div>
            <div class="ab-hero__grid"></div>
        </div>
        <div class="ab-hero__content">
            <div class="ab-hero__tag">
                <span class="ab-dot"></span> Since day one, for you
            </div>
            <h1 class="ab-hero__title">
                About <em>LibasBD</em>
            </h1>
            <p class="ab-hero__sub">Premium fashion, honest prices, and service that actually cares — delivered to your door across Bangladesh.</p>
        </div>
    </section>

    <!-- Story -->
    <section class="ab-story">
        <div class="ab-container">
            <div class="ab-story__grid">
                <div class="ab-story__media">
                    <img src="{{ asset('images/logos/logo.png') }}" alt="LibasBD" loading="lazy">
                </div>
                <div class="ab-story__text">
                    <span class="ab-kicker">Our Story</span>
                    <h2>Built on trust, styled for you</h2>
                    <p>LibasBD started with a simple idea: make premium-quality fashion and everyday essentials easy to buy online in Bangladesh — without compromising on authenticity or service.</p>
                    <p>From elegant burkha and abaya collections to 3-piece suits, cosmetics, and daily necessities, every product in our catalog is hand-picked for quality. We handle sourcing, packing, and delivery ourselves so what you see is exactly what you get.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Values -->
    <section class="ab-values">
        <div class="ab-container">
            <span class="ab-kicker ab-kicker--center">Why shop with us</span>
            <h2 class="ab-section-title">What we stand for</h2>
            <div class="ab-values__grid">
                <div class="ab-value-card" data-index="01">
                    <div class="ab-value-card__icon"><i class="fas fa-gem"></i></div>
                    <h3>Premium Quality</h3>
                    <p>Every item is checked for fabric, finish, and authenticity before it ships.</p>
                </div>
                <div class="ab-value-card" data-index="02">
                    <div class="ab-value-card__icon"><i class="fas fa-truck-fast"></i></div>
                    <h3>Fast Delivery</h3>
                    <p>Nationwide delivery via trusted couriers, with tracking on every order.</p>
                </div>
                <div class="ab-value-card" data-index="03">
                    <div class="ab-value-card__icon"><i class="fas fa-rotate-left"></i></div>
                    <h3>Easy Returns</h3>
                    <p>Something not right? Our refund policy keeps returns simple and fair.</p>
                </div>
                <div class="ab-value-card" data-index="04">
                    <div class="ab-value-card__icon"><i class="fas fa-headset"></i></div>
                    <h3>Real Support</h3>
                    <p>Talk to a human, 9AM–10PM every day — by phone, email, or live chat.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="ab-cta">
        <div class="ab-container">
            <div class="ab-cta__box">
                <h2>Ready to shop?</h2>
                <p>Browse the collection and find something you'll love.</p>
                <div class="ab-cta__actions">
                    <a href="{{ url('/products') }}" class="ab-btn ab-btn--gold">Shop Now <i class="fas fa-arrow-right"></i></a>
                    <a href="{{ url('/contact') }}" class="ab-btn ab-btn--ghost">Contact Us</a>
                </div>
            </div>
        </div>
    </section>

</div>

<style>
.ab-root {
    --ink: #1d1912;
    --ink-muted: #6b6250;
    --ink-faint: #a39a82;
    --surface: #ffffff;
    --surface-2: #faf6ec;
    --accent: #c9a24b;
    --accent-2: #e5c877;
    --accent-warm: #9c7c33;
    --accent-glow: rgba(201,162,75,0.18);
    --radius-md: 18px;
    --shadow-sm: 0 2px 12px rgba(29,25,18,0.07);
    --shadow-md: 0 8px 32px rgba(29,25,18,0.10);
    font-family: 'DM Sans', sans-serif;
    color: var(--ink);
    background: var(--surface);
    overflow-x: hidden;
}

.ab-container {
    max-width: 1160px;
    margin: 0 auto;
    padding: 0 28px;
}

/* Hero */
.ab-hero {
    min-height: 60vh;
    background: var(--ink);
    position: relative;
    display: flex;
    align-items: center;
    overflow: hidden;
}
.ab-hero__bg { position: absolute; inset: 0; pointer-events: none; }
.ab-hero__grid {
    position: absolute; inset: 0;
    background-image:
        linear-gradient(rgba(255,255,255,0.04) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255,255,255,0.04) 1px, transparent 1px);
    background-size: 60px 60px;
}
.ab-hero__orb {
    position: absolute;
    border-radius: 50%;
    filter: blur(80px);
}
.ab-hero__orb--1 {
    width: 500px; height: 500px;
    background: radial-gradient(circle, rgba(201,162,75,0.35) 0%, transparent 70%);
    top: -100px; left: -80px;
}
.ab-hero__orb--2 {
    width: 400px; height: 400px;
    background: radial-gradient(circle, rgba(156,124,51,0.25) 0%, transparent 70%);
    bottom: -50px; right: 100px;
}
.ab-hero__content {
    position: relative;
    max-width: 1160px;
    margin: 0 auto;
    padding: 90px 28px;
    width: 100%;
}
.ab-hero__tag {
    display: inline-flex; align-items: center; gap: 10px;
    background: rgba(255,255,255,0.08);
    border: 1px solid rgba(255,255,255,0.14);
    color: rgba(255,255,255,0.85);
    font-size: 13px; letter-spacing: 0.4px;
    padding: 8px 16px; border-radius: 100px;
    margin-bottom: 28px;
}
.ab-dot { width: 8px; height: 8px; border-radius: 50%; background: var(--accent-2); }
.ab-hero__title {
    font-family: 'Syne', sans-serif;
    font-size: clamp(42px, 7vw, 84px);
    font-weight: 800;
    color: #fff;
    line-height: 1.02;
    margin: 0 0 20px;
}
.ab-hero__title em {
    font-style: normal;
    background: linear-gradient(135deg, var(--accent-2), var(--accent-warm));
    -webkit-background-clip: text;
    background-clip: text;
    -webkit-text-fill-color: transparent;
}
.ab-hero__sub {
    color: rgba(255,255,255,0.7);
    font-size: clamp(15px, 1.6vw, 19px);
    font-weight: 300;
    max-width: 560px;
    margin: 0;
}

/* Story */
.ab-story { padding: 90px 0; background: var(--surface); }
.ab-story__grid {
    display: grid;
    grid-template-columns: 1fr 1.2fr;
    gap: 60px;
    align-items: center;
}
.ab-story__media {
    background: var(--surface-2);
    border: 1px solid var(--surface-2);
    border-radius: var(--radius-md);
    padding: 50px;
    display: flex; align-items: center; justify-content: center;
    box-shadow: var(--shadow-sm);
}
.ab-story__media img { max-width: 100%; height: auto; display: block; }
.ab-kicker {
    display: inline-block;
    font-size: 12px; font-weight: 600;
    letter-spacing: 2px; text-transform: uppercase;
    color: var(--accent-warm);
    margin-bottom: 14px;
}
.ab-kicker--center { display: block; text-align: center; }
.ab-story__text h2, .ab-section-title {
    font-family: 'Syne', sans-serif;
    font-size: clamp(28px, 3.4vw, 42px);
    font-weight: 700;
    margin: 0 0 18px;
    line-height: 1.15;
}
.ab-story__text p { color: var(--ink-muted); line-height: 1.75; margin: 0 0 14px; }

/* Values */
.ab-values { padding: 80px 0; background: var(--surface-2); }
.ab-section-title { text-align: center; margin-bottom: 44px; }
.ab-values__grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
}
.ab-value-card {
    background: var(--surface);
    border: 1px solid var(--surface-2);
    border-radius: var(--radius-md);
    padding: 30px 24px;
    position: relative;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.ab-value-card:hover { transform: translateY(-4px); box-shadow: var(--shadow-md); }
.ab-value-card::before {
    content: attr(data-index);
    position: absolute; top: 18px; right: 22px;
    font-family: 'Syne', sans-serif;
    font-size: 13px; font-weight: 700;
    color: var(--ink-faint);
}
.ab-value-card__icon {
    width: 52px; height: 52px;
    border-radius: 14px;
    background: var(--accent-glow);
    color: var(--accent-warm);
    display: flex; align-items: center; justify-content: center;
    font-size: 20px;
    margin-bottom: 18px;
}
.ab-value-card h3 { font-family: 'Syne', sans-serif; font-size: 17px; margin: 0 0 8px; }
.ab-value-card p { color: var(--ink-muted); font-size: 14px; line-height: 1.6; margin: 0; }

/* CTA */
.ab-cta { padding: 90px 0; background: var(--surface); }
.ab-cta__box {
    background: var(--ink);
    border-radius: var(--radius-md);
    padding: 60px 40px;
    text-align: center;
    position: relative;
    overflow: hidden;
}
.ab-cta__box::before {
    content: '';
    position: absolute; inset: 0;
    background: radial-gradient(circle at 30% 20%, rgba(201,162,75,0.25), transparent 60%);
    pointer-events: none;
}
.ab-cta__box h2 {
    font-family: 'Syne', sans-serif;
    color: #fff;
    font-size: clamp(26px, 3vw, 38px);
    margin: 0 0 10px;
    position: relative;
}
.ab-cta__box p { color: rgba(255,255,255,0.65); margin: 0 0 28px; position: relative; }
.ab-cta__actions { display: flex; gap: 14px; justify-content: center; flex-wrap: wrap; position: relative; }
.ab-btn {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 14px 28px;
    border-radius: 100px;
    font-weight: 500; font-size: 15px;
    text-decoration: none;
    transition: transform 0.25s ease, box-shadow 0.25s ease;
}
.ab-btn:hover { transform: translateY(-2px); }
.ab-btn--gold {
    background: linear-gradient(135deg, var(--accent-2), var(--accent-warm));
    color: var(--ink);
}
.ab-btn--gold:hover { box-shadow: 0 8px 24px rgba(201,162,75,0.4); color: var(--ink); }
.ab-btn--ghost {
    border: 1px solid rgba(255,255,255,0.3);
    color: #fff;
}
.ab-btn--ghost:hover { border-color: var(--accent-2); color: var(--accent-2); }

@media (max-width: 900px) {
    .ab-story__grid { grid-template-columns: 1fr; gap: 32px; }
    .ab-values__grid { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 540px) {
    .ab-values__grid { grid-template-columns: 1fr; }
    .ab-cta__box { padding: 44px 24px; }
}
</style>

@endsection
