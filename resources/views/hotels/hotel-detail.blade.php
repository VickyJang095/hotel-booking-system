@extends('layouts.app')

@section('title', $hotel->name . ' - Tripto')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    #hotelMap {
        height: 280px;
        border-radius: 16px;
        overflow: hidden;
    }

    .gallery-grid {
        display: grid;
        grid-template-columns: 2fr 1fr 1fr;
        grid-template-rows: 1fr 1fr;
        gap: 6px;
        height: 420px;
    }

    .gallery-grid .main-img {
        grid-row: 1/3;
    }

    .gallery-grid img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        cursor: pointer;
        transition: opacity .2s;
    }

    .gallery-grid img:hover {
        opacity: .88;
    }

    .gallery-grid .main-img img {
        border-radius: 16px 0 0 16px;
    }

    .gallery-grid .top-right-1 img {
        border-radius: 0 16px 0 0;
    }

    .gallery-grid .bot-right-2 img {
        border-radius: 0 0 16px 0;
    }

    .tab-btn {
        position: relative;
        padding: .6rem 1.2rem;
        font-size: .875rem;
        font-weight: 600;
        color: #6b7280;
        border-bottom: 2px solid transparent;
        transition: all .2s;
        white-space: nowrap;
    }

    .tab-btn.active {
        color: #2563eb;
        border-bottom-color: #2563eb;
    }

    .tab-btn:hover {
        color: #374151;
    }

    .tab-content {
        display: none;
    }

    .tab-content.active {
        display: block;
    }

    .room-card {
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        overflow: hidden;
        transition: all .2s;
    }

    .room-card:hover {
        box-shadow: 0 8px 24px rgba(0, 0, 0, .08);
    }

    .filter-pill {
        padding: .4rem 1rem;
        border: 1px solid #e5e7eb;
        border-radius: 9999px;
        font-size: .8125rem;
        font-weight: 500;
        cursor: pointer;
        transition: all .2s;
    }

    .filter-pill.active,
    .filter-pill:hover {
        background: #2563eb;
        color: #fff;
        border-color: #2563eb;
    }

    .star-bar {
        height: 6px;
        background: #e5e7eb;
        border-radius: 9999px;
        overflow: hidden;
    }

    .star-bar-fill {
        height: 100%;
        background: #f59e0b;
        border-radius: 9999px;
    }

    .booking-widget {
        position: sticky;
        top: 80px;
    }

    .activity-card img {
        width: 100%;
        height: 140px;
        object-fit: cover;
        border-radius: 12px 12px 0 0;
    }

    .similar-card {
        border: 1px solid #f3f4f6;
        border-radius: 16px;
        overflow: hidden;
        transition: all .2s;
    }

    .similar-card:hover {
        box-shadow: 0 8px 24px rgba(0, 0, 0, .1);
        transform: translateY(-2px);
    }

    .amenity-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: .625rem 0;
        font-size: .875rem;
        color: #374151;
    }

    [data-section] {
        scroll-margin-top: 80px;
    }

    .scrollbar-hide {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }

    .scrollbar-hide::-webkit-scrollbar {
        display: none;
    }

    @media(max-width:1024px) {
        .gallery-grid {
            height: 300px;
        }

        .booking-widget {
            position: static;
        }
    }

    @media(max-width:768px) {
        .gallery-grid {
            grid-template-columns: 1fr;
            grid-template-rows: auto;
            height: 240px;
        }

        .gallery-grid>*:not(.main-img) {
            display: none;
        }

        .gallery-grid .main-img img {
            border-radius: 12px;
        }
    }
</style>
@endpush

@section('content')
@php
use App\Helpers\TranslationHelper as TH;

$amenityList = is_array($hotel->amenities) ? $hotel->amenities : (json_decode($hotel->amenities, true) ?? []);
$paymentList = is_array($hotel->payment_methods) ? $hotel->payment_methods : (json_decode($hotel->payment_methods, true) ?? []);
$nights = max(1, \Carbon\Carbon::parse(request('check_in', now()->format('Y-m-d')))->diffInDays(\Carbon\Carbon::parse(request('check_out', now()->addDay()->format('Y-m-d')))));
$adults = request('adults', 2);
$checkIn = request('check_in', now()->format('Y-m-d'));
$checkOut = request('check_out', now()->addDay()->format('Y-m-d'));
$totalPrice = $hotel->price_per_night * $nights;
$currency = app(\App\Services\CurrencyService::class);

$galleryImages = [
$hotel->image_url,
'https://images.unsplash.com/photo-1631049307264-da0ec9d70304?w=600&q=80',
'https://images.unsplash.com/photo-1615460549969-36fa19521a4f?w=600&q=80',
'https://images.unsplash.com/photo-1582719508461-905c673771fd?w=600&q=80',
'https://images.unsplash.com/photo-1571003123894-1f0594d2b5d9?w=600&q=80',
];

$rooms = [
['name'=>'Phòng Superior View Biển','size'=>35,'bed'=>'Giường King','guests'=>2,'amenities'=>['View biển','Wi-Fi','Điều hòa','Mini Bar'],'price'=>$hotel->price_per_night,'available'=>true,'tag'=>'NHÓM'],
['name'=>'Phòng Deluxe','size'=>30,'bed'=>'Giường King','guests'=>2,'amenities'=>['Wi-Fi','Điều hòa','TV','Két an toàn'],'price'=>$hotel->price_per_night*0.85,'available'=>true,'tag'=>null],
['name'=>'Suite Superior','size'=>55,'bed'=>'Giường King','guests'=>2,'amenities'=>['View biển','Phòng khách','Wi-Fi','Jacuzzi'],'price'=>$hotel->price_per_night*1.5,'available'=>true,'tag'=>'NHÓM'],
['name'=>'Phòng Deluxe View Biển','size'=>38,'bed'=>'2 Giường đôi','guests'=>3,'amenities'=>['View biển','Wi-Fi','Điều hòa','Ban công'],'price'=>$hotel->price_per_night*1.1,'available'=>false,'tag'=>null],
['name'=>'Phòng Gia Đình','size'=>60,'bed'=>'1 King + 2 Đơn','guests'=>4,'amenities'=>['Wi-Fi','Điều hòa','TV','Bếp'],'price'=>$hotel->price_per_night*1.8,'available'=>true,'tag'=>null],
['name'=>'Phòng Corner','size'=>32,'bed'=>'1 Giường King','guests'=>2,'amenities'=>['View thành phố','Wi-Fi','Điều hòa'],'price'=>$hotel->price_per_night*0.9,'available'=>false,'tag'=>null],
];

$ratingLabel = $hotel->rating >= 4.5 ? __('search.excellent') : ($hotel->rating >= 4.0 ? __('search.very_good') : __('search.good'));

$policies = [
['icon'=>'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z','label'=>__('hotel.pol_checkin'),'value'=>'14:00 – 23:00'],
['icon'=>'M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1','label'=>__('hotel.pol_checkout'),'value'=>'Trước 12:00'],
['icon'=>'M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2zM10 8.5a.5.5 0 11-1 0 .5.5 0 011 0zm5 5a.5.5 0 11-1 0 .5.5 0 011 0z','label'=>__('hotel.pol_cancellation'),'value'=>$hotel->free_cancellation ? __('hotel.free_cancel_avail') : __('hotel.non_refundable')],
['icon'=>'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z','label'=>__('hotel.pol_children'),'value'=>__('hotel.children_welcome')],
['icon'=>'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z','label'=>__('hotel.pol_quiet'),'value'=>__('hotel.quiet_hours')],
['icon'=>'M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636','label'=>__('hotel.pol_smoking'),'value'=>__('hotel.no_smoking')],
['icon'=>'M14.121 14.121L19 19m-7-7l7-7m-7 7l-2.879 2.879M12 12L9.121 9.121m0 5.758a3 3 0 10-4.243 4.243 3 3 0 004.243-4.243zm0-5.758a3 3 0 10-4.243-4.243 3 3 0 004.243 4.243z','label'=>__('hotel.pol_pets'),'value'=>__('hotel.no_pets')],
];

// Icon map cho tiện nghi
$amenityIcons = [
'Wi-Fi' => '
<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0" />',
'Pool' => '
<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />',
'Gym' => '
<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />',
'Restaurant' => '
<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />',
'Spa access' => '
<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />',
'Spa' => '
<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />',
'Free Parking' => '
<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />',
'Air Conditioning' => '
<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17H3a2 2 0 01-2-2V5a2 2 0 012-2h16a2 2 0 012 2v10a2 2 0 01-2 2h-2" />',
'TV' => '
<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17H3a2 2 0 01-2-2V5a2 2 0 012-2h16a2 2 0 012 2v10a2 2 0 01-2 2h-2" />',
'Iron' => '
<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3H5a2 2 0 00-2 2v4m6-6h10a2 2 0 012 2v4M9 3v18m0 0h10a2 2 0 002-2v-4M9 21H5a2 2 0 01-2-2v-4m0 0h18" />',
'Hair dryer' => '
<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />',
'Hair Dryer' => '
<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />',
'Breakfast' => '
<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />',
'Breakfast Included' => '
<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />',
'Smoke Alarm' => '
<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />',
'Carbon monoxide Alarm' => '
<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />',
'Carbon Monoxide Alarm' => '
<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />',
'default' => '
<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />',
];
@endphp

{{-- BREADCRUMB --}}
<div class="bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 py-3 flex items-center gap-2 text-sm text-gray-500">
        <a href="{{ route('home') }}" class="hover:text-blue-600 transition">{{ __('hotel.home') }}</a>
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
        <a href="{{ route('hotels.search', ['location'=>$hotel->city,'check_in'=>$checkIn,'check_out'=>$checkOut,'adults'=>$adults,'rooms'=>1,'children'=>0]) }}" class="hover:text-blue-600 transition">{{ $hotel->city }}</a>
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
        <span class="text-gray-800 font-medium truncate">{{ $hotel->name }}</span>
    </div>
</div>

{{-- HOTEL HEADER --}}
<div class="bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 py-4">
        <div class="flex items-start justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <h1 class="text-2xl font-bold text-gray-900">{{ $hotel->name }}</h1>
                    <div class="flex items-center gap-0.5">
                        @for($i=0;$i<$hotel->star_rating;$i++)
                            <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                            @endfor
                    </div>
                </div>
                <div class="flex items-center gap-3 text-sm text-gray-500">
                    <span class="flex items-center gap-1">
                        <svg class="w-4 h-4 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                        </svg>
                        {{ $hotel->address ?? $hotel->city }}
                    </span>
                    @if($hotel->distance_from_centre)
                    <span>· {{ __('hotel.from_centre', ['km' => $hotel->distance_from_centre]) }}</span>
                    @endif
                </div>
            </div>
            <div class="flex items-center gap-2 shrink-0">
                <button class="w-9 h-9 rounded-full border border-gray-200 flex items-center justify-center hover:bg-gray-50 transition text-gray-500">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" />
                    </svg>
                </button>
                <button class="w-9 h-9 rounded-full border border-gray-200 flex items-center justify-center hover:bg-gray-50 transition text-gray-500">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</div>

{{-- STICKY TABS --}}
<div class="bg-white border-b border-gray-200 sticky top-0 z-30">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex gap-1 overflow-x-auto scrollbar-hide">
            @foreach(['overview'=>__('hotel.tab_overview'),'amenities'=>__('hotel.tab_amenities'),'location'=>__('hotel.tab_location'),'rooms'=>__('hotel.tab_rooms'),'reviews'=>__('hotel.tab_reviews'),'policies'=>__('hotel.tab_policies')] as $id=>$label)
            <button class="tab-btn {{ $id==='overview'?'active':'' }}" onclick="switchTab('{{ $id }}')">{{ $label }}</button>
            @endforeach
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 py-6">
    <div class="flex gap-6 items-start">
        <div class="flex-1 min-w-0">

            {{-- GALLERY --}}
            <div class="gallery-grid mb-6 rounded-2xl overflow-hidden">
                <div class="main-img"><img src="{{ $galleryImages[0] }}" alt="{{ $hotel->name }}" onclick="openGallery(0)" onerror="this.src='https://images.unsplash.com/photo-1566073771259-6a8506099945?w=800&q=80'"></div>
                <div class="top-right-1"><img src="{{ $galleryImages[1] }}" alt="Phòng" onclick="openGallery(1)"></div>
                <div><img src="{{ $galleryImages[2] }}" alt="Hồ bơi" onclick="openGallery(2)"></div>
                <div><img src="{{ $galleryImages[3] }}" alt="Tầm nhìn" onclick="openGallery(3)"></div>
                <div class="bot-right-2 relative">
                    <img src="{{ $galleryImages[4] }}" alt="Khách sạn" onclick="openGallery(4)">
                    <button onclick="openGallery(0)" class="absolute bottom-3 right-3 bg-white/90 backdrop-blur-sm text-gray-800 text-xs font-semibold px-3 py-1.5 rounded-xl shadow hover:bg-white transition flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        {{ __('hotel.all_photos') }}
                    </button>
                </div>
            </div>

            {{-- OVERVIEW TAB --}}
            <div class="tab-content active" id="tab-overview" data-section="overview">

                {{-- Mô tả --}}
                <div class="bg-white rounded-2xl border border-gray-100 p-6 mb-5">
                    <h2 class="text-lg font-bold text-gray-900 mb-3">{{ __('hotel.description') }}</h2>
                    <div class="flex gap-4 mb-4 flex-wrap">
                        @if($hotel->free_cancellation)
                        <div class="flex items-center gap-2 text-sm text-gray-600 bg-green-50 px-3 py-1.5 rounded-xl">
                            <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            {{ __('hotel.free_cancellation') }}
                        </div>
                        @endif
                        @if($hotel->instant_booking)
                        <div class="flex items-center gap-2 text-sm text-gray-600 bg-blue-50 px-3 py-1.5 rounded-xl">
                            <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                            {{ __('hotel.instant_booking') }}
                        </div>
                        @endif
                        @if($hotel->pay_at_property)
                        <div class="flex items-center gap-2 text-sm text-gray-600 bg-purple-50 px-3 py-1.5 rounded-xl">
                            <svg class="w-4 h-4 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                            </svg>
                            {{ __('hotel.pay_at_property') }}
                        </div>
                        @endif
                    </div>
                    <p class="text-gray-600 text-sm leading-relaxed">
                        {{ $hotel->description ?? 'Trải nghiệm sự sang trọng và thoải mái tại '.$hotel->name.'. Tọa lạc tại trung tâm '.$hotel->city.', khách sạn '.$hotel->star_rating.' sao này mang đến tiện nghi đẳng cấp và dịch vụ tận tâm.' }}
                    </p>
                    <div class="mt-4 grid grid-cols-2 sm:grid-cols-4 gap-3">
                        <div class="bg-gray-50 rounded-xl p-3 text-center">
                            <p class="text-xs text-gray-400 mb-1">{{ __('hotel.check_in_time') }}</p>
                            <p class="text-sm font-semibold text-gray-800">14:00</p>
                        </div>
                        <div class="bg-gray-50 rounded-xl p-3 text-center">
                            <p class="text-xs text-gray-400 mb-1">{{ __('hotel.check_out_time') }}</p>
                            <p class="text-sm font-semibold text-gray-800">12:00</p>
                        </div>
                        <div class="bg-gray-50 rounded-xl p-3 text-center">
                            <p class="text-xs text-gray-400 mb-1">{{ __('hotel.total_rooms') }}</p>
                            <p class="text-sm font-semibold text-gray-800">{{ $hotel->total_rooms }}</p>
                        </div>
                        <div class="bg-gray-50 rounded-xl p-3 text-center">
                            <p class="text-xs text-gray-400 mb-1">{{ __('hotel.max_guests') }}</p>
                            <p class="text-sm font-semibold text-gray-800">{{ $hotel->max_guests_per_room }}{{ __('hotel.per_room') }}</p>
                        </div>
                    </div>
                </div>

                {{-- TIỆN NGHI — dùng TranslationHelper::amenity() --}}
                <div class="bg-white rounded-2xl border border-gray-100 p-6 mb-5" id="tab-amenities" data-section="amenities">
                    <h2 class="text-lg font-bold text-gray-900 mb-4">{{ __('hotel.tab_amenities') }}</h2>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-1">
                        @foreach($amenityList as $am)
                        <div class="amenity-item">
                            <svg class="w-5 h-5 text-blue-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                {!! $amenityIcons[$am] ?? $amenityIcons['default'] !!}
                            </svg>
                            <span>{{ TH::amenity($am) }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Vị trí --}}
                <div class="bg-white rounded-2xl border border-gray-100 p-6 mb-5" id="tab-location" data-section="location">
                    <h2 class="text-lg font-bold text-gray-900 mb-4">{{ __('hotel.tab_location') }}</h2>
                    <div id="hotelMap" class="mb-3"></div>
                    <div class="flex items-start gap-2 text-sm text-gray-600">
                        <svg class="w-4 h-4 text-blue-500 mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                        </svg>
                        <span>{{ $hotel->address ?? $hotel->city }}, Việt Nam</span>
                    </div>
                </div>
            </div>

            {{-- ROOMS TAB --}}
            <div class="tab-content" id="tab-rooms" data-section="rooms">
                <div class="bg-white rounded-2xl border border-gray-100 p-6 mb-5">
                    <h2 class="text-lg font-bold text-gray-900 mb-4">{{ __('hotel.tab_rooms') }}</h2>
                    <div class="flex gap-2 flex-wrap mb-5">
                        <button class="filter-pill active" onclick="filterRooms('all',this)">{{ __('hotel.all_rooms') }}</button>
                        <button class="filter-pill" onclick="filterRooms('1bed',this)">{{ __('hotel.one_bed') }}</button>
                        <button class="filter-pill" onclick="filterRooms('2bed',this)">{{ __('hotel.two_beds') }}</button>
                        <button class="filter-pill" onclick="filterRooms('suite',this)">{{ __('hotel.suite') }}</button>
                    </div>
                    <div class="space-y-4" id="roomList">
                        @foreach($rooms as $i => $room)
                        <div class="room-card" data-room="{{ strtolower(str_contains($room['name'],'Suite')?'suite':(str_contains($room['bed'],'2')?'2bed':'1bed')) }}">
                            <div class="flex">
                                <div class="relative w-48 shrink-0">
                                    <img src="{{ $galleryImages[min($i,count($galleryImages)-1)] }}" alt="{{ $room['name'] }}" class="w-full h-full object-cover min-h-[160px]" onerror="this.src='https://images.unsplash.com/photo-1631049307264-da0ec9d70304?w=400&q=80'">
                                    @if($room['tag'])<span class="absolute top-2 left-2 bg-blue-600 text-white text-xs font-bold px-2 py-0.5 rounded-lg">{{ $room['tag'] }}</span>@endif
                                </div>
                                <div class="flex-1 p-4 flex justify-between gap-4">
                                    <div class="flex-1">
                                        <h3 class="font-bold text-gray-900 mb-1">{{ $room['name'] }}</h3>
                                        <div class="flex items-center gap-3 text-xs text-gray-500 mb-2">
                                            <span class="flex items-center gap-1"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4" />
                                                </svg>{{ $room['size'] }}m²</span>
                                            <span class="flex items-center gap-1"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                                </svg>{{ $room['bed'] }}</span>
                                            <span class="flex items-center gap-1"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-5-3.87M9 20H4v-2a4 4 0 015-3.87m6-4a4 4 0 11-8 0 4 4 0 018 0z" />
                                                </svg>{{ $room['guests'] }} {{ __('hotel.guests_room') }}</span>
                                        </div>
                                        <div class="flex flex-wrap gap-1.5">
                                            @foreach($room['amenities'] as $a)<span class="text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded-lg">{{ $a }}</span>@endforeach
                                        </div>
                                        <button class="text-xs text-blue-600 font-medium mt-2 hover:underline">{{ __('hotel.more_details') }}</button>
                                    </div>
                                    <div class="text-right shrink-0 flex flex-col justify-between">
                                        <div>
                                            <p class="text-xs text-gray-400 line-through">{{ $currency->formatPrice($room['price']*1.1) }}</p>
                                            <p class="text-xl font-bold text-gray-900">{{ $currency->formatPrice($room['price']) }}</p>
                                            <p class="text-xs text-gray-400">{{ __('hotel.per_night') }}</p>
                                        </div>
                                        @if($room['available'])
                                        <a href="{{ route('booking.details',$hotel->id) }}?check_in={{ $checkIn }}&check_out={{ $checkOut }}&adults={{ $adults }}&rooms=1&children=0" class="mt-3 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-4 py-2 rounded-xl transition block text-center">{{ __('hotel.reserve') }}</a>
                                        @else
                                        <button disabled class="mt-3 bg-gray-200 text-gray-400 text-sm font-semibold px-4 py-2 rounded-xl cursor-not-allowed block w-full">{{ __('hotel.not_available') }}</button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- REVIEWS TAB --}}
            <div class="tab-content" id="tab-reviews" data-section="reviews">
                <div class="bg-white rounded-2xl border border-gray-100 p-6 mb-5">
                    <h2 class="text-lg font-bold text-gray-900 mb-5">{{ __('hotel.tab_reviews') }}</h2>
                    <div class="flex gap-8 mb-6 flex-wrap">
                        <div class="text-center">
                            <div class="text-5xl font-bold text-gray-900 mb-1">{{ $hotel->rating }}</div>
                            <div class="flex justify-center gap-0.5 mb-1">
                                @for($i=0;$i<5;$i++)<svg class="w-4 h-4 {{ $i<floor($hotel->rating)?'text-yellow-400':'text-gray-200' }}" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>@endfor
                            </div>
                            <p class="text-sm font-semibold text-blue-600">{{ $ratingLabel }}</p>
                            <p class="text-xs text-gray-400">{{ number_format($hotel->review_count) }} {{ __('search.reviews') }}</p>
                        </div>
                        <div class="flex-1 min-w-48 space-y-2">
                            @foreach([__('hotel.review_service')=>92,__('hotel.review_cleanliness')=>88,__('hotel.review_location')=>95,__('hotel.review_value')=>85,__('hotel.review_facilities')=>90,__('hotel.review_staff')=>94] as $cat=>$pct)
                            <div class="flex items-center gap-3">
                                <span class="text-xs text-gray-500 w-20 shrink-0">{{ $cat }}</span>
                                <div class="star-bar flex-1">
                                    <div class="star-bar-fill" style="width:{{ $pct }}%"></div>
                                </div>
                                <span class="text-xs font-semibold text-gray-700 w-8 text-right">{{ number_format($pct/20,1) }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                        @php $mockReviews=[['name'=>'A. Jenny','flag'=>'🇺🇸','date'=>'3 tháng','score'=>'9.5','text'=>'Khách sạn tuyệt vời với tầm nhìn ngoạn mục. Nhân viên rất chu đáo và phòng ốc sạch sẽ.','tags'=>['Phòng sạch','Tầm nhìn đẹp','Nhân viên tốt']],['name'=>'Jan Mak','flag'=>'🇵🇱','date'=>'2 tuần','score'=>'7.8','text'=>'Vị trí tốt và phòng thoải mái. Bữa sáng xuất sắc. Tiện nghi spa hàng đầu.','tags'=>['Bữa sáng ngon','Spa','Vị trí']],['name'=>'Emily Fox','flag'=>'🇬🇧','date'=>'1 tháng','score'=>'8.5','text'=>'Khách sạn vượt mọi kỳ vọng. Kiến trúc đẹp, nhân viên thân thiện và đồ ăn tuyệt vời.','tags'=>['Đồ ăn ngon','Tầm nhìn đẹp','Nhân viên thân thiện']]]; @endphp
                        @foreach($mockReviews as $review)
                        <div class="border border-gray-100 rounded-2xl p-4">
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center gap-2">
                                    <div class="w-9 h-9 rounded-full bg-blue-100 flex items-center justify-center text-sm font-bold text-blue-600">{{ substr($review['name'],0,1) }}</div>
                                    <div>
                                        <p class="text-sm font-semibold text-gray-900">{{ $review['name'] }} {{ $review['flag'] }}</p>
                                        <p class="text-xs text-gray-400">{{ $review['date'] }} trước</p>
                                    </div>
                                </div>
                                <span class="bg-blue-600 text-white text-sm font-bold px-2 py-0.5 rounded-lg">{{ $review['score'] }}</span>
                            </div>
                            <p class="text-sm text-gray-600 leading-relaxed mb-3">{{ $review['text'] }}</p>
                            <div class="flex flex-wrap gap-1.5">@foreach($review['tags'] as $tag)<span class="text-xs bg-blue-50 text-blue-600 px-2 py-0.5 rounded-full font-medium">{{ $tag }}</span>@endforeach</div>
                        </div>
                        @endforeach
                    </div>
                    <button class="w-full py-2.5 border border-gray-200 rounded-xl text-sm font-semibold text-gray-600 hover:bg-gray-50 transition">{{ __('hotel.show_all_reviews',['n'=>number_format($hotel->review_count)]) }}</button>
                </div>
            </div>

            {{-- POLICIES TAB --}}
            <div class="tab-content" id="tab-policies" data-section="policies">
                <div class="bg-white rounded-2xl border border-gray-100 p-6 mb-5">
                    <h2 class="text-lg font-bold text-gray-900 mb-5">{{ __('hotel.tab_policies') }}</h2>
                    <p class="text-sm text-gray-500 mb-4">{{ __('hotel.policies_note') }}</p>
                    <div class="divide-y divide-gray-100">
                        @foreach($policies as $policy)
                        <div class="flex items-start gap-4 py-4">
                            <div class="w-9 h-9 rounded-xl bg-blue-50 flex items-center justify-center shrink-0"><svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $policy['icon'] }}" />
                                </svg></div>
                            <div>
                                <p class="text-sm font-semibold text-gray-800">{{ $policy['label'] }}</p>
                                <p class="text-sm text-gray-500 mt-0.5">{{ $policy['value'] }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- THINGS TO DO --}}
            <div class="bg-white rounded-2xl border border-gray-100 p-6 mb-5">
                <h2 class="text-lg font-bold text-gray-900 mb-4">{{ __('hotel.nearby_title') }}</h2>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                    @php $activities=[['name'=>'Tour Thánh Đường','img'=>'https://images.unsplash.com/photo-1583422409516-2895a77efded?w=400&q=80','price'=>'$29'],['name'=>'Show Flamenco','img'=>'https://images.unsplash.com/photo-1504609773096-104ff2c73ba4?w=400&q=80','price'=>'$45'],['name'=>'Tour Ẩm Thực','img'=>'https://images.unsplash.com/photo-1414235077428-338989a2e8c0?w=400&q=80','price'=>'$65'],['name'=>'Thử rượu & Tapas','img'=>'https://images.unsplash.com/photo-1551218808-94e220e084d2?w=400&q=80','price'=>'$39']]; @endphp
                    @foreach($activities as $act)
                    <div class="activity-card cursor-pointer group">
                        <div class="relative overflow-hidden rounded-xl"><img src="{{ $act['img'] }}" alt="{{ $act['name'] }}" class="w-full h-[120px] object-cover group-hover:scale-105 transition-transform duration-300" onerror="this.src='https://images.unsplash.com/photo-1533105079780-92b9be482077?w=400&q=80'"></div>
                        <div class="pt-2 pb-1">
                            <p class="text-sm font-semibold text-gray-800 leading-tight">{{ $act['name'] }}</p>
                            <p class="text-xs text-blue-600 font-medium mt-0.5">{{ __('hotel.from_price') }} {{ $act['price'] }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- SIMILAR HOTELS --}}
            <div class="bg-white rounded-2xl border border-gray-100 p-6 mb-5">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-bold text-gray-900">{{ __('hotel.similar_hotels',['city'=>$hotel->city]) }}</h2>
                    <div class="flex gap-1">
                        <button class="w-8 h-8 rounded-full border border-gray-200 flex items-center justify-center hover:bg-gray-50 transition text-gray-500"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg></button>
                        <button class="w-8 h-8 rounded-full border border-gray-200 flex items-center justify-center hover:bg-gray-50 transition text-gray-500"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg></button>
                    </div>
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    @php $similarImgs=['https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?w=400&q=80','https://images.unsplash.com/photo-1551882547-ff40c63fe5fa?w=400&q=80','https://images.unsplash.com/photo-1596436889106-be35e843f974?w=400&q=80','https://images.unsplash.com/photo-1571896349842-33c89424de2d?w=400&q=80']; $similarNames=['Hotel Arts '.$hotel->city,'Majestic Hotel & Spa','W '.$hotel->city,'Hotel Regina '.$hotel->city]; @endphp
                    @foreach($similarNames as $idx=>$sname)
                    <div class="similar-card">
                        <img src="{{ $similarImgs[$idx] }}" alt="{{ $sname }}" class="w-full h-[120px] object-cover" onerror="this.src='https://images.unsplash.com/photo-1566073771259-6a8506099945?w=300&q=80'">
                        <div class="p-3">
                            <p class="text-sm font-bold text-gray-900 truncate mb-1">{{ $sname }}</p>
                            <div class="flex items-center gap-0.5 mb-1">@for($s=0;$s<4;$s++)<svg class="w-3 h-3 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>@endfor</div>
                            <p class="text-xs text-gray-500 mb-1 flex items-center gap-1"><svg class="w-3 h-3 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                                </svg>{{ $hotel->city }}</p>
                            <p class="text-sm font-bold text-gray-900">{{ __('hotel.from_price') }} <span class="text-blue-600">{{ $currency->formatPrice($hotel->price_per_night*(0.8+$idx*0.15)) }}</span>{{ __('hotel.per_night') }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

        </div>

        {{-- BOOKING WIDGET --}}
        <div class="w-80 shrink-0 hidden lg:block">
            <div class="booking-widget bg-white rounded-2xl border border-gray-200 shadow-lg overflow-hidden">
                <div class="bg-blue-600 p-5 text-white">
                    <div class="flex items-center justify-between mb-1">
                        <div><span class="text-2xl font-bold">{{ $currency->formatPrice($hotel->price_per_night) }}</span><span class="text-blue-200 text-sm">{{ __('hotel.per_night') }}</span></div>
                        <div class="text-right"><span class="bg-white/20 text-white text-xs font-bold px-2 py-1 rounded-lg">{{ $hotel->rating }}</span>
                            <p class="text-blue-200 text-xs mt-0.5">{{ $ratingLabel }}</p>
                        </div>
                    </div>
                    @if($hotel->free_cancellation)
                    <p class="text-blue-100 text-xs flex items-center gap-1 mt-2"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>{{ __('hotel.free_cancellation') }}</p>
                    @endif
                </div>
                <div class="p-5">
                    <form action="{{ route('booking.details',$hotel->id) }}" method="GET" class="space-y-3">
                        <div class="grid grid-cols-2 gap-2">
                            <div class="border border-gray-200 rounded-xl p-3">
                                <p class="text-xs text-gray-400 mb-1">{{ __('booking.check_in') }}</p><input type="date" name="check_in" value="{{ $checkIn }}" class="text-sm font-semibold text-gray-800 outline-none w-full bg-transparent">
                            </div>
                            <div class="border border-gray-200 rounded-xl p-3">
                                <p class="text-xs text-gray-400 mb-1">{{ __('booking.check_out') }}</p><input type="date" name="check_out" value="{{ $checkOut }}" class="text-sm font-semibold text-gray-800 outline-none w-full bg-transparent">
                            </div>
                        </div>
                        <div class="border border-gray-200 rounded-xl p-3">
                            <p class="text-xs text-gray-400 mb-1">{{ __('search.guests') }}</p>
                            <select name="adults" class="text-sm font-semibold text-gray-800 outline-none w-full bg-transparent">
                                @for($g=1;$g<=$hotel->max_guests_per_room;$g++)<option value="{{ $g }}" {{ $adults==$g?'selected':'' }}>{{ $g }} người lớn</option>@endfor
                            </select>
                        </div>
                        <div class="bg-gray-50 rounded-xl p-3 space-y-2">
                            <div class="flex justify-between text-sm"><span class="text-gray-500">{{ $currency->formatPrice($hotel->price_per_night) }} × {{ $nights }} {{ __('search.nights_label') }}</span><span class="font-medium">{{ $currency->formatPrice($totalPrice) }}</span></div>
                            <div class="flex justify-between text-sm"><span class="text-gray-500">{{ __('hotel.taxes_fees') }}</span><span class="font-medium">{{ $currency->formatPrice($totalPrice*0.1) }}</span></div>
                            <div class="border-t border-gray-200 pt-2 flex justify-between"><span class="font-bold text-gray-900">{{ __('hotel.total') }}</span><span class="font-bold text-gray-900">{{ $currency->formatPrice($totalPrice*1.1) }}</span></div>
                        </div>
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-xl transition text-sm">{{ __('hotel.reserve_now') }}</button>
                    </form>
                    <p class="text-xs text-gray-400 text-center mt-3">{{ __('hotel.not_charged_yet') }}</p>
                    @if(!empty($paymentList))
                    <div class="mt-4 pt-4 border-t border-gray-100">
                        <p class="text-xs text-gray-400 mb-2">{{ __('hotel.accepted_payments') }}</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach($paymentList as $pm)<span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded-lg">{{ TH::paymentMethod($pm) }}</span>@endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

{{-- LIGHTBOX --}}
<div id="lightbox" class="fixed inset-0 z-50 hidden bg-black/90 flex items-center justify-center">
    <button onclick="closeLightbox()" class="absolute top-4 right-4 w-10 h-10 bg-white/10 hover:bg-white/20 rounded-full flex items-center justify-center text-white transition"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg></button>
    <button onclick="prevPhoto()" class="absolute left-4 top-1/2 -translate-y-1/2 w-10 h-10 bg-white/10 hover:bg-white/20 rounded-full flex items-center justify-center text-white transition"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg></button>
    <img id="lightboxImg" src="" alt="" class="max-h-[85vh] max-w-[85vw] object-contain rounded-xl">
    <button onclick="nextPhoto()" class="absolute right-4 top-1/2 -translate-y-1/2 w-10 h-10 bg-white/10 hover:bg-white/20 rounded-full flex items-center justify-center text-white transition"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg></button>
    <div class="absolute bottom-4 left-1/2 -translate-x-1/2 text-white text-sm" id="lightboxCount"></div>
</div>
@endsection

@php $jsLat=$hotel->latitude?(float)$hotel->latitude:null; $jsLng=$hotel->longitude?(float)$hotel->longitude:null; $jsName=$hotel->name; $jsPhotos=$galleryImages; @endphp
@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    function switchTab(id) {
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
        const m = {
            overview: 'tab-overview',
            amenities: 'tab-overview',
            location: 'tab-overview',
            rooms: 'tab-rooms',
            reviews: 'tab-reviews',
            policies: 'tab-policies'
        };
        document.getElementById(m[id] || 'tab-overview')?.classList.add('active');
        document.querySelectorAll('.tab-btn').forEach(b => {
            if (b.getAttribute('onclick') === `switchTab('${id}')`) b.classList.add('active');
        });
        if (['amenities', 'location'].includes(id)) setTimeout(() => {
            document.getElementById('tab-' + id)?.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }, 50);
    }

    function filterRooms(type, btn) {
        document.querySelectorAll('.filter-pill').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        document.querySelectorAll('#roomList .room-card').forEach(card => {
            card.style.display = (type === 'all' || card.dataset.room === type) ? '' : 'none';
        });
    }
    const photos = @json($jsPhotos);
    let currentPhoto = 0;

    function openGallery(idx) {
        currentPhoto = idx;
        document.getElementById('lightbox').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        updateLightbox();
    }

    function closeLightbox() {
        document.getElementById('lightbox').classList.add('hidden');
        document.body.style.overflow = '';
    }

    function prevPhoto() {
        currentPhoto = (currentPhoto - 1 + photos.length) % photos.length;
        updateLightbox();
    }

    function nextPhoto() {
        currentPhoto = (currentPhoto + 1) % photos.length;
        updateLightbox();
    }

    function updateLightbox() {
        document.getElementById('lightboxImg').src = photos[currentPhoto];
        document.getElementById('lightboxCount').textContent = (currentPhoto + 1) + ' / ' + photos.length;
    }
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') closeLightbox();
        if (e.key === 'ArrowLeft') prevPhoto();
        if (e.key === 'ArrowRight') nextPhoto();
    });
    document.addEventListener('DOMContentLoaded', function() {
        const hotelLat = @json($jsLat),
            hotelLng = @json($jsLng),
            hotelName = @json($jsName);
        if (hotelLat && hotelLng) {
            const map = L.map('hotelMap', {
                zoomControl: true,
                scrollWheelZoom: false
            }).setView([hotelLat, hotelLng], 15);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap'
            }).addTo(map);
            const icon = L.divIcon({
                className: '',
                html: `<div style="background:#2563eb;color:#fff;font-size:12px;font-weight:700;padding:6px 12px;border-radius:20px;white-space:nowrap;box-shadow:0 3px 10px rgba(37,99,235,0.4)">${hotelName}</div>`,
                iconAnchor: [60, 20]
            });
            L.marker([hotelLat, hotelLng], {
                icon
            }).addTo(map);
        } else {
            document.getElementById('hotelMap').innerHTML = '<div class="h-full bg-gray-100 rounded-2xl flex items-center justify-center text-gray-400 text-sm">{{ __("hotel.map_unavailable") }}</div>';
        }
    });
</script>
@endpush