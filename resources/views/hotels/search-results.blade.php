@extends('layouts.app')

@section('title', 'Search Results - Tripto')

@push('styles')
<style>
    .hotel-card {
        transition: all 0.25s ease;
    }

    .hotel-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.12);
    }

    .star-filled {
        color: #f59e0b;
    }

    .price-old {
        text-decoration: line-through;
        color: #9ca3af;
    }

    .discount-badge {
        background: #dcfce7;
        color: #16a34a;
    }

    input[type=range] {
        accent-color: #2563eb;
    }

    .filter-tag.active {
        background: #eff6ff;
        color: #2563eb;
        border-color: #2563eb;
    }

    /* Grid view */
    #hotelsContainer.grid-view {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1rem;
    }

    #hotelsContainer.grid-view .list-card {
        display: none !important;
    }

    #hotelsContainer.grid-view .grid-card {
        display: flex !important;
    }

    /* List view */
    #hotelsContainer.list-view {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    #hotelsContainer.list-view .list-card {
        display: flex !important;
    }

    #hotelsContainer.list-view .grid-card {
        display: none !important;
    }

    /* Map */
    #leafletMap {
        height: 100%;
        min-height: 400px;
    }

    .map-hotel-item.active {
        background: #eff6ff;
        border-left: 3px solid #2563eb;
    }

    .leaflet-popup-content {
        margin: 0;
        padding: 0;
    }

    .leaflet-popup-content-wrapper {
        padding: 0;
        border-radius: 12px;
        overflow: hidden;
    }
</style>
@endpush

@section('content')
@php
$mapHotels = $hotels->map(fn($h) => [
'id' => $h->id,
'name' => $h->name,
'lat' => $h->latitude,
'lng' => $h->longitude,
'price' => $h->price_per_night,
'rating' => $h->rating,
'reviews' => number_format($h->review_count),
'image' => $h->image_url,
'city' => $h->city,
'url' => route('hotels.show', $h->id),
]);
@endphp

{{-- SEARCH BAR --}}
<div class="sticky top-0 z-40 flex items-center justify-between -mt-22">
    <div class="max-w-xl mx-auto px-4 py-3 flex items-center justify-between gap-4">
        <form action="{{ route('hotels.search') }}" method="GET"
            class="flex items-center bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden divide-x divide-gray-200 flex-1 max-w-2xl">

            {{-- Location --}}
            <div class="flex items-center gap-2 px-2 mx-2 py-2.5 flex-1 min-w-0">
                <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <input type="text" name="location" value="{{ $location }}"
                    class="bg-transparent text-[18px] font-semibold text-gray-800 outline-none w-full placeholder-gray-400"
                    placeholder="Where to?">
            </div>

            {{-- Dates --}}
            <div class="hidden lg:flex items-center gap-2 px-5 py-2.5 shrink-0">
                <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span class="text-[18px] font-semibold text-gray-800 whitespace-nowrap">
                    {{ \Carbon\Carbon::parse($checkIn)->format('d M') }} - {{ \Carbon\Carbon::parse($checkOut)->format('d M') }}
                </span>
                <input type="hidden" name="check_in" value="{{ $checkIn }}">
                <input type="hidden" name="check_out" value="{{ $checkOut }}">
            </div>

            {{-- Guests --}}
            <div class="hidden lg:flex items-center gap-2 px-5 py-2.5 shrink-0">
                <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-5-3.87M9 20H4v-2a4 4 0 015-3.87m6-4a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                <span class="text-[18px] font-semibold text-gray-800 whitespace-nowrap">{{ $adults + $children }} Guests</span>
                <input type="hidden" name="rooms" value="{{ $rooms }}">
                <input type="hidden" name="adults" value="{{ $adults }}">
                <input type="hidden" name="children" value="{{ $children }}">
            </div>

            {{-- Search button --}}
            <button type="submit" class="m-2 rounded-full bg-blue-600 hover:bg-blue-700 text-white px-3 py-3 transition shrink-0 flex items-center justify-center">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </button>
        </form>
    </div>
</div>

{{-- MAP MODAL --}}
<div id="mapModal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="closeMap()"></div>
    <div class="absolute inset-4 md:inset-8 bg-white rounded-2xl overflow-hidden shadow-2xl flex flex-col">

        {{-- Header --}}
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100 shrink-0">
            <div>
                <h2 class="font-bold text-gray-900">Hotels in {{ $location }}</h2>
                <p class="text-xs text-gray-400 mt-0.5">{{ $hotels->total() }} properties found</p>
            </div>
            <button onclick="closeMap()" class="w-9 h-9 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center transition">
                <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        {{-- Map + List --}}
        <div class="flex flex-1 overflow-hidden">
            <div id="leafletMap" class="flex-1 z-0"></div>
            <div class="w-80 shrink-0 border-l border-gray-100 overflow-y-auto">
                @foreach($hotels as $hotel)
                <div class="map-hotel-item px-3 py-3 border-b border-gray-100 cursor-pointer hover:bg-gray-50 transition"
                    data-id="{{ $hotel->id }}"
                    onclick="focusHotel({{ $hotel->id }}, {{ $hotel->latitude ?? 0 }}, {{ $hotel->longitude ?? 0 }})">
                    <div class="flex gap-3">
                        <img src="{{ $hotel->image_url }}" class="w-16 h-16 rounded-xl object-cover shrink-0"
                            onerror="this.src='https://images.unsplash.com/photo-1566073771259-6a8506099945?w=200&q=80'">
                        <div class="min-w-0 flex-1">
                            <p class="font-semibold text-sm text-gray-900 truncate">{{ $hotel->name }}</p>
                            <div class="flex items-center gap-1 mt-0.5">
                                <svg class="w-3 h-3 text-blue-500 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                                </svg>
                                <p class="text-xs text-blue-500 truncate">{{ $hotel->city }}</p>
                            </div>
                            <div class="flex items-center gap-1.5 mt-1">
                                <span class="bg-blue-600 text-white text-xs font-bold px-1.5 py-0.5 rounded-lg">{{ $hotel->rating }}</span>
                                <span class="text-xs text-gray-400">{{ number_format($hotel->review_count) }} reviews</span>
                            </div>
                            <p class="text-sm font-bold text-gray-900 mt-1">
                                ${{ number_format($hotel->price_per_night) }}
                                <span class="text-xs font-normal text-gray-400">/night</span>
                            </p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

{{-- MAIN --}}
<div class="max-w-7xl mx-auto px-4 py-6 bg-white min-h-screen mt-5">
    <div class="flex gap-6">

        {{-- SIDEBAR --}}
        <aside class="w-72 shrink-0">

            {{-- Show on Map --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-4">
                <div class="relative" style="height:150px;">
                    <div id="miniMap" class="absolute inset-0 z-0"></div>
                    <div class="absolute top-2 left-2 z-10 bg-white/90 backdrop-blur-sm rounded-xl px-2.5 py-1 shadow-sm pointer-events-none">
                        <span class="text-xs font-semibold text-gray-700">{{ $hotels->total() }} places in {{ $location }}</span>
                    </div>
                    <div class="absolute inset-0 z-10 cursor-pointer" onclick="openMap()"></div>
                </div>
                <div class="p-3">
                    <button type="button" onclick="openMap()"
                        class="flex items-center justify-center gap-2 w-full bg-white border border-gray-200 hover:border-blue-400 hover:text-blue-600 text-gray-700 font-semibold text-sm py-2.5 rounded-xl transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                        </svg>
                        Show on Map
                    </button>
                </div>
            </div>

            {{-- FILTER FORM --}}
            <form method="GET" action="{{ route('hotels.search') }}" id="filterForm">
                <input type="hidden" name="location" value="{{ $location }}">
                <input type="hidden" name="check_in" value="{{ $checkIn }}">
                <input type="hidden" name="check_out" value="{{ $checkOut }}">
                <input type="hidden" name="rooms" value="{{ $rooms }}">
                <input type="hidden" name="adults" value="{{ $adults }}">
                <input type="hidden" name="children" value="{{ $children }}">
                <input type="hidden" name="price_max" id="priceMaxInput" value="{{ request('price_max', 1500) }}">
                <input type="hidden" name="distance_max" id="distanceMaxInput" value="{{ request('distance_max', 10) }}">

                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-bold text-gray-900">Filter by</h3>
                        <a href="{{ route('hotels.search', ['location'=>$location,'check_in'=>$checkIn,'check_out'=>$checkOut,'rooms'=>$rooms,'adults'=>$adults,'children'=>$children]) }}"
                            class="text-sm text-blue-600 hover:underline font-medium">Clear</a>
                    </div>

                    @php
                    $chevron = '<svg class="chevron w-4 h-4 text-gray-400 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>';
                    @endphp

                    {{-- Price Range --}}
                    <div class="border-b border-gray-100 mb-1">
                        <button type="button" onclick="toggleFilter('price')" class="flex items-center justify-between w-full py-3 text-left">
                            <span class="font-semibold text-gray-800 text-sm">Price Range</span>{!! $chevron !!}
                        </button>
                        <div id="filter-price" class="pb-4">
                            <p class="text-xs text-gray-400 mb-3">Nightly prices including fees and taxes</p>
                            <div class="flex gap-0.5 items-end h-10 mb-3">
                                @foreach([2,3,4,5,7,9,11,13,12,10,8,7,6,5,4,3,2,1] as $h)
                                <div class="flex-1 bg-blue-100 rounded-sm" style="height:{{ $h * 8 }}%"></div>
                                @endforeach
                            </div>
                            <input type="range" id="priceRange" min="0" max="1500"
                                value="{{ request('price_max', 1500) }}" class="w-full mb-3"
                                oninput="updatePrice(this.value)">
                            <div class="flex gap-2">
                                <div class="flex-1 border border-gray-200 rounded-xl px-3 py-2">
                                    <p class="text-xs text-gray-400">Minimum</p>
                                    <p class="text-sm font-semibold text-gray-800">$ 0</p>
                                </div>
                                <div class="flex-1 border border-gray-200 rounded-xl px-3 py-2">
                                    <p class="text-xs text-gray-400">Maximum</p>
                                    <p class="text-sm font-semibold text-gray-800" id="maxPriceLabel">
                                        {{ request('price_max', 1500) >= 1500 ? '$ 1,500+' : '$ '.number_format(request('price_max'), 0) }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Distance --}}
                    <div class="border-b border-gray-100 mb-1">
                        <button type="button" onclick="toggleFilter('distance')" class="flex items-center justify-between w-full py-3 text-left">
                            <span class="font-semibold text-gray-800 text-sm">Distance From Centre</span>{!! $chevron !!}
                        </button>
                        <div id="filter-distance" class="pb-4">
                            <div class="flex justify-between text-xs text-gray-400 mb-1"><span>1 km</span><span>10+ km</span></div>
                            <input type="range" id="distanceRange" min="1" max="10"
                                value="{{ request('distance_max', 10) }}" class="w-full"
                                oninput="updateDistance(this.value)">
                            <p class="text-xs text-gray-500 mt-2">
                                Within <span id="distanceVal" class="font-semibold text-blue-600">{{ request('distance_max', 10) >= 10 ? '10+' : request('distance_max') }}</span> km from centre
                            </p>
                        </div>
                    </div>

                    {{-- Review Score --}}
                    <div class="border-b border-gray-100 mb-1">
                        <button type="button" onclick="toggleFilter('review')" class="flex items-center justify-between w-full py-3 text-left">
                            <span class="font-semibold text-gray-800 text-sm">Guest Review Score</span>{!! $chevron !!}
                        </button>
                        <div id="filter-review" class="pb-4">
                            @foreach([['5.0 Excellent','5.0'],['4.0+ Very good','4.0'],['3.0+ Good','3.0'],['2.0+ Fair','2.0'],['< 2.0 Poor','0']] as [$label,$val])
                                <label class="flex items-center gap-3 py-1.5 cursor-pointer group">
                                <input type="radio" name="review_score" value="{{ $val }}" class="w-4 h-4 accent-blue-600"
                                    {{ request('review_score') == $val ? 'checked' : '' }}>
                                <span class="text-sm text-gray-700 group-hover:text-blue-600 transition">{{ $label }}</span>
                                </label>
                                @endforeach
                        </div>
                    </div>

                    {{-- Stars --}}
                    <div class="border-b border-gray-100 mb-1">
                        <button type="button" onclick="toggleFilter('stars')" class="flex items-center justify-between w-full py-3 text-left">
                            <span class="font-semibold text-gray-800 text-sm">Property Classification</span>{!! $chevron !!}
                        </button>
                        <div id="filter-stars" class="pb-4">
                            @foreach([5,4,3,2,1] as $s)
                            <label class="flex items-center gap-3 py-1.5 cursor-pointer group">
                                <input type="checkbox" name="stars[]" value="{{ $s }}" class="w-4 h-4 rounded accent-blue-600"
                                    {{ in_array($s, (array) request('stars', [])) ? 'checked' : '' }}>
                                <div class="flex items-center gap-0.5">
                                    @for($x = 0; $x < $s; $x++)
                                        <svg class="w-3.5 h-3.5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                        @endfor
                                </div>
                                <span class="text-sm text-gray-700">{{ $s }}-star{{ $s === 1 ? ' / No rating' : '' }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Property Type --}}
                    <div class="border-b border-gray-100 mb-1">
                        <button type="button" onclick="toggleFilter('proptype')" class="flex items-center justify-between w-full py-3 text-left">
                            <span class="font-semibold text-gray-800 text-sm">Property Type</span>{!! $chevron !!}
                        </button>
                        <div id="filter-proptype" class="hidden pb-4">
                            <div class="flex flex-wrap gap-2">
                                @foreach(['Hotel','Hostel','Apartment','Villa','Resort','Guest House','Motel','Capsule Hotel'] as $ptype)
                                <label class="cursor-pointer">
                                    <input type="checkbox" name="property_type[]" value="{{ $ptype }}" class="sr-only peer"
                                        {{ in_array($ptype, (array) request('property_type', [])) ? 'checked' : '' }}>
                                    <span class="filter-tag peer-checked:active text-xs px-3 py-1.5 rounded-xl border border-gray-200 text-gray-600 font-medium hover:border-blue-400 transition block">{{ $ptype }}</span>
                                </label>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- Amenities --}}
                    <div class="border-b border-gray-100 mb-1">
                        <button type="button" onclick="toggleFilter('amenities')" class="flex items-center justify-between w-full py-3 text-left">
                            <span class="font-semibold text-gray-800 text-sm">Amenities</span>{!! $chevron !!}
                        </button>
                        <div id="filter-amenities" class="pb-4">
                            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-2">Popular</p>
                            <div class="flex flex-wrap gap-2 mb-4">
                                @foreach(['Wi-Fi','Air Conditioning','Pool','Gym','TV','Kitchen','BBQ Grill','Washing machine'] as $am)
                                <label class="cursor-pointer">
                                    <input type="checkbox" name="amenities[]" value="{{ $am }}" class="sr-only peer"
                                        {{ in_array($am, (array) request('amenities', [])) ? 'checked' : '' }}>
                                    <span class="filter-tag peer-checked:active text-xs px-3 py-1.5 rounded-xl border border-gray-200 text-gray-600 font-medium hover:border-blue-400 transition block">{{ $am }}</span>
                                </label>
                                @endforeach
                            </div>
                            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-2">Essentials</p>
                            <div class="flex flex-wrap gap-2">
                                @foreach(['Iron','Hair dryer','Safe','Towels','Hangers','Radiant Heating'] as $am)
                                <label class="cursor-pointer">
                                    <input type="checkbox" name="amenities[]" value="{{ $am }}" class="sr-only peer"
                                        {{ in_array($am, (array) request('amenities', [])) ? 'checked' : '' }}>
                                    <span class="filter-tag peer-checked:active text-xs px-3 py-1.5 rounded-xl border border-gray-200 text-gray-600 font-medium hover:border-blue-400 transition block">{{ $am }}</span>
                                </label>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- On-site Services --}}
                    <div class="border-b border-gray-100 mb-1">
                        <button type="button" onclick="toggleFilter('onsite')" class="flex items-center justify-between w-full py-3 text-left">
                            <span class="font-semibold text-gray-800 text-sm">On-site Services</span>{!! $chevron !!}
                        </button>
                        <div id="filter-onsite" class="hidden pb-4">
                            <div class="flex flex-wrap gap-2">
                                @foreach(['Breakfast Included','Pool','Hot Tub','Free Parking','Spa access','Gym','Restaurant','Bar','Waterfront','Private Beach Area'] as $am)
                                <label class="cursor-pointer">
                                    <input type="checkbox" name="amenities[]" value="{{ $am }}" class="sr-only peer"
                                        {{ in_array($am, (array) request('amenities', [])) ? 'checked' : '' }}>
                                    <span class="filter-tag peer-checked:active text-xs px-3 py-1.5 rounded-xl border border-gray-200 text-gray-600 font-medium hover:border-blue-400 transition block">{{ $am }}</span>
                                </label>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- Safety --}}
                    <div class="border-b border-gray-100 mb-1">
                        <button type="button" onclick="toggleFilter('safety')" class="flex items-center justify-between w-full py-3 text-left">
                            <span class="font-semibold text-gray-800 text-sm">Safety</span>{!! $chevron !!}
                        </button>
                        <div id="filter-safety" class="hidden pb-4">
                            <div class="flex flex-wrap gap-2">
                                @foreach(['Smoke Alarm','Carbon monoxide Alarm'] as $am)
                                <label class="cursor-pointer">
                                    <input type="checkbox" name="amenities[]" value="{{ $am }}" class="sr-only peer"
                                        {{ in_array($am, (array) request('amenities', [])) ? 'checked' : '' }}>
                                    <span class="filter-tag peer-checked:active text-xs px-3 py-1.5 rounded-xl border border-gray-200 text-gray-600 font-medium hover:border-blue-400 transition block">{{ $am }}</span>
                                </label>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- Booking Options --}}
                    <div class="border-b border-gray-100 mb-1">
                        <button type="button" onclick="toggleFilter('booking')" class="flex items-center justify-between w-full py-3 text-left">
                            <span class="font-semibold text-gray-800 text-sm">Booking Options</span>{!! $chevron !!}
                        </button>
                        <div id="filter-booking" class="hidden pb-4">
                            <div class="space-y-3">
                                @foreach([['free_cancellation','Free Cancellation','Get a full refund if you change your mind'],['instant_booking','Instant Booking','Book without waiting for host approval'],['pay_at_property','Pay at Property','No upfront payment needed'],['pay_later','Pay Later','Reserve now, pay closer to your stay']] as [$name,$label,$desc])
                                <label class="flex items-start gap-3 cursor-pointer group">
                                    <input type="checkbox" name="{{ $name }}" value="1" class="w-4 h-4 mt-0.5 rounded accent-blue-600 shrink-0"
                                        {{ request($name) ? 'checked' : '' }}>
                                    <div>
                                        <p class="text-sm font-medium text-gray-700 group-hover:text-blue-600 transition">{{ $label }}</p>
                                        <p class="text-xs text-gray-400">{{ $desc }}</p>
                                    </div>
                                </label>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- Payment Options --}}
                    <div class="border-b border-gray-100 mb-1">
                        <button type="button" onclick="toggleFilter('payment')" class="flex items-center justify-between w-full py-3 text-left">
                            <span class="font-semibold text-gray-800 text-sm">Payment Options</span>{!! $chevron !!}
                        </button>
                        <div id="filter-payment" class="hidden pb-4">
                            <div class="flex flex-wrap gap-2">
                                @foreach(['Credit Card','Debit Card','Cash','Bank Transfer','PayPal'] as $pm)
                                <label class="cursor-pointer">
                                    <input type="checkbox" name="payment_methods[]" value="{{ $pm }}" class="sr-only peer"
                                        {{ in_array($pm, (array) request('payment_methods', [])) ? 'checked' : '' }}>
                                    <span class="filter-tag peer-checked:active text-xs px-3 py-1.5 rounded-xl border border-gray-200 text-gray-600 font-medium hover:border-blue-400 transition block">{{ $pm }}</span>
                                </label>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- Accessibility --}}
                    <div class="mb-1">
                        <button type="button" onclick="toggleFilter('access')" class="flex items-center justify-between w-full py-3 text-left">
                            <span class="font-semibold text-gray-800 text-sm">Accessibility Features</span>{!! $chevron !!}
                        </button>
                        <div id="filter-access" class="hidden pb-4">
                            <label class="flex items-center gap-3 py-1.5 cursor-pointer group">
                                <input type="checkbox" name="wheelchair_accessible" value="1" class="w-4 h-4 rounded accent-blue-600"
                                    {{ request('wheelchair_accessible') ? 'checked' : '' }}>
                                <span class="text-sm text-gray-700 group-hover:text-blue-600 transition">Wheelchair accessible</span>
                            </label>
                        </div>
                    </div>

                    <button type="submit" class="mt-4 w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 rounded-xl transition text-sm">
                        Apply Filters
                    </button>
                </div>
            </form>
        </aside>

        {{-- RESULTS --}}
        <div class="flex-1 min-w-0">

            {{-- Results header --}}
            <div class="mb-5">
                <h1 class="text-[30px] font-bold text-gray-900 mb-4">
                    Explore {{ $hotels->total() }}+ Places in <span>{{ $location }}</span>
                </h1>
                <div class="flex justify-between items-center">
                    <form method="GET" action="{{ route('hotels.search') }}" id="sortForm">
                        @foreach(request()->except('sort') as $key => $val)
                        @if(is_array($val))
                        @foreach($val as $v)<input type="hidden" name="{{ $key }}[]" value="{{ $v }}">@endforeach
                        @else
                        <input type="hidden" name="{{ $key }}" value="{{ $val }}">
                        @endif
                        @endforeach
                        <select name="sort" onchange="document.getElementById('sortForm').submit()"
                            class="border border-gray-400 rounded-xl px-3 py-1 text-lg font-semibold text-gray-700 outline-none bg-white cursor-pointer">
                            <option value="recommended" {{ request('sort','recommended') == 'recommended' ? 'selected' : '' }}>Sort by: Top Reviewed</option>
                            <option value="price_asc" {{ request('sort') == 'price_asc'   ? 'selected' : '' }}>Sort by: Low to High</option>
                            <option value="price_desc" {{ request('sort') == 'price_desc'  ? 'selected' : '' }}>Sort by: High to Low</option>
                            <option value="rating" {{ request('sort') == 'rating'      ? 'selected' : '' }}>Sort by: Top Rated</option>
                        </select>
                    </form>
                    <div class="flex items-center bg-white border border-gray-400 rounded-full px-1 py-1 gap-3">
                        <button type="button" id="btnList" onclick="setView('list')"
                            class="w-8 h-8 rounded-full flex items-center justify-center text-gray-400 hover:bg-gray-100 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                        <button type="button" id="btnGrid" onclick="setView('grid')"
                            class="w-8 h-8 rounded-full flex items-center justify-center text-gray-400 hover:bg-gray-100 transition">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 16 16">
                                <rect x="0" y="0" width="6" height="6" rx="1" />
                                <rect x="9" y="0" width="6" height="6" rx="1" />
                                <rect x="0" y="9" width="6" height="6" rx="1" />
                                <rect x="9" y="9" width="6" height="6" rx="1" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            {{-- Hotel Cards --}}
            <div id="hotelsContainer">
                @forelse($hotels as $hotel)
                @php
                $amenityList = is_array($hotel->amenities) ? $hotel->amenities : (json_decode($hotel->amenities, true) ?? []);
                $totalPrice = $hotel->price_per_night * $nights;
                @endphp

                {{-- LIST CARD --}}
                <div class="hotel-card list-card bg-white rounded-2xl border border-gray-100 shadow-sm mb-4 overflow-hidden flex hidden">
                    <div class="relative w-64 shrink-0 self-stretch">
                        <img src="{{ $hotel->image_url }}" alt="{{ $hotel->name }}"
                            class="absolute inset-0 w-full h-full object-cover"
                            onerror="this.src='https://images.unsplash.com/photo-1566073771259-6a8506099945?w=600&q=80'">
                        @if($hotel->rating >= 4.8)
                        <span class="absolute top-3 left-3 bg-green-500 text-white text-xs font-bold px-2.5 py-1 rounded-full">Best Value</span>
                        @elseif($hotel->rating >= 4.5)
                        <span class="absolute top-3 left-3 bg-purple-500 text-white text-xs font-bold px-2.5 py-1 rounded-full">Guest Favourite</span>
                        @endif
                        <button type="button" class="absolute top-3 right-3 w-8 h-8 bg-white/80 backdrop-blur-sm rounded-full flex items-center justify-center hover:bg-white transition shadow">
                            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                        </button>
                    </div>
                    <div class="flex-1 p-5 flex flex-col justify-between">
                        <div>
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <h2 class="font-bold text-lg text-gray-900">{{ $hotel->name }}</h2>
                                    <div class="flex items-center gap-0.5 mt-1">
                                        @for($i = 0; $i < $hotel->star_rating; $i++)
                                            <svg class="w-3.5 h-3.5 star-filled" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                            </svg>
                                            @endfor
                                    </div>
                                </div>
                                <div class="text-right shrink-0">
                                    <span class="text-xs font-semibold text-gray-500 block">
                                        @if($hotel->rating >= 4.5) Excellent @elseif($hotel->rating >= 4.0) Very Good @else Good @endif
                                    </span>
                                    <div class="bg-blue-600 text-white text-sm font-bold w-10 h-10 rounded-xl flex items-center justify-center ml-auto mt-1">{{ $hotel->rating }}</div>
                                    <p class="text-xs text-gray-400 mt-0.5">{{ number_format($hotel->review_count) }} reviews</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2 mt-2 text-xs text-gray-500">
                                <svg class="w-3.5 h-3.5 text-blue-500 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                                </svg>
                                <span>{{ $hotel->city }}</span><span>·</span>
                                <span>{{ $hotel->distance_from_centre }} km from centre</span>
                            </div>
                            <div class="flex gap-1.5 mt-3 flex-wrap">
                                @foreach(array_slice($amenityList, 0, 5) as $am)
                                <span class="text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded-lg">{{ $am }}</span>
                                @endforeach
                                @if(count($amenityList) > 5)<span class="text-xs text-blue-500 font-medium">+{{ count($amenityList) - 5 }} more</span>@endif
                            </div>
                            <div class="flex gap-4 mt-3 flex-wrap">
                                @if($hotel->free_cancellation)
                                <span class="flex items-center gap-1.5 text-xs text-gray-600">
                                    <svg class="w-3.5 h-3.5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    Free cancellation
                                </span>
                                @endif
                                @if($hotel->instant_booking)
                                <span class="flex items-center gap-1.5 text-xs text-gray-600">
                                    <svg class="w-3.5 h-3.5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    Instant booking
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="flex items-end justify-between mt-4 pt-4 border-t border-gray-100">
                            <div>
                                <p class="text-xs text-gray-400">{{ $nights }} nights, {{ $adults }} adults</p>
                                <div class="flex items-baseline gap-2">
                                    <span class="price-old text-sm">${{ number_format($totalPrice * 1.1, 0) }}</span>
                                    <span class="text-xl font-bold text-gray-900">${{ number_format($totalPrice, 0) }}</span>
                                </div>
                                <span class="discount-badge text-xs font-semibold px-2 py-0.5 rounded-lg">10% off</span>
                            </div>
                            <a href="{{ route('hotels.show', $hotel->id) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm px-5 py-2.5 rounded-xl transition">View Deal →</a>
                        </div>
                    </div>
                </div>

                {{-- GRID CARD --}}
                <div class="hotel-card grid-card bg-white rounded-2xl shadow-sm overflow-hidden flex flex-col">
                    <div class="relative w-full" style="height:200px;">
                        <a href="{{ route('hotels.show', $hotel->id) }}"><img src="{{ $hotel->image_url }}" alt="{{ $hotel->name }}"
                                class="absolute inset-0 w-full h-full object-cover"
                                onerror="this.src='https://images.unsplash.com/photo-1566073771259-6a8506099945?w=600&q=80'"></a>
                        @if($hotel->rating >= 4.8)
                        <span class="absolute top-3 left-3 bg-green-500 text-white text-xs font-bold px-3 py-1 rounded-full">Best Value</span>
                        @elseif($hotel->rating >= 4.5)
                        <span class="absolute top-3 left-3 bg-purple-500 text-white text-xs font-bold px-3 py-1 rounded-full">Guest Favourite</span>
                        @elseif($hotel->rating >= 4.3)
                        <span class="absolute top-3 left-3 bg-blue-500 text-white text-xs font-bold px-3 py-1 rounded-full">Best Location</span>
                        @elseif($hotel->free_cancellation)
                        <span class="absolute top-3 left-3 bg-teal-500 text-white text-xs font-bold px-3 py-1 rounded-full">Getaway Deal</span>
                        @endif
                        <button type="button" class="absolute top-3 right-3 w-8 h-8 bg-white/80 backdrop-blur-sm rounded-full flex items-center justify-center hover:bg-white transition shadow">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                        </button>
                    </div>
                    <div class="p-4 flex flex-col flex-1">
                        <div class="flex items-start justify-between gap-2 mb-2">
                            <a href="{{ route('hotels.show', $hotel->id) }}">
                                <h2 class="font-bold text-base text-gray-900 leading-snug">{{ $hotel->name }}</h2>
                            </a>
                            <div class="flex items-center gap-0.5 shrink-0 mt-0.5">
                                <span class="text-sm font-bold text-gray-800">{{ $hotel->star_rating }}</span>
                                <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                            </div>
                        </div>
                        <div class="flex items-center gap-1 text-sm text-blue-500 mb-2">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                            </svg>
                            <span class="font-medium truncate">{{ $hotel->city }}</span>
                        </div>
                        <div class="flex items-center gap-2 mb-2">
                            <span class="bg-blue-50 text-blue-700 text-sm font-bold px-2 py-0.5 rounded-lg">{{ $hotel->rating }}</span>
                            <span class="text-sm font-semibold text-blue-700">
                                @if($hotel->rating >= 4.5) Excellent @elseif($hotel->rating >= 4.0) Very Good @else Good @endif
                            </span>
                            <span class="text-xs text-gray-400">{{ number_format($hotel->review_count) }} reviews</span>
                        </div>
                        <p class="text-xs text-gray-500 mb-2 truncate">
                            {{ $hotel->type }}
                            @if(!empty($amenityList[0])) · {{ $amenityList[0] }} @endif
                            @if(!empty($amenityList[1])) · {{ $amenityList[1] }} @endif
                        </p>
                        @if($hotel->total_rooms <= 5)
                            <p class="text-xs text-red-500 font-medium mb-3">Only {{ $hotel->total_rooms }} left at this price</p>
                            @else
                            <div class="mb-3"></div>
                            @endif
                            <div class="mt-auto pt-3 border-t border-gray-100">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="bg-green-100 text-green-700 text-xs font-bold px-2 py-0.5 rounded-full">10% off</span>
                                    <span class="price-old text-xs text-gray-400">${{ number_format($totalPrice * 1.1, 0) }}</span>
                                </div>
                                <span class="text-xl font-bold text-gray-900">${{ number_format($totalPrice, 0) }}</span>
                                <p class="text-xs text-gray-400 mt-0.5">{{ $nights }} nights, {{ $adults }} adults</p>
                            </div>
                    </div>
                </div>

                @empty
                <div class="col-span-3 text-center py-20 bg-white rounded-2xl border border-gray-100">
                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h3 class="text-xl font-bold text-gray-700 mb-2">No hotels found</h3>
                    <p class="text-gray-400 mb-4">Try adjusting your search or filters</p>
                    <a href="{{ route('home') }}" class="inline-block bg-blue-600 text-white px-6 py-2.5 rounded-xl font-semibold hover:bg-blue-700 transition">Back to Home</a>
                </div>
                @endforelse
            </div>

            @if($hotels->hasPages())
            <div class="mt-6">{{ $hotels->appends(request()->query())->links() }}</div>
            @endif
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // ===== FILTERS =====
    function toggleFilter(id) {
        const el = document.getElementById('filter-' + id);
        const chevron = el.previousElementSibling.querySelector('.chevron');
        const hidden = el.classList.contains('hidden');
        el.classList.toggle('hidden', !hidden);
        chevron.style.transform = hidden ? 'rotate(0deg)' : 'rotate(-90deg)';
    }

    // ===== VIEW TOGGLE =====
    function setView(mode) {
        const container = document.getElementById('hotelsContainer');
        const btnGrid = document.getElementById('btnGrid');
        const btnList = document.getElementById('btnList');
        const active = 'w-8 h-8 rounded-full flex items-center justify-center border-2 border-gray-800 text-gray-800 transition';
        const inactive = 'w-8 h-8 rounded-full flex items-center justify-center text-gray-400 hover:bg-gray-100 transition';
        if (mode === 'grid') {
            container.className = 'grid-view';
            btnGrid.className = active;
            btnList.className = inactive;
        } else {
            container.className = 'list-view';
            btnList.className = active;
            btnGrid.className = inactive;
        }
        localStorage.setItem('hotelView', mode);
    }

    // ===== SLIDERS =====
    function updatePrice(val) {
        document.getElementById('maxPriceLabel').textContent = val >= 1500 ? '$ 1,500+' : '$ ' + parseInt(val).toLocaleString();
        document.getElementById('priceMaxInput').value = val;
    }

    function updateDistance(val) {
        document.getElementById('distanceVal').textContent = val >= 10 ? '10+' : val;
        document.getElementById('distanceMaxInput').value = val;
    }

    // ===== MAP =====
    let miniMapInstance = null;
    let mapInstance = null;
    const mapMarkers = {};

    function loadLeaflet(callback) {
        if (window.L) {
            callback();
            return;
        }
        if (!document.getElementById('leaflet-css')) {
            const link = document.createElement('link');
            link.id = 'leaflet-css';
            link.rel = 'stylesheet';
            link.href = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css';
            document.head.appendChild(link);
        }
        const script = document.createElement('script');
        script.src = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js';
        script.onload = callback;
        document.head.appendChild(script);
    }

    function initMiniMap() {
        const hotels = @json($mapHotels);
        const valid = hotels.filter(h => h.lat && h.lng);
        if (!valid.length) return;
        const avgLat = valid.reduce((s, h) => s + parseFloat(h.lat), 0) / valid.length;
        const avgLng = valid.reduce((s, h) => s + parseFloat(h.lng), 0) / valid.length;
        miniMapInstance = L.map('miniMap', {
            zoomControl: false,
            dragging: false,
            scrollWheelZoom: false,
            doubleClickZoom: false,
            touchZoom: false,
            keyboard: false,
            attributionControl: false
        }).setView([avgLat, avgLng], 11);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(miniMapInstance);
        valid.forEach(h => {
            L.circleMarker([h.lat, h.lng], {
                    radius: 5,
                    fillColor: '#2563eb',
                    color: '#fff',
                    weight: 1.5,
                    fillOpacity: 1
                })
                .addTo(miniMapInstance);
        });
    }

    function openMap() {
        document.getElementById('mapModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        setTimeout(() => {
            if (mapInstance) {
                mapInstance.invalidateSize();
                return;
            }
            renderFullMap();
        }, 150);
    }

    function closeMap() {
        document.getElementById('mapModal').classList.add('hidden');
        document.body.style.overflow = '';
    }

    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') closeMap();
    });

    function renderFullMap() {
        const hotels = @json($mapHotels);
        const valid = hotels.filter(h => h.lat && h.lng);
        if (!valid.length) return;
        const avgLat = valid.reduce((s, h) => s + parseFloat(h.lat), 0) / valid.length;
        const avgLng = valid.reduce((s, h) => s + parseFloat(h.lng), 0) / valid.length;
        mapInstance = L.map('leafletMap').setView([avgLat, avgLng], 12);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(mapInstance);
        valid.forEach(hotel => {
            const icon = L.divIcon({
                className: '',
                html: `<div style="background:#2563eb;color:#fff;font-size:11px;font-weight:700;padding:4px 10px;border-radius:20px;white-space:nowrap;box-shadow:0 2px 8px rgba(0,0,0,0.25);cursor:pointer">$${hotel.price}</div>`,
                iconAnchor: [30, 14]
            });
            const popup = `
                <div style="width:210px;font-family:sans-serif">
                    <img src="${hotel.image}" style="width:100%;height:110px;object-fit:cover"
                         onerror="this.src='https://images.unsplash.com/photo-1566073771259-6a8506099945?w=300&q=80'">
                    <div style="padding:10px">
                        <p style="font-weight:700;font-size:13px;margin:0 0 3px;color:#111">${hotel.name}</p>
                        <p style="color:#3b82f6;font-size:11px;margin:0 0 6px">${hotel.city}</p>
                        <div style="display:flex;align-items:center;gap:5px;margin-bottom:6px">
                            <span style="background:#2563eb;color:#fff;font-size:11px;font-weight:700;padding:2px 6px;border-radius:6px">${hotel.rating}</span>
                            <span style="color:#6b7280;font-size:11px">${hotel.reviews} reviews</span>
                        </div>
                        <p style="font-weight:700;font-size:14px;margin:0 0 8px;color:#111">$${hotel.price}<span style="font-weight:400;color:#9ca3af;font-size:11px">/night</span></p>
                        <a href="${hotel.url}" style="display:block;background:#2563eb;color:#fff;text-align:center;padding:7px;border-radius:8px;font-size:12px;font-weight:600;text-decoration:none">View Hotel →</a>
                    </div>
                </div>`;
            const marker = L.marker([hotel.lat, hotel.lng], {
                    icon
                })
                .addTo(mapInstance)
                .bindPopup(popup, {
                    maxWidth: 230,
                    offset: [0, -5]
                });
            marker.on('popupopen', () => highlightListItem(hotel.id));
            marker.on('popupclose', () => clearHighlight());
            mapMarkers[hotel.id] = marker;
        });
    }

    function focusHotel(id, lat, lng) {
        if (!mapInstance || !lat || !lng) return;
        mapInstance.setView([lat, lng], 15, {
            animate: true
        });
        if (mapMarkers[id]) mapMarkers[id].openPopup();
        highlightListItem(id);
    }

    function highlightListItem(id) {
        clearHighlight();
        const item = document.querySelector(`.map-hotel-item[data-id="${id}"]`);
        if (item) {
            item.classList.add('active');
            item.scrollIntoView({
                behavior: 'smooth',
                block: 'nearest'
            });
        }
    }

    function clearHighlight() {
        document.querySelectorAll('.map-hotel-item.active').forEach(el => el.classList.remove('active'));
    }

    // ===== INIT =====
    document.addEventListener('DOMContentLoaded', function() {
        setView(localStorage.getItem('hotelView') || 'grid');
        ['price', 'distance', 'review', 'stars', 'amenities'].forEach(id => {
            const el = document.getElementById('filter-' + id);
            if (el) {
                el.classList.remove('hidden');
                el.previousElementSibling?.querySelector('.chevron')?.style.setProperty('transform', 'rotate(0deg)');
            }
        });
        ['proptype', 'onsite', 'safety', 'booking', 'payment', 'access'].forEach(id => {
            const el = document.getElementById('filter-' + id);
            if (el) {
                el.classList.add('hidden');
                el.previousElementSibling?.querySelector('.chevron')?.style.setProperty('transform', 'rotate(-90deg)');
            }
        });
        loadLeaflet(initMiniMap);
    });
</script>
@endpush