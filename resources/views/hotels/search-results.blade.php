@extends('layouts.app')

@section('title', __('search.title') . ' - Tripto')

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
use App\Helpers\TranslationHelper as TH;
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
$currency = app(\App\Services\CurrencyService::class);
@endphp

{{-- SEARCH BAR --}}
<div class="sticky top-0 z-40 flex items-center justify-between -mt-22">
    <div class="max-w-xl mx-auto px-4 py-3 flex items-center justify-between gap-4">
        <form action="{{ route('hotels.search') }}" method="GET"
            class="flex items-center bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden divide-x divide-gray-200 flex-1 max-w-2xl">
            <div class="flex items-center gap-2 px-2 mx-2 py-2.5 flex-1 min-w-0">
                <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <input type="text" name="location" value="{{ $location }}"
                    class="bg-transparent text-[18px] font-semibold text-gray-800 outline-none w-full placeholder-gray-400"
                    placeholder="{{ __('home.search_location') }}">
            </div>
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
            <div class="hidden lg:flex items-center gap-2 px-5 py-2.5 shrink-0">
                <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-5-3.87M9 20H4v-2a4 4 0 015-3.87m6-4a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                <span class="text-[18px] font-semibold text-gray-800 whitespace-nowrap">{{ $adults + $children }} {{ __('search.guests') }}</span>
                <input type="hidden" name="rooms" value="{{ $rooms }}">
                <input type="hidden" name="adults" value="{{ $adults }}">
                <input type="hidden" name="children" value="{{ $children }}">
            </div>
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
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100 shrink-0">
            <div>
                <h2 class="font-bold text-gray-900">{{ __('search.hotels_in') }} {{ $location }}</h2>
                <p class="text-xs text-gray-400 mt-0.5">{{ $hotels->total() }} {{ __('search.properties_found') }}</p>
            </div>
            <button onclick="closeMap()" class="w-9 h-9 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center transition">
                <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <div class="flex flex-1 overflow-hidden">
            <div id="leafletMap" class="flex-1 z-0"></div>
            <div class="w-80 shrink-0 border-l border-gray-100 overflow-y-auto">
                @foreach($hotels as $hotel)
                <div class="map-hotel-item px-3 py-3 border-b border-gray-100 cursor-pointer hover:bg-gray-50 transition"
                    data-id="{{ $hotel->id }}"
                    data-lat="{{ $hotel->latitude ?? 0 }}"
                    data-lng="{{ $hotel->longitude ?? 0 }}"
                    onclick="focusHotel(this.dataset.id, this.dataset.lat, this.dataset.lng)">
                    <div class="flex gap-3">
                        <img src="{{ $hotel->image_url }}" class="w-16 h-16 rounded-xl object-cover shrink-0"
                            onerror="this.src='https://images.unsplash.com/photo-1566073771259-6a8506099945?w=200&q=80'">
                        <div class="min-w-0 flex-1">
                            <p class="font-semibold text-sm text-gray-900 truncate">{{ $hotel->name }}</p>
                            <p class="text-xs text-blue-500 truncate mt-0.5">{{ $hotel->city }}</p>
                            <div class="flex items-center gap-1.5 mt-1">
                                <span class="bg-blue-600 text-white text-xs font-bold px-1.5 py-0.5 rounded-lg">{{ $hotel->rating }}</span>
                                <span class="text-xs text-gray-400">{{ number_format($hotel->review_count) }} {{ __('search.reviews') }}</span>
                            </div>
                            <p class="text-sm font-bold text-gray-900 mt-1">
                                {{ $currency->formatPrice($hotel->price_per_night) }}
                                <span class="text-xs font-normal text-gray-400">{{ __('search.per_night') }}</span>
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
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-4">
                <div class="relative" style="height:150px;">
                    <div id="miniMap" class="absolute inset-0 z-0"></div>
                    <div class="absolute top-2 left-2 z-10 bg-white/90 backdrop-blur-sm rounded-xl px-2.5 py-1 shadow-sm pointer-events-none">
                        <span class="text-xs font-semibold text-gray-700">{{ $hotels->total() }} {{ __('search.places_in') }} {{ $location }}</span>
                    </div>
                    <div class="absolute inset-0 z-10 cursor-pointer" onclick="openMap()"></div>
                </div>
                <div class="p-3">
                    <button type="button" onclick="openMap()"
                        class="flex items-center justify-center gap-2 w-full bg-white border border-gray-200 hover:border-blue-400 hover:text-blue-600 text-gray-700 font-semibold text-sm py-2.5 rounded-xl transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                        </svg>
                        {{ __('search.show_map') }}
                    </button>
                </div>
            </div>

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
                        <h3 class="font-bold text-gray-900">{{ __('search.filter_by') }}</h3>
                        <a href="{{ route('hotels.search', ['location'=>$location,'check_in'=>$checkIn,'check_out'=>$checkOut,'rooms'=>$rooms,'adults'=>$adults,'children'=>$children]) }}"
                            class="text-sm text-blue-600 hover:underline font-medium">{{ __('common.clear') }}</a>
                    </div>

                    @php
                    $chevron = '<svg class="chevron w-4 h-4 text-gray-400 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>';
                    @endphp

                    {{-- Price Range --}}
                    <div class="border-b border-gray-100 mb-1">
                        <button type="button" onclick="toggleFilter('price')" class="flex items-center justify-between w-full py-3 text-left">
                            <span class="font-semibold text-gray-800 text-sm">{{ __('search.price_range') }}</span>{!! $chevron !!}
                        </button>
                        <div id="filter-price" class="pb-4">
                            <p class="text-xs text-gray-400 mb-3">{{ __('search.price_note') }}</p>
                            <div class="flex gap-0.5 items-end h-10 mb-3">
                                @foreach([2,3,4,5,7,9,11,13,12,10,8,7,6,5,4,3,2,1] as $h)
                                <div class="flex-1 bg-blue-100 rounded-sm" style="height:<?= $h * 8 ?>%"></div>
                                @endforeach
                            </div>
                            <input type="range" id="priceRange" min="0" max="1500" value="{{ request('price_max', 1500) }}" class="w-full mb-3" oninput="updatePrice(this.value)">
                            <div class="flex gap-2">
                                <div class="flex-1 border border-gray-200 rounded-xl px-3 py-2">
                                    <p class="text-xs text-gray-400">{{ __('search.minimum') }}</p>
                                    <p class="text-sm font-semibold text-gray-800">₫ 0</p>
                                </div>
                                <div class="flex-1 border border-gray-200 rounded-xl px-3 py-2">
                                    <p class="text-xs text-gray-400">{{ __('search.maximum') }}</p>
                                    <p class="text-sm font-semibold text-gray-800" id="maxPriceLabel">
                                        {{ request('price_max', 1500) >= 1500 ? '₫1,500+' : '₫'.number_format(request('price_max'), 0) }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Distance --}}
                    <div class="border-b border-gray-100 mb-1">
                        <button type="button" onclick="toggleFilter('distance')" class="flex items-center justify-between w-full py-3 text-left">
                            <span class="font-semibold text-gray-800 text-sm">{{ __('search.distance') }}</span>{!! $chevron !!}
                        </button>
                        <div id="filter-distance" class="pb-4">
                            <div class="flex justify-between text-xs text-gray-400 mb-1"><span>1 km</span><span>10+ km</span></div>
                            <input type="range" id="distanceRange" min="1" max="10" value="{{ request('distance_max', 10) }}" class="w-full" oninput="updateDistance(this.value)">
                            <p class="text-xs text-gray-500 mt-2">
                                {{ __('search.within_km', ['' => '']) }}<span id="distanceVal" class="font-semibold text-blue-600">{{ request('distance_max', 10) >= 10 ? '10+' : request('distance_max') }}</span> km
                            </p>
                        </div>
                    </div>

                    {{-- Review Score --}}
                    <div class="border-b border-gray-100 mb-1">
                        <button type="button" onclick="toggleFilter('review')" class="flex items-center justify-between w-full py-3 text-left">
                            <span class="font-semibold text-gray-800 text-sm">{{ __('search.review_score') }}</span>{!! $chevron !!}
                        </button>
                        <div id="filter-review" class="pb-4">
                            @foreach([
                            ['5.0 ' . __('search.excellent'), '5.0'],
                            ['4.0+ ' . __('search.very_good'), '4.0'],
                            ['3.0+ ' . __('search.good'), '3.0'],
                            ['2.0+ ' . __('search.fair'), '2.0'],
                            ['< 2.0 ' . __(' search.poor'), '0' ],
                                ] as [$label, $val])
                                <label class="flex items-center gap-3 py-1.5 cursor-pointer group">
                                <input type="radio" name="review_score" value="{{ $val }}" class="w-4 h-4 accent-blue-600" {{ request('review_score') == $val ? 'checked' : '' }}>
                                <span class="text-sm text-gray-700 group-hover:text-blue-600 transition">{{ $label }}</span>
                                </label>
                                @endforeach
                        </div>
                    </div>

                    {{-- Stars --}}
                    <div class="border-b border-gray-100 mb-1">
                        <button type="button" onclick="toggleFilter('stars')" class="flex items-center justify-between w-full py-3 text-left">
                            <span class="font-semibold text-gray-800 text-sm">{{ __('search.property_class') }}</span>{!! $chevron !!}
                        </button>
                        <div id="filter-stars" class="pb-4">
                            @foreach([5,4,3,2,1] as $s)
                            <label class="flex items-center gap-3 py-1.5 cursor-pointer group">
                                <input type="checkbox" name="stars[]" value="{{ $s }}" class="w-4 h-4 rounded accent-blue-600" {{ in_array($s, (array) request('stars', [])) ? 'checked' : '' }}>
                                <div class="flex items-center gap-0.5">
                                    @for($x = 0; $x < $s; $x++)
                                        <svg class="w-3.5 h-3.5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
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
                            <span class="font-semibold text-gray-800 text-sm">{{ __('search.property_type') }}</span>{!! $chevron !!}
                        </button>
                        <div id="filter-proptype" class="hidden pb-4">
                            <div class="flex flex-wrap gap-2">
                                @php
                                $propertyTypes = [
                                'Hotel' => __('search.pt_hotel'),
                                'Hostel' => __('search.pt_hostel'),
                                'Apartment' => __('search.pt_apartment'),
                                'Villa' => __('search.pt_villa'),
                                'Resort' => __('search.pt_resort'),
                                'Guest House' => __('search.pt_guesthouse'),
                                'Motel' => __('search.pt_motel'),
                                'Capsule Hotel'=> __('search.pt_capsule'),
                                ];
                                @endphp
                                @foreach($propertyTypes as $value => $label)
                                <label class="cursor-pointer">
                                    <input type="checkbox" name="property_type[]" value="{{ $value }}" class="sr-only peer"
                                        {{ in_array($value, (array) request('property_type', [])) ? 'checked' : '' }}>
                                    <span class="text-xs px-3 py-1.5 rounded-xl border border-gray-200 text-gray-600 font-medium hover:border-blue-400 transition block peer-checked:bg-blue-50 peer-checked:text-blue-600 peer-checked:border-blue-500">
                                        {{ $label }}
                                    </span>
                                </label>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- Amenities --}}
                    <div class="border-b border-gray-100 mb-1">
                        <button type="button" onclick="toggleFilter('amenities')" class="flex items-center justify-between w-full py-3 text-left">
                            <span class="font-semibold text-gray-800 text-sm">{{ __('search.amenities') }}</span>{!! $chevron !!}
                        </button>
                        <div id="filter-amenities" class="pb-4">
                            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-2">{{ __('search.popular') }}</p>
                            <div class="flex flex-wrap gap-2 mb-4">
                                @php
                                $popularAmenities = [
                                'Wi-Fi' => __('search.am_wifi'),
                                'Air Conditioning' => __('search.am_ac'),
                                'Pool' => __('search.am_pool'),
                                'Gym' => __('search.am_gym'),
                                'TV' => __('search.am_tv'),
                                'Kitchen' => __('search.am_kitchen'),
                                'BBQ Grill' => __('search.am_bbq'),
                                'Washing machine' => __('search.am_washer'),
                                ];
                                @endphp
                                @foreach($popularAmenities as $value => $label)
                                <label class="cursor-pointer">
                                    <input type="checkbox" name="amenities[]" value="{{ $value }}" class="sr-only peer"
                                        {{ in_array($value, (array) request('amenities', [])) ? 'checked' : '' }}>
                                    <span class="text-xs px-3 py-1.5 rounded-xl border border-gray-200 text-gray-600 font-medium hover:border-blue-400 transition block peer-checked:bg-blue-50 peer-checked:text-blue-600 peer-checked:border-blue-500">
                                        {{ $label }}
                                    </span>
                                </label>
                                @endforeach
                            </div>

                            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-2">{{ __('search.essentials') }}</p>
                            <div class="flex flex-wrap gap-2">
                                @php
                                $essentialAmenities = [
                                'Iron' => __('search.am_iron'),
                                'Hair dryer' => __('search.am_hairdryer'),
                                'Safe' => __('search.am_safe'),
                                'Towels' => __('search.am_towels'),
                                'Hangers' => __('search.am_hangers'),
                                'Radiant Heating' => __('search.am_heating'),
                                ];
                                @endphp
                                @foreach($essentialAmenities as $value => $label)
                                <label class="cursor-pointer">
                                    <input type="checkbox" name="amenities[]" value="{{ $value }}" class="sr-only peer"
                                        {{ in_array($value, (array) request('amenities', [])) ? 'checked' : '' }}>
                                    <span class="text-xs px-3 py-1.5 rounded-xl border border-gray-200 text-gray-600 font-medium hover:border-blue-400 transition block peer-checked:bg-blue-50 peer-checked:text-blue-600 peer-checked:border-blue-500">
                                        {{ $label }}
                                    </span>
                                </label>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- On-site Services --}}
                    <div class="border-b border-gray-100 mb-1">
                        <button type="button" onclick="toggleFilter('onsite')" class="flex items-center justify-between w-full py-3 text-left">
                            <span class="font-semibold text-gray-800 text-sm">{{ __('search.onsite_services') }}</span>{!! $chevron !!}
                        </button>
                        <div id="filter-onsite" class="hidden pb-4">
                            <div class="flex flex-wrap gap-2">
                                @php
                                $onsiteServices = [
                                'Breakfast Included' => __('search.os_breakfast'),
                                'Pool' => __('search.am_pool'),
                                'Hot Tub' => __('search.os_hottub'),
                                'Free Parking' => __('search.os_parking'),
                                'Spa access' => __('search.os_spa'),
                                'Gym' => __('search.am_gym'),
                                'Restaurant' => __('search.os_restaurant'),
                                'Bar' => __('search.os_bar'),
                                'Waterfront' => __('search.os_waterfront'),
                                'Private Beach Area' => __('search.os_beach'),
                                ];
                                @endphp
                                @foreach($onsiteServices as $value => $label)
                                <label class="cursor-pointer">
                                    <input type="checkbox" name="amenities[]" value="{{ $value }}" class="sr-only peer"
                                        {{ in_array($value, (array) request('amenities', [])) ? 'checked' : '' }}>
                                    <span class="text-xs px-3 py-1.5 rounded-xl border border-gray-200 text-gray-600 font-medium hover:border-blue-400 transition block peer-checked:bg-blue-50 peer-checked:text-blue-600 peer-checked:border-blue-500">
                                        {{ $label }}
                                    </span>
                                </label>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- Safety --}}
                    <div class="border-b border-gray-100 mb-1">
                        <button type="button" onclick="toggleFilter('safety')" class="flex items-center justify-between w-full py-3 text-left">
                            <span class="font-semibold text-gray-800 text-sm">{{ __('search.safety') }}</span>{!! $chevron !!}
                        </button>
                        <div id="filter-safety" class="hidden pb-4">
                            <div class="flex flex-wrap gap-2">
                                @php
                                $safetyItems = [
                                'Smoke Alarm' => __('search.sf_smoke'),
                                'Carbon monoxide Alarm' => __('search.sf_co'),
                                ];
                                @endphp
                                @foreach($safetyItems as $value => $label)
                                <label class="cursor-pointer">
                                    <input type="checkbox" name="amenities[]" value="{{ $value }}" class="sr-only peer"
                                        {{ in_array($value, (array) request('amenities', [])) ? 'checked' : '' }}>
                                    <span class="text-xs px-3 py-1.5 rounded-xl border border-gray-200 text-gray-600 font-medium hover:border-blue-400 transition block peer-checked:bg-blue-50 peer-checked:text-blue-600 peer-checked:border-blue-500">
                                        {{ $label }}
                                    </span>
                                </label>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- Booking Options --}}
                    <div class="border-b border-gray-100 mb-1">
                        <button type="button" onclick="toggleFilter('booking')" class="flex items-center justify-between w-full py-3 text-left">
                            <span class="font-semibold text-gray-800 text-sm">{{ __('search.booking_options') }}</span>{!! $chevron !!}
                        </button>
                        <div id="filter-booking" class="hidden pb-4">
                            <div class="space-y-3">
                                @foreach([
                                ['free_cancellation', __('search.free_cancellation'), __('search.free_cancellation_desc')],
                                ['instant_booking', __('search.instant_booking'), __('search.instant_booking_desc')],
                                ['pay_at_property', __('search.pay_at_property'), __('search.pay_at_property_desc')],
                                ['pay_later', __('search.pay_later'), __('search.pay_later_desc')],
                                ] as [$name, $label, $desc])
                                <label class="flex items-start gap-3 cursor-pointer group">
                                    <input type="checkbox" name="{{ $name }}" value="1" class="w-4 h-4 mt-0.5 rounded accent-blue-600 shrink-0" {{ request($name) ? 'checked' : '' }}>
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
                            <span class="font-semibold text-gray-800 text-sm">{{ __('search.payment_options') }}</span>{!! $chevron !!}
                        </button>
                        <div id="filter-payment" class="hidden pb-4">
                            <div class="flex flex-wrap gap-2">
                                @foreach(['Credit Card' => __('search.p_credit'),'Debit Card' => __('search.p_debit'),'Cash' => __('search.p_cash'),'Bank Transfer' => __('search.p_bank'),'PayPal'] as $pm)
                                <label class="cursor-pointer">
                                    <input type="checkbox" name="payment_methods[]" value="{{ $pm }}" class="sr-only peer" {{ in_array($pm, (array) request('payment_methods', [])) ? 'checked' : '' }}>
                                    <span class="text-xs px-3 py-1.5 rounded-xl border border-gray-200 text-gray-600 font-medium hover:border-blue-400 transition block peer-checked:bg-blue-50 peer-checked:text-blue-600 peer-checked:border-blue-500">{{ $pm }}</span>
                                </label>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- Accessibility --}}
                    <div class="mb-1">
                        <button type="button" onclick="toggleFilter('access')" class="flex items-center justify-between w-full py-3 text-left">
                            <span class="font-semibold text-gray-800 text-sm">{{ __('search.accessibility') }}</span>{!! $chevron !!}
                        </button>
                        <div id="filter-access" class="hidden pb-4">
                            <label class="flex items-center gap-3 py-1.5 cursor-pointer group">
                                <input type="checkbox" name="wheelchair_accessible" value="1" class="w-4 h-4 rounded accent-blue-600" {{ request('wheelchair_accessible') ? 'checked' : '' }}>
                                <span class="text-sm text-gray-700 group-hover:text-blue-600 transition">{{ __('search.wheelchair') }}</span>
                            </label>
                        </div>
                    </div>

                    <button type="submit" class="mt-4 w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 rounded-xl transition text-sm">
                        {{ __('search.apply_filters') }}
                    </button>
                </div>
            </form>
        </aside>

        {{-- RESULTS --}}
        <div class="flex-1 min-w-0">
            <div class="mb-5">
                <h1 class="text-[30px] font-bold text-gray-900 mb-4">
                    {{ __('search.places_found', ['count' => $hotels->total(), 'location' => $location]) }}
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
                            <option value="recommended" {{ request('sort','recommended') == 'recommended' ? 'selected' : '' }}>{{ __('search.sort_by') }}: {{ __('search.top_reviewed') }}</option>
                            <option value="price_asc" {{ request('sort') == 'price_asc'   ? 'selected' : '' }}>{{ __('search.sort_by') }}: {{ __('search.price_low_high') }}</option>
                            <option value="price_desc" {{ request('sort') == 'price_desc'  ? 'selected' : '' }}>{{ __('search.sort_by') }}: {{ __('search.price_high_low') }}</option>
                            <option value="rating" {{ request('sort') == 'rating'      ? 'selected' : '' }}>{{ __('search.sort_by') }}: {{ __('search.top_rated') }}</option>
                        </select>
                    </form>
                    <div class="flex items-center bg-white border border-gray-400 rounded-full px-1 py-1 gap-3">
                        <button type="button" id="btnList" onclick="setView('list')" class="w-8 h-8 rounded-full flex items-center justify-center text-gray-400 hover:bg-gray-100 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                        <button type="button" id="btnGrid" onclick="setView('grid')" class="w-8 h-8 rounded-full flex items-center justify-center text-gray-400 hover:bg-gray-100 transition">
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

            <div id="hotelsContainer">
                @forelse($hotels as $hotel)
                @php
                $amenityList = is_array($hotel->amenities) ? $hotel->amenities : (json_decode($hotel->amenities, true) ?? []);
                $totalPrice = $hotel->price_per_night * $nights;
                $ratingLabel = $hotel->rating >= 4.5 ? __('search.excellent') : ($hotel->rating >= 4.0 ? __('search.very_good') : __('search.good'));
                @endphp

                {{-- LIST CARD --}}
                <div class="hotel-card list-card bg-white rounded-2xl border border-gray-100 shadow-sm mb-4 overflow-hidden flex hidden">
                    <div class="relative w-64 shrink-0 self-stretch">
                        <img src="{{ $hotel->image_url }}" alt="{{ $hotel->name }}" class="absolute inset-0 w-full h-full object-cover" onerror="this.src='https://images.unsplash.com/photo-1566073771259-6a8506099945?w=600&q=80'">
                        @if($hotel->rating >= 4.8)
                        <span class="absolute top-3 left-3 bg-green-500 text-white text-xs font-bold px-2.5 py-1 rounded-full">{{ __('search.best_value') }}</span>
                        @elseif($hotel->rating >= 4.5)
                        <span class="absolute top-3 left-3 bg-purple-500 text-white text-xs font-bold px-2.5 py-1 rounded-full">{{ __('search.guest_favourite') }}</span>
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
                                    <span class="text-xs font-semibold text-gray-500 block">{{ $ratingLabel }}</span>
                                    <div class="bg-blue-600 text-white text-sm font-bold w-10 h-10 rounded-xl flex items-center justify-center ml-auto mt-1">{{ $hotel->rating }}</div>
                                    <p class="text-xs text-gray-400 mt-0.5">{{ number_format($hotel->review_count) }} {{ __('search.reviews') }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2 mt-2 text-xs text-gray-500">
                                <svg class="w-3.5 h-3.5 text-blue-500 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                                </svg>
                                <span>{{ $hotel->city }}</span><span>·</span>
                                <span>{{ __('search.from_centre', ['km' => $hotel->distance_from_centre]) }}</span>
                            </div>
                            <div class="flex gap-1.5 mt-3 flex-wrap">
                                @foreach(array_slice($amenityList, 0, 5) as $am)
                                <span class="text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded-lg">{{ $am }}</span>
                                @endforeach
                                @if(count($amenityList) > 5)<span class="text-xs text-blue-500 font-medium">+{{ count($amenityList) - 5 }} more</span>@endif
                            </div>
                            @if($hotel->free_cancellation || $hotel->instant_booking)
                            <div class="flex gap-4 mt-3 flex-wrap">
                                @if($hotel->free_cancellation)
                                <span class="flex items-center gap-1.5 text-xs text-gray-600">
                                    <svg class="w-3.5 h-3.5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    {{ __('search.free_cancellation') }}
                                </span>
                                @endif
                                @if($hotel->instant_booking)
                                <span class="flex items-center gap-1.5 text-xs text-gray-600">
                                    <svg class="w-3.5 h-3.5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    {{ __('search.instant_booking') }}
                                </span>
                                @endif
                            </div>
                            @endif
                        </div>
                        <div class="flex items-end justify-between mt-4 pt-4 border-t border-gray-100">
                            <div>
                                <p class="text-xs text-gray-400">{{ $nights }} {{ __('search.nights_label') }}, {{ $adults }} {{ __('search.adults_label') }}</p>
                                <div class="flex items-baseline gap-2">
                                    <span class="price-old text-sm">{{ $currency->formatPrice($totalPrice * 1.1) }}</span>
                                    <span class="text-xl font-bold text-gray-900">{{ $currency->formatPrice($totalPrice) }}</span>
                                </div>
                                <span class="discount-badge text-xs font-semibold px-2 py-0.5 rounded-lg">10% {{ __('search.off') }}</span>
                            </div>
                            <a href="{{ route('hotels.show', $hotel->id) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm px-5 py-2.5 rounded-xl transition">{{ __('search.view_deal') }}</a>
                        </div>
                    </div>
                </div>

                {{-- GRID CARD --}}
                <div class="hotel-card grid-card bg-white rounded-2xl shadow-sm overflow-hidden flex flex-col">
                    <div class="relative w-full" style="height:200px;">
                        <a href="{{ route('hotels.show', $hotel->id) }}">
                            <img src="{{ $hotel->image_url }}" alt="{{ $hotel->name }}" class="absolute inset-0 w-full h-full object-cover" onerror="this.src='https://images.unsplash.com/photo-1566073771259-6a8506099945?w=600&q=80'">
                        </a>
                        @if($hotel->rating >= 4.8)
                        <span class="absolute top-3 left-3 bg-green-500 text-white text-xs font-bold px-3 py-1 rounded-full">{{ __('search.best_value') }}</span>
                        @elseif($hotel->rating >= 4.5)
                        <span class="absolute top-3 left-3 bg-purple-500 text-white text-xs font-bold px-3 py-1 rounded-full">{{ __('search.guest_favourite') }}</span>
                        @elseif($hotel->rating >= 4.3)
                        <span class="absolute top-3 left-3 bg-blue-500 text-white text-xs font-bold px-3 py-1 rounded-full">{{ __('search.best_location') }}</span>
                        @elseif($hotel->free_cancellation)
                        <span class="absolute top-3 left-3 bg-teal-500 text-white text-xs font-bold px-3 py-1 rounded-full">{{ __('search.getaway_deal') }}</span>
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
                            <span class="text-sm font-semibold text-blue-700">{{ $ratingLabel }}</span>
                            <span class="text-xs text-gray-400">{{ number_format($hotel->review_count) }} {{ __('search.reviews') }}</span>
                        </div>
                        <p class="text-xs text-gray-500 mb-2 truncate">
                            {{ $hotel->type }}
                            @if(!empty($amenityList[0])) · {{ $amenityList[0] }} @endif
                            @if(!empty($amenityList[1])) · {{ $amenityList[1] }} @endif
                        </p>
                        @if($hotel->total_rooms <= 5)
                            <p class="text-xs text-red-500 font-medium mb-3">{{ __('search.rooms_left', ['n' => $hotel->total_rooms]) }}</p>
                            @else
                            <div class="mb-3"></div>
                            @endif
                            <div class="mt-auto pt-3 border-t border-gray-100">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="bg-green-100 text-green-700 text-xs font-bold px-2 py-0.5 rounded-full">10% {{ __('search.off') }}</span>
                                    <span class="price-old text-xs text-gray-400">{{ $currency->formatPrice($totalPrice * 1.1) }}</span>
                                </div>
                                <span class="text-xl font-bold text-gray-900">{{ $currency->formatPrice($totalPrice) }}</span>
                                <p class="text-xs text-gray-400 mt-0.5">{{ $nights }} {{ __('search.nights_label') }}, {{ $adults }} {{ __('search.adults_label') }}</p>
                            </div>
                    </div>
                </div>

                @empty
                <div class="col-span-3 text-center py-20 bg-white rounded-2xl border border-gray-100">
                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h3 class="text-xl font-bold text-gray-700 mb-2">{{ __('search.no_results') }}</h3>
                    <p class="text-gray-400 mb-4">{{ __('search.no_results_sub') }}</p>
                    <a href="{{ route('home') }}" class="inline-block bg-blue-600 text-white px-6 py-2.5 rounded-xl font-semibold hover:bg-blue-700 transition">{{ __('search.back_home') }}</a>
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
    function toggleFilter(id) {
        const el = document.getElementById('filter-' + id);
        const chevron = el.previousElementSibling.querySelector('.chevron');
        el.classList.toggle('hidden');
        const isHidden = el.classList.contains('hidden');
        if (chevron) chevron.style.transform = isHidden ? 'rotate(-90deg)' : 'rotate(0deg)';
    }

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

    function updatePrice(val) {
        document.getElementById('maxPriceLabel').textContent = val >= 1500 ? '$ 1,500+' : '$ ' + parseInt(val).toLocaleString();
        document.getElementById('priceMaxInput').value = val;
    }

    function updateDistance(val) {
        document.getElementById('distanceVal').textContent = val >= 10 ? '10+' : val;
        document.getElementById('distanceMaxInput').value = val;
    }

    let miniMapInstance = null,
        mapInstance = null;
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
        const hotels = document.getElementById('map-data') ? JSON.parse(document.getElementById('map-data').textContent) : [];
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
            }).addTo(miniMapInstance);
        });
    }

    function openMap() {
        document.getElementById('mapModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        setTimeout(() => {
            loadLeaflet(() => {
                if (mapInstance) {
                    mapInstance.invalidateSize();
                    return;
                }
                renderFullMap();
            });
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
        const hotels = document.getElementById('map-data') ? JSON.parse(document.getElementById('map-data').textContent) : [];
        const valid = hotels.filter(h => h.lat && h.lng);
        if (!valid.length) return;
        const avgLat = valid.reduce((s, h) => s + parseFloat(h.lat), 0) / valid.length;
        const avgLng = valid.reduce((s, h) => s + parseFloat(h.lng), 0) / valid.length;
        mapInstance = L.map('leafletMap').setView([avgLat, avgLng], 12);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap'
        }).addTo(mapInstance);
        valid.forEach(hotel => {
            const icon = L.divIcon({
                className: '',
                html: `<div style="background:#2563eb;color:#fff;font-size:11px;font-weight:700;padding:4px 10px;border-radius:20px;white-space:nowrap;box-shadow:0 2px 8px rgba(0,0,0,0.25);cursor:pointer">$${hotel.price}</div>`,
                iconAnchor: [30, 14]
            });
            const popup = `<div style="width:210px;font-family:sans-serif"><img src="${hotel.image}" style="width:100%;height:110px;object-fit:cover" onerror="this.src='https://images.unsplash.com/photo-1566073771259-6a8506099945?w=300&q=80'"><div style="padding:10px"><p style="font-weight:700;font-size:13px;margin:0 0 3px;color:#111">${hotel.name}</p><p style="color:#3b82f6;font-size:11px;margin:0 0 6px">${hotel.city}</p><div style="display:flex;align-items:center;gap:5px;margin-bottom:6px"><span style="background:#2563eb;color:#fff;font-size:11px;font-weight:700;padding:2px 6px;border-radius:6px">${hotel.rating}</span><span style="color:#6b7280;font-size:11px">${hotel.reviews} reviews</span></div><p style="font-weight:700;font-size:14px;margin:0 0 8px;color:#111">$${hotel.price}<span style="font-weight:400;color:#9ca3af;font-size:11px">/night</span></p><a href="${hotel.url}" style="display:block;background:#2563eb;color:#fff;text-align:center;padding:7px;border-radius:8px;font-size:12px;font-weight:600;text-decoration:none">View →</a></div></div>`;
            const marker = L.marker([hotel.lat, hotel.lng], {
                icon
            }).addTo(mapInstance).bindPopup(popup, {
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

{{-- Map data - tránh Blade syntax trong JS --}}
<script id="map-data" type="application/json">
    @json($mapHotels)
</script>
@endpush