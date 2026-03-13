@extends('layouts.app')

@section('content')

@include('partials.hero')

@include('home.introduction')

@include('home.hotel-cards')

@include('home.deal-for-weekend')

@include('home.top-sight-to-see')

@include('home.top-thing-to-do')

@include('home.explore-tripo')

@include('home.home-guest-love')

@include('home.testimonials')

@include('auth.modal')

@endsection

<style>
    html {
        scroll-behavior: smooth;
    }

    .scroll-animate {
        opacity: 0;
        transform: translateY(40px);
        transition: all 0.9s ease;
        will-change: transform, opacity;
    }

    .scroll-animate.show {
        opacity: 1;
        transform: translateY(0);
    }

    .fade-up { transform: translateY(40px); }
    .fade-down { transform: translateY(-40px); }
    .slide-left { transform: translateX(-60px); }
    .slide-right { transform: translateX(60px); }
    .zoom-in { transform: scale(0.95); }
    .zoom-out { transform: scale(1.05); }
    .delay-1 { transition-delay: .1s; }
    .delay-2 { transition-delay: .2s; }
    .delay-3 { transition-delay: .3s; }
    .delay-4 { transition-delay: .4s; }

    ::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }

    ::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 10px;
    }

    ::-webkit-scrollbar-thumb {
        background: linear-gradient(180deg, #052461, #0a3a82);
        border-radius: 10px;
    }

    ::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(180deg, #0a3a82, #052461);
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('show');
                }
            });
        }, {
            threshold: 0.15
        });

        document.querySelectorAll('.scroll-animate')
            .forEach(el => observer.observe(el));
    });
</script>
