@extends('landing.base')

@section('theme_css')
<style>
    :root {
        --lp-bg: #0e0e10;
        --lp-surface: #17171a;
        --lp-text: #f2f2f2;
        --lp-muted: #9b9ba4;
        --lp-accent: #f0f0f0;
        --lp-accent-soft: #232326;
        --lp-chip-border: #2a2a2e;
        --lp-badge-bg: #e63950;
        --lp-bar-bg: #000000;
        --lp-bar-text: #d4d4d4;
        --lp-btn-bg: linear-gradient(135deg, #fafafa, #d4d4d4);
        --lp-btn-text: #111111;
        --lp-btn-shadow: rgba(255, 255, 255, .12);
        --lp-hero-bg: linear-gradient(135deg, #1c1c1f, #0e0e10);
        --lp-cta-text: #f2f2f2;
    }
    .lp-cta p { color: #9b9ba4; }
    .lp-desc p, .lp-desc div { color: #c9c9cf; }
    .lp-chip { background: var(--lp-surface); }
</style>
@endsection
