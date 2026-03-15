@extends('layouts.panel')
@section('title', 'Owner Dashboard')
@section('page-title', 'My Dashboard')
@section('page-subtitle', 'Overview of your hotel performance')

@section('content')
@if(!$hotel)
<div class="bg-amber-50 border border-amber-200 rounded-2xl p-6 text-center">
    <svg class="w-12 h-12 text-amber-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
    <p class="text-amber-800 font-semibold mb-1">No hotel assigned yet</p>
    <p class="text-amber-600 text-sm">Please contact an admin to assign a hotel to your account.</p>
</div>
@else

{{-- STAT CARDS --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    @php
    $cards = [
        ['label'=>'Total Rooms',    'value'=>$stats['total_rooms'],    'icon'=>'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6','color'=>'blue'],
        ['label'=>'Total Bookings', 'value'=>$stats['total_bookings'], 'icon'=>'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2','color'=>'green'],
        ['label'=>'This Month',     'value'=>$stats['this_month'],     'icon'=>'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z','color'=>'purple'],
        ['label'=>'Rating',         'value'=>$stats['rating'],         'icon'=>'M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z','color'=>'orange'],
    ];
    $colors = ['blue'=>['bg'=>'bg-blue-50','text'=>'text-blue-600'],'green'=>['bg'=>'bg-green-50','text'=>'text-green-600'],'purple'=>['bg'=>'bg-purple-50','text'=>'text-purple-600'],'orange'=>['bg'=>'bg-orange-50','text'=>'text-orange-500']];
    @endphp

    @foreach($cards as $card)
    @php $c = $colors[$card['color']]; @endphp
    <div class="stat-card flex items-center gap-4">
        <div class="w-12 h-12 rounded-2xl {{ $c['bg'] }} flex items-center justify-center shrink-0">
            <svg class="w-6 h-6 {{ $c['text'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $card['icon'] }}"/>
            </svg>
        </div>
        <div>
            <p class="text-2xl font-bold text-gray-900">{{ $card['value'] }}</p>
            <p class="text-xs text-gray-400 font-medium">{{ $card['label'] }}</p>
        </div>
    </div>
    @endforeach
</div>

{{-- HOTEL PREVIEW --}}
<div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
    <div class="flex gap-0">
        <div class="w-48 shrink-0">
            <img src="{{ $hotel->image_url }}" alt="{{ $hotel->name }}"
                 class="w-full h-full object-cover min-h-[180px]"
                 onerror="this.src='https://images.unsplash.com/photo-1566073771259-6a8506099945?w=400&q=80'">
        </div>
        <div class="flex-1 p-6">
            <div class="flex items-start justify-between mb-2">
                <div>
                    <h2 class="text-lg font-bold text-gray-900">{{ $hotel->name }}</h2>
                    <p class="text-sm text-gray-500 flex items-center gap-1 mt-0.5">
                        <svg class="w-3.5 h-3.5 text-blue-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/></svg>
                        {{ $hotel->address ?? $hotel->city }}
                    </p>
                </div>
                <a href="{{ route('owner.hotel.edit') }}" class="btn-edit flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    Edit Hotel
                </a>
            </div>
            <div class="flex items-center gap-3 mb-3">
                <div class="flex gap-0.5">
                    @for($s=0;$s<$hotel->star_rating;$s++)
                    <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    @endfor
                </div>
                <span class="bg-blue-600 text-white text-xs font-bold px-2 py-0.5 rounded-lg">{{ $hotel->rating }}</span>
                <span class="text-xs text-gray-400">{{ number_format($hotel->review_count) }} reviews</span>
            </div>
            <div class="grid grid-cols-3 gap-3">
                <div class="bg-gray-50 rounded-xl p-3 text-center">
                    <p class="text-lg font-bold text-gray-900">${{ number_format($hotel->price_per_night) }}</p>
                    <p class="text-xs text-gray-400">per night</p>
                </div>
                <div class="bg-gray-50 rounded-xl p-3 text-center">
                    <p class="text-lg font-bold text-gray-900">{{ $hotel->total_rooms }}</p>
                    <p class="text-xs text-gray-400">rooms</p>
                </div>
                <div class="bg-gray-50 rounded-xl p-3 text-center">
                    <p class="text-lg font-bold text-gray-900">{{ $hotel->max_guests_per_room }}</p>
                    <p class="text-xs text-gray-400">max guests</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection