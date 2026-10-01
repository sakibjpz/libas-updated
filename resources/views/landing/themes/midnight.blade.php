@extends('landing.base')

@section('theme_css')
<style>
    :root {
        --lp-bg: #12141c;
        --lp-surface: #1b1e2a;
        --lp-text: #f1ede4;
        --lp-muted: #a9a4b8;
        --lp-accent: #e5c877;
        --lp-accent-soft: #2a2f42;
        --lp-chip-border: #2e3350;
        --lp-badge-bg: #e63950;
        --lp-bar-bg: #0b0d14;
        --lp-bar-text: #e5c877;
        --lp-btn-bg: linear-gradient(135deg, #e5c877, #b8923f);
        --lp-btn-text: #14100a;
        --lp-btn-shadow: rgba(229, 200, 119, .3);
        --lp-hero-bg: linear-gradient(135deg, #2a2f42, #1b1e2a);
        --lp-cta-text: #f1ede4;
    }
    .lp-cta p { color: #a9a4b8; }
    .lp-desc p, .lp-desc div { color: #c9c4d4; }
    .lp-chip { background: var(--lp-surface); }
</style>
@endsection
