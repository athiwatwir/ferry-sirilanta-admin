@extends('layouts.default')

@section('content')
<style>
    .dash-welcome {
        --dash-ink: #1a2332;
        --dash-muted: #5c6b7a;
        --dash-sea: #0b6e99;
        --dash-sea-deep: #085578;
        --dash-sand: #e8f4f8;
        --dash-foam: #f7fbfd;
        min-height: min(72vh, 640px);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: clamp(1.5rem, 4vw, 3rem) clamp(1rem, 3vw, 2rem);
        border-radius: 1.25rem;
        position: relative;
        overflow: hidden;
        background:
            radial-gradient(ellipse 80% 60% at 10% 20%, rgba(11, 110, 153, 0.12), transparent 55%),
            radial-gradient(ellipse 70% 50% at 90% 80%, rgba(8, 85, 120, 0.1), transparent 50%),
            linear-gradient(160deg, var(--dash-foam) 0%, var(--dash-sand) 55%, #eef6f9 100%);
    }

    .dash-welcome::before {
        content: "";
        position: absolute;
        inset: auto -10% -30% auto;
        width: min(420px, 55vw);
        height: min(420px, 55vw);
        border-radius: 50%;
        background: radial-gradient(circle, rgba(11, 110, 153, 0.08), transparent 70%);
        pointer-events: none;
    }

    .dash-welcome__inner {
        position: relative;
        z-index: 1;
        width: 100%;
        max-width: 640px;
        text-align: center;
    }

    .dash-welcome__eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.8rem;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: var(--dash-sea);
        font-weight: 600;
        margin-bottom: 1rem;
    }

    .dash-welcome__greeting {
        font-size: clamp(1.15rem, 2.5vw, 1.35rem);
        color: var(--dash-muted);
        margin-bottom: 0.35rem;
        font-weight: 500;
    }

    .dash-welcome__name {
        font-size: clamp(2rem, 5.5vw, 3.25rem);
        line-height: 1.15;
        font-weight: 700;
        color: var(--dash-ink);
        margin-bottom: 0.75rem;
        letter-spacing: -0.02em;
        word-break: break-word;
    }

    .dash-welcome__name span {
        background: linear-gradient(120deg, var(--dash-sea) 0%, var(--dash-sea-deep) 100%);
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
    }

    .dash-welcome__sub {
        font-size: clamp(0.95rem, 2vw, 1.05rem);
        color: var(--dash-muted);
        margin: 0 auto 2rem;
        max-width: 28rem;
        line-height: 1.55;
    }

    .dash-welcome__cta .btn {
        min-height: 3.25rem;
        min-width: 12rem;
        padding: 0.85rem 1.75rem;
        font-size: 1.05rem;
        border-radius: 0.75rem;
        box-shadow: 0 10px 28px rgba(11, 110, 153, 0.22);
    }

    .dash-welcome__cta .dt-buttons {
        margin-bottom: 0 !important;
        display: inline-flex;
        justify-content: center;
    }

    .dash-welcome__meta {
        margin-top: 1.75rem;
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        justify-content: center;
    }

    @media (min-width: 768px) and (max-width: 1024px) {
        .dash-welcome {
            min-height: min(68vh, 560px);
            padding: 2.5rem 2rem;
        }

        .dash-welcome__cta .btn {
            min-height: 3.5rem;
            min-width: 14rem;
            font-size: 1.1rem;
        }
    }

</style>

<div class="dash-welcome">
    <div class="dash-welcome__inner">
        <div class="dash-welcome__eyebrow">
            <i class="icon-base ti tabler-ship"></i>
            Sirilanta
        </div>

        <p class="dash-welcome__greeting">
            {{ $greeting }} · {{ $greetingEn }}
        </p>

        <h1 class="dash-welcome__name">
            Hi, <span>{{ $displayName }}</span>
        </h1>

        <p class="dash-welcome__sub">
            พร้อมจองตั๋วเรือแล้วหรือยัง<br class="d-none d-md-inline">
            เริ่มต้นได้เลยด้วยปุ่มด้านล่าง
        </p>

        <div class="dash-welcome__cta">
            <x-button.new text="Book Now!" :href="$bookNowUrl" target="_blank" />
        </div>


    </div>
</div>
@endsection
