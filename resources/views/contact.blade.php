@extends('layouts.app')

@section('title', 'Contact Us - libasbd')
@section('description', 'Contact LibasBD - House-3, Road-1, Block-A, Bochila City Developers Ltd., Bosila, Mahammadpur, Dhaka-1207. Call 01333257604 (9AM-10PM everyday).')

@section('content')

<link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:ital,wght@0,300;0,400;0,500;1,300&display=swap" rel="stylesheet">

<div class="cp-root">

    <!-- Hero -->
    <section class="cp-hero">
        <div class="cp-hero__bg">
            <div class="cp-hero__orb cp-hero__orb--1"></div>
            <div class="cp-hero__orb cp-hero__orb--2"></div>
            <div class="cp-hero__orb cp-hero__orb--3"></div>
            <div class="cp-hero__grid"></div>
        </div>
        <div class="cp-hero__content">
            <div class="cp-hero__tag">
                <span class="cp-dot"></span> We're available now
            </div>
            <h1 class="cp-hero__title">
                Let's <em>Talk</em>
            </h1>
            <p class="cp-hero__sub">Have a question, need support, or just want to say hello? We'd love to hear from you.</p>
            <div class="cp-hero__scroll">
                <span>Scroll to explore</span>
                <div class="cp-hero__scroll-line"></div>
            </div>
        </div>
        <div class="cp-hero__badge">
            <svg viewBox="0 0 100 100" class="cp-badge-ring"><path id="curve" d="M 50,50 m -37,0 a 37,37 0 1,1 74,0 a 37,37 0 1,1 -74,0"/><text><textPath href="#curve">LIBASBD </textPath></text></svg>
            <div class="cp-badge-icon"><i class="fas fa-paper-plane"></i></div>
        </div>
    </section>

    <!-- Info Cards -->
    <section class="cp-info">
        <div class="cp-container">
            <div class="cp-info__grid">
                <div class="cp-info-card" data-index="01">
                    <div class="cp-info-card__num">01</div>
                    <div class="cp-info-card__icon"><i class="fas fa-map-marker-alt"></i></div>
                    <h3>Visit Us</h3>
                    <p>House-3, Road-1, Block-A,<br>Bochila City Developers Ltd.,<br>Bosila, Mahammadpur, Dhaka-1207</p>
                    <a href="https://maps.app.goo.gl/fhgtRAKdmAYhumqf6" target="_blank" class="cp-info-card__link">
                        Get Directions <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
                <div class="cp-info-card cp-info-card--accent" data-index="02">
                    <div class="cp-info-card__num">02</div>
                    <div class="cp-info-card__icon"><i class="fas fa-phone-alt"></i></div>
                    <h3>Call Us</h3>
                    <p>01333257604</p>
                    <a href="tel:+8801333257604" class="cp-info-card__link">
                        Call Now <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
                <div class="cp-info-card" data-index="03">
                    <div class="cp-info-card__num">03</div>
                    <div class="cp-info-card__icon"><i class="fas fa-envelope"></i></div>
                    <h3>Email Us</h3>
                    <p>support@libasbd.com</p>
                    <a href="mailto:support@libasbd.com" class="cp-info-card__link">
                        Send Email <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <section class="cp-main">
        <div class="cp-container">
            <div class="cp-main__grid">

                <!-- Form Side -->
                <div class="cp-form-side">
                    <div class="cp-form-header">
                        <span class="cp-eyebrow">Send a Message</span>
                        <h2>We read every <br><em>single message</em></h2>
                    </div>
                    <form class="cp-form" id="contactForm">
                        @csrf
                        <div class="cp-form__row">
                            <div class="cp-field">
                                <label for="name">Full Name <span>*</span></label>
                                <input type="text" id="name" name="name" required placeholder="e.g. Rahim Uddin">
                            </div>
                            <div class="cp-field">
                                <label for="email">Email Address <span>*</span></label>
                                <input type="email" id="email" name="email" required placeholder="you@example.com">
                            </div>
                        </div>
                        <div class="cp-form__row">
                            <div class="cp-field">
                                <label for="phone">Phone Number</label>
                                <input type="tel" id="phone" name="phone" placeholder="+880 1XXX-XXXXXX">
                            </div>
                            <div class="cp-field">
                                <label for="subject">Subject <span>*</span></label>
                                <input type="text" id="subject" name="subject" required placeholder="How can we help?">
                            </div>
                        </div>
                        <div class="cp-field cp-field--full">
                            <label for="message">Your Message <span>*</span></label>
                            <textarea id="message" name="message" rows="5" required placeholder="Tell us everything..."></textarea>
                        </div>
                        <button type="submit" class="cp-submit">
                            <span class="cp-submit__text">Send Message</span>
                            <span class="cp-submit__icon"><i class="fas fa-paper-plane"></i></span>
                        </button>
                    </form>
                </div>

                <!-- Info Side -->
                <div class="cp-side">

                    <!-- Map -->
                    <div class="cp-side-block">
                        <div class="cp-side-block__label">Our Location</div>
                        <div class="cp-map">
                            <iframe
                                src="https://maps.google.com/maps?q=23.7447517,90.3470668&z=17&output=embed"
                                width="100%" height="220" style="border:0;" allowfullscreen="" loading="lazy">
                            </iframe>
                        </div>
                    </div>

                    <!-- Hours -->
                    <div class="cp-side-block">
                        <div class="cp-side-block__label">Business Hours</div>
                        <ul class="cp-hours">
                            <li>
                                <span class="cp-hours__day">Every Day</span>
                                <span class="cp-hours__line"></span>
                                <span class="cp-hours__time">9 AM – 10 PM</span>
                            </li>
                            <li class="cp-hours__emergency">
                                <i class="fas fa-circle-notch fa-spin"></i>
                                <span>Emergency Support — 24/7</span>
                            </li>
                        </ul>
                    </div>

                    <!-- Social -->
                    <div class="cp-side-block">
                        <div class="cp-side-block__label">Follow Us</div>
                        <div class="cp-socials">
                            <a href="https://www.facebook.com/libasbd0" class="cp-social cp-social--fb" target="_blank"><i class="fab fa-facebook-f"></i></a>
                            <a href="#" class="cp-social cp-social--tw" target="_blank"><i class="fab fa-twitter"></i></a>
                            <a href="#" class="cp-social cp-social--ig" target="_blank"><i class="fab fa-instagram"></i></a>
                            <a href="#" class="cp-social cp-social--li" target="_blank"><i class="fab fa-linkedin-in"></i></a>
                            <a href="#" class="cp-social cp-social--wa" target="_blank"><i class="fab fa-whatsapp"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ -->
    <section class="cp-faq">
        <div class="cp-container">
            <div class="cp-faq__header">
                <span class="cp-eyebrow">FAQ</span>
                <h2>Common <em>Questions</em></h2>
            </div>
            <div class="cp-faq__grid">
                <div class="cp-faq-item">
                    <div class="cp-faq-item__icon"><i class="fas fa-shipping-fast"></i></div>
                    <div class="cp-faq-item__body">
                        <h4>What are your delivery times?</h4>
                        <p>1–3 business days in Dhaka, 3–7 days outside Dhaka. Same-day delivery available for urgent orders within Dhaka.</p>
                    </div>
                </div>
                <div class="cp-faq-item">
                    <div class="cp-faq-item__icon"><i class="fas fa-undo-alt"></i></div>
                    <div class="cp-faq-item__body">
                        <h4>What is your return policy?</h4>
                        <p>Return products within 7 days of delivery if unused and in original packaging. Full refund or exchange guaranteed.</p>
                    </div>
                </div>
                <div class="cp-faq-item">
                    <div class="cp-faq-item__icon"><i class="fas fa-credit-card"></i></div>
                    <div class="cp-faq-item__body">
                        <h4>What payment methods do you accept?</h4>
                        <p>Cash on delivery, bKash, Nagad, Rocket, credit/debit cards, and bank transfers. All transactions are secure.</p>
                    </div>
                </div>
                <div class="cp-faq-item">
                    <div class="cp-faq-item__icon"><i class="fas fa-search"></i></div>
                    <div class="cp-faq-item__body">
                        <h4>How can I track my order?</h4>
                        <p>You'll receive a tracking link via SMS and email once shipped. Also track from your account dashboard anytime.</p>
                    </div>
                </div>
            </div>
            <div class="cp-faq__footer">
                <a href="#" class="cp-btn-outline">View All FAQs <i class="fas fa-arrow-right"></i></a>
            </div>
        </div>
    </section>

</div>

<style>
/* =========================================
   ROOT & TOKENS
========================================= */
.cp-root {
    --ink: #1d1912;
    --ink-muted: #6b6250;
    --ink-faint: #a39a82;
    --surface: #ffffff;
    --surface-2: #faf6ec;
    --surface-3: #f3e8cd;
    --accent: #c9a24b;
    --accent-2: #e5c877;
    --accent-warm: #9c7c33;
    --accent-glow: rgba(201,162,75,0.18);
    --radius-sm: 10px;
    --radius-md: 18px;
    --radius-lg: 28px;
    --shadow-sm: 0 2px 12px rgba(13,13,18,0.07);
    --shadow-md: 0 8px 32px rgba(13,13,18,0.10);
    --shadow-lg: 0 20px 60px rgba(13,13,18,0.12);
    font-family: 'DM Sans', sans-serif;
    color: var(--ink);
    background: var(--surface);
    overflow-x: hidden;
}

.cp-container {
    max-width: 1160px;
    margin: 0 auto;
    padding: 0 28px;
}

/* =========================================
   HERO
========================================= */
.cp-hero {
    min-height: 88vh;
    background: var(--ink);
    position: relative;
    display: flex;
    align-items: center;
    overflow: hidden;
}

.cp-hero__bg {
    position: absolute;
    inset: 0;
    pointer-events: none;
}

.cp-hero__grid {
    position: absolute;
    inset: 0;
    background-image:
        linear-gradient(rgba(255,255,255,0.04) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255,255,255,0.04) 1px, transparent 1px);
    background-size: 60px 60px;
}

.cp-hero__orb {
    position: absolute;
    border-radius: 50%;
    filter: blur(80px);
    animation: orbFloat 8s ease-in-out infinite;
}

.cp-hero__orb--1 {
    width: 500px; height: 500px;
    background: radial-gradient(circle, rgba(201,162,75,0.35) 0%, transparent 70%);
    top: -100px; left: -80px;
}

.cp-hero__orb--2 {
    width: 400px; height: 400px;
    background: radial-gradient(circle, rgba(156,124,51,0.25) 0%, transparent 70%);
    bottom: -50px; right: 100px;
    animation-delay: -3s;
}

.cp-hero__orb--3 {
    width: 300px; height: 300px;
    background: radial-gradient(circle, rgba(229,200,119,0.25) 0%, transparent 70%);
    top: 40%; right: 20%;
    animation-delay: -5s;
}

@keyframes orbFloat {
    0%, 100% { transform: translate(0, 0) scale(1); }
    33% { transform: translate(20px, -30px) scale(1.05); }
    66% { transform: translate(-15px, 20px) scale(0.97); }
}

.cp-hero__content {
    position: relative;
    z-index: 2;
    width: 100%;
    max-width: 1160px;
    margin: 0 auto;
    padding: 100px 28px 120px;
}

.cp-hero__tag {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    background: rgba(255,255,255,0.08);
    border: 1px solid rgba(255,255,255,0.12);
    color: rgba(255,255,255,0.85);
    font-size: 0.85rem;
    font-weight: 500;
    letter-spacing: 0.06em;
    padding: 8px 18px;
    border-radius: 100px;
    margin-bottom: 36px;
    backdrop-filter: blur(10px);
    animation: fadeUp 0.7s ease both;
}

.cp-dot {
    width: 8px; height: 8px;
    background: #4ade80;
    border-radius: 50%;
    box-shadow: 0 0 10px #4ade80;
    animation: pulse 2s ease infinite;
}

@keyframes pulse {
    0%, 100% { opacity: 1; transform: scale(1); }
    50% { opacity: 0.6; transform: scale(0.85); }
}

.cp-hero__title {
    font-family: 'Syne', sans-serif;
    font-size: clamp(4rem, 10vw, 8.5rem);
    font-weight: 800;
    color: white;
    line-height: 0.95;
    margin: 0 0 30px;
    animation: fadeUp 0.7s 0.1s ease both;
}

.cp-hero__title em {
    font-style: italic;
    font-weight: 400;
    background: linear-gradient(135deg, #e5c877, #9c7c33);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.cp-hero__sub {
    font-size: 1.2rem;
    color: rgba(255,255,255,0.6);
    max-width: 480px;
    line-height: 1.7;
    margin: 0 0 60px;
    font-weight: 300;
    animation: fadeUp 0.7s 0.2s ease both;
}

.cp-hero__scroll {
    display: flex;
    align-items: center;
    gap: 16px;
    color: rgba(255,255,255,0.4);
    font-size: 0.8rem;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    animation: fadeUp 0.7s 0.3s ease both;
}

.cp-hero__scroll-line {
    width: 50px;
    height: 1px;
    background: rgba(255,255,255,0.2);
    position: relative;
    overflow: hidden;
}

.cp-hero__scroll-line::after {
    content: '';
    position: absolute;
    left: -100%;
    top: 0;
    width: 100%;
    height: 100%;
    background: rgba(255,255,255,0.7);
    animation: scanLine 2s ease infinite;
}

@keyframes scanLine {
    to { left: 100%; }
}

@keyframes fadeUp {
    from { opacity: 0; transform: translateY(24px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Badge */
.cp-hero__badge {
    position: absolute;
    right: 80px;
    bottom: 80px;
    width: 140px; height: 140px;
    z-index: 2;
    animation: badgeSpin 20s linear infinite;
}

@keyframes badgeSpin {
    to { transform: rotate(360deg); }
}

.cp-badge-ring {
    width: 100%; height: 100%;
    fill: none;
}

.cp-badge-ring text {
    font-family: 'DM Sans', sans-serif;
    font-size: 13px;
    fill: rgba(255,255,255,0.4);
    letter-spacing: 2px;
}

.cp-badge-icon {
    position: absolute;
    inset: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.8rem;
    color: rgba(255,255,255,0.5);
    animation: badgeSpin 20s linear infinite reverse;
}

/* =========================================
   INFO CARDS
========================================= */
.cp-info {
    padding: 80px 0;
    background: var(--surface);
}

.cp-info__grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 24px;
}

.cp-info-card {
    background: var(--surface-2);
    border-radius: var(--radius-lg);
    padding: 40px 32px;
    position: relative;
    overflow: hidden;
    transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
    border: 1px solid var(--surface-3);
}

.cp-info-card--accent {
    background: var(--accent);
    border-color: var(--accent);
    color: white;
}

.cp-info-card--accent h3,
.cp-info-card--accent p,
.cp-info-card--accent .cp-info-card__num {
    color: white !important;
}

.cp-info-card:hover {
    transform: translateY(-8px) scale(1.01);
    box-shadow: var(--shadow-lg);
}

.cp-info-card__num {
    font-family: 'Syne', sans-serif;
    font-size: 0.75rem;
    font-weight: 700;
    letter-spacing: 0.15em;
    color: var(--ink-faint);
    margin-bottom: 24px;
}

.cp-info-card__icon {
    width: 56px; height: 56px;
    background: rgba(255,255,255,0.15);
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.4rem;
    margin-bottom: 20px;
    transition: transform 0.3s;
}

.cp-info-card:not(.cp-info-card--accent) .cp-info-card__icon {
    background: var(--accent-glow);
    color: var(--accent);
}

.cp-info-card--accent .cp-info-card__icon {
    background: rgba(255,255,255,0.2);
    color: white;
}

.cp-info-card:hover .cp-info-card__icon {
    transform: rotate(-5deg) scale(1.1);
}

.cp-info-card h3 {
    font-family: 'Syne', sans-serif;
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--ink);
    margin: 0 0 12px;
}

.cp-info-card p {
    color: var(--ink-muted);
    font-size: 0.95rem;
    line-height: 1.7;
    margin: 0 0 24px;
}

.cp-info-card__link {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-size: 0.88rem;
    font-weight: 600;
    text-decoration: none;
    color: var(--accent);
    letter-spacing: 0.03em;
    transition: gap 0.2s;
}

.cp-info-card--accent .cp-info-card__link {
    color: rgba(255,255,255,0.85);
}

.cp-info-card__link:hover {
    gap: 12px;
}

/* =========================================
   MAIN SECTION
========================================= */
.cp-main {
    padding: 0 0 100px;
    background: var(--surface);
}

.cp-main__grid {
    display: grid;
    grid-template-columns: 1fr 400px;
    gap: 48px;
    align-items: start;
}

/* Form */
.cp-form-header {
    margin-bottom: 40px;
}

.cp-eyebrow {
    display: inline-block;
    font-size: 0.78rem;
    font-weight: 600;
    letter-spacing: 0.18em;
    text-transform: uppercase;
    color: var(--accent);
    background: var(--accent-glow);
    padding: 5px 14px;
    border-radius: 100px;
    margin-bottom: 16px;
}

.cp-form-header h2 {
    font-family: 'Syne', sans-serif;
    font-size: 2.6rem;
    font-weight: 800;
    color: var(--ink);
    line-height: 1.15;
    margin: 0;
}

.cp-form-header h2 em {
    font-style: italic;
    font-weight: 600;
    color: var(--accent);
}

.cp-form {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.cp-form__row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}

.cp-field {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.cp-field label {
    font-size: 0.88rem;
    font-weight: 600;
    color: var(--ink);
    letter-spacing: 0.02em;
}

.cp-field label span {
    color: var(--accent-warm);
}

.cp-field input,
.cp-field textarea {
    padding: 14px 18px;
    border: 1.5px solid var(--surface-3);
    border-radius: var(--radius-sm);
    font-family: 'DM Sans', sans-serif;
    font-size: 0.97rem;
    color: var(--ink);
    background: var(--surface-2);
    transition: all 0.25s;
    outline: none;
    resize: none;
}

.cp-field input:focus,
.cp-field textarea:focus {
    border-color: var(--accent);
    background: white;
    box-shadow: 0 0 0 4px var(--accent-glow);
}

.cp-field input::placeholder,
.cp-field textarea::placeholder {
    color: var(--ink-faint);
}

.cp-submit {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 18px 24px;
    background: var(--ink);
    color: white;
    border: none;
    border-radius: var(--radius-sm);
    font-family: 'Syne', sans-serif;
    font-size: 1rem;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.3s;
    letter-spacing: 0.04em;
    overflow: hidden;
    position: relative;
}

.cp-submit::before {
    content: '';
    position: absolute;
    inset: 0;
    background: var(--accent);
    transform: translateX(-100%);
    transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}

.cp-submit:hover::before {
    transform: translateX(0);
}

.cp-submit__text,
.cp-submit__icon {
    position: relative;
    z-index: 1;
}

.cp-submit__icon {
    width: 36px; height: 36px;
    background: rgba(255,255,255,0.1);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: transform 0.3s;
}

.cp-submit:hover .cp-submit__icon {
    transform: translateX(4px) rotate(5deg);
}

/* Side Blocks */
.cp-side {
    display: flex;
    flex-direction: column;
    gap: 20px;
    position: sticky;
    top: 30px;
}

.cp-side-block {
    background: var(--surface-2);
    border-radius: var(--radius-md);
    padding: 24px;
    border: 1px solid var(--surface-3);
}

.cp-side-block__label {
    font-size: 0.75rem;
    font-weight: 700;
    letter-spacing: 0.15em;
    text-transform: uppercase;
    color: var(--ink-faint);
    margin-bottom: 16px;
}

.cp-map iframe {
    border-radius: var(--radius-sm);
    display: block;
    width: 100%;
}

.cp-hours {
    list-style: none;
    padding: 0; margin: 0;
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.cp-hours li {
    display: flex;
    align-items: center;
    gap: 10px;
}

.cp-hours__day {
    font-size: 0.9rem;
    font-weight: 500;
    color: var(--ink-muted);
    white-space: nowrap;
}

.cp-hours__line {
    flex: 1;
    height: 1px;
    background: var(--surface-3);
}

.cp-hours__time {
    font-size: 0.9rem;
    font-weight: 600;
    color: var(--ink);
    white-space: nowrap;
}

.cp-hours__emergency {
    margin-top: 4px;
    padding: 12px 16px;
    background: rgba(156,124,51,0.08);
    border: 1px dashed rgba(156,124,51,0.3);
    border-radius: var(--radius-sm);
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 0.88rem;
    font-weight: 600;
    color: var(--accent-warm);
}

.cp-socials {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.cp-social {
    width: 42px; height: 42px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1rem;
    text-decoration: none;
    transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
}

.cp-social:hover { transform: translateY(-4px) scale(1.1); }
.cp-social--fb { background: #1877f2; }
.cp-social--tw { background: #1da1f2; }
.cp-social--ig { background: linear-gradient(135deg, #f09433, #e6683c, #dc2743, #cc2366, #bc1888); }
.cp-social--li { background: #0077b5; }
.cp-social--wa { background: #25d366; }

/* =========================================
   FAQ
========================================= */
.cp-faq {
    background: var(--ink);
    padding: 100px 0;
}

.cp-faq__header {
    margin-bottom: 56px;
}

.cp-faq__header .cp-eyebrow {
    background: rgba(255,255,255,0.08);
    color: rgba(255,255,255,0.6);
}

.cp-faq__header h2 {
    font-family: 'Syne', sans-serif;
    font-size: 2.8rem;
    font-weight: 800;
    color: white;
    line-height: 1.15;
    margin: 0;
}

.cp-faq__header h2 em {
    font-style: italic;
    font-weight: 400;
    color: var(--accent-2);
}

.cp-faq__grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
}

.cp-faq-item {
    background: rgba(255,255,255,0.04);
    border: 1px solid rgba(255,255,255,0.08);
    border-radius: var(--radius-md);
    padding: 30px;
    display: flex;
    gap: 20px;
    align-items: flex-start;
    transition: all 0.3s;
}

.cp-faq-item:hover {
    background: rgba(255,255,255,0.07);
    border-color: rgba(229,200,119,0.4);
    transform: translateY(-4px);
}

.cp-faq-item__icon {
    width: 48px; height: 48px;
    background: var(--accent-glow);
    color: var(--accent-2);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.1rem;
    flex-shrink: 0;
}

.cp-faq-item__body h4 {
    font-family: 'Syne', sans-serif;
    font-size: 1.05rem;
    font-weight: 700;
    color: white;
    margin: 0 0 10px;
}

.cp-faq-item__body p {
    color: rgba(255,255,255,0.5);
    font-size: 0.92rem;
    line-height: 1.7;
    margin: 0;
    font-weight: 300;
}

.cp-faq__footer {
    margin-top: 48px;
    text-align: center;
}

.cp-btn-outline {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    padding: 14px 32px;
    border: 1.5px solid rgba(255,255,255,0.2);
    border-radius: 100px;
    color: rgba(255,255,255,0.7);
    text-decoration: none;
    font-size: 0.9rem;
    font-weight: 600;
    letter-spacing: 0.04em;
    transition: all 0.3s;
}

.cp-btn-outline:hover {
    border-color: var(--accent-2);
    color: var(--accent-2);
    background: var(--accent-glow);
}

/* =========================================
   RESPONSIVE
========================================= */
@media (max-width: 1024px) {
    .cp-main__grid {
        grid-template-columns: 1fr;
    }
    .cp-side {
        position: static;
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
    }
    .cp-hero__badge { right: 30px; bottom: 30px; width: 110px; height: 110px; }
}

@media (max-width: 768px) {
    .cp-info__grid { grid-template-columns: 1fr; }
    .cp-form__row { grid-template-columns: 1fr; }
    .cp-faq__grid { grid-template-columns: 1fr; }
    .cp-side { grid-template-columns: 1fr; }
    .cp-hero__title { font-size: 3.5rem; }
    .cp-hero__badge { display: none; }
    .cp-faq__header h2 { font-size: 2rem; }
    .cp-form-header h2 { font-size: 2rem; }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('contactForm');
    if (!form) return;

    form.addEventListener('submit', function (e) {
        e.preventDefault();

        const btn = form.querySelector('.cp-submit');
        const txt = btn.querySelector('.cp-submit__text');
        const ico = btn.querySelector('.cp-submit__icon i');

        // Show loading
        txt.textContent = 'Sending…';
        ico.className = 'fas fa-spinner fa-spin';
        btn.disabled = true;

        // Collect form data
        const formData = new FormData(form);

        // Send AJAX request
        fetch('{{ route("contact.store") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Success message
                const alert = document.createElement('div');
                alert.style.cssText = `
                    background: #dcfce7; border: 1px solid #86efac; color: #166534;
                    padding: 16px 20px; border-radius: 12px; margin-bottom: 20px;
                    display: flex; align-items: center; gap: 10px; font-size: 0.95rem;
                    animation: fadeUp 0.4s ease both;
                `;
                alert.innerHTML = '<i class="fas fa-check-circle"></i> ' + data.message;
                form.prepend(alert);
                form.reset();
                
                setTimeout(() => alert.remove(), 5000);
            } else {
                // Error message
                let errorMessage = data.message || 'Something went wrong.';
                if (data.errors) {
                    errorMessage = Object.values(data.errors).flat().join(', ');
                }
                
                const alert = document.createElement('div');
                alert.style.cssText = `
                    background: #fee2e2; border: 1px solid #fecaca; color: #991b1b;
                    padding: 16px 20px; border-radius: 12px; margin-bottom: 20px;
                    display: flex; align-items: center; gap: 10px; font-size: 0.95rem;
                    animation: fadeUp 0.4s ease both;
                `;
                alert.innerHTML = '<i class="fas fa-exclamation-circle"></i> ' + errorMessage;
                form.prepend(alert);
                
                setTimeout(() => alert.remove(), 5000);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            const alert = document.createElement('div');
            alert.style.cssText = `
                background: #fee2e2; border: 1px solid #fecaca; color: #991b1b;
                padding: 16px 20px; border-radius: 12px; margin-bottom: 20px;
                display: flex; align-items: center; gap: 10px; font-size: 0.95rem;
                animation: fadeUp 0.4s ease both;
            `;
            alert.innerHTML = '<i class="fas fa-exclamation-circle"></i> Network error. Please try again.';
            form.prepend(alert);
            
            setTimeout(() => alert.remove(), 5000);
        })
        .finally(() => {
            // Reset button
            txt.textContent = 'Send Message';
            ico.className = 'fas fa-paper-plane';
            btn.disabled = false;
        });
    });

    // Animate cards on scroll
    const cards = document.querySelectorAll('.cp-info-card, .cp-faq-item');
    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry, i) => {
            if (entry.isIntersecting) {
                setTimeout(() => {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }, i * 80);
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1 });

    cards.forEach(card => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(30px)';
        card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
        observer.observe(card);
    });
});
</script>

@push('schema')
<script type="application/ld+json">{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'FAQPage',
    'mainEntity' => [
        [
            '@type' => 'Question',
            'name' => 'What are your delivery times?',
            'acceptedAnswer' => ['@type' => 'Answer', 'text' => '1-3 business days in Dhaka, 3-7 days outside Dhaka. Same-day delivery available for urgent orders within Dhaka.'],
        ],
        [
            '@type' => 'Question',
            'name' => 'What is your return policy?',
            'acceptedAnswer' => ['@type' => 'Answer', 'text' => 'Return products within 7 days of delivery if unused and in original packaging. Full refund or exchange guaranteed.'],
        ],
        [
            '@type' => 'Question',
            'name' => 'What payment methods do you accept?',
            'acceptedAnswer' => ['@type' => 'Answer', 'text' => 'Cash on delivery, bKash, Nagad, Rocket, credit/debit cards, and bank transfers. All transactions are secure.'],
        ],
        [
            '@type' => 'Question',
            'name' => 'How can I track my order?',
            'acceptedAnswer' => ['@type' => 'Answer', 'text' => 'You will receive a tracking link via SMS and email once shipped. Also track from your account dashboard anytime.'],
        ],
    ],
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
@endpush

@endsection