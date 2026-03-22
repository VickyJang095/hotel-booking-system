@extends('layouts.panel')
@section('title', __('admin.dashboard'))
@section('page-title', __('admin.dashboard'))
@section('page-subtitle', 'Tổng quan toàn bộ hệ thống')

@section('content')
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    @php
    $cards = [
    ['label' => __('admin.total_hotels'), 'value' => $stats['total_hotels'], 'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4', 'color' => 'blue'],
    ['label' => __('admin.total_users'), 'value' => $stats['total_users'], 'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z', 'color' => 'green'],
    ['label' => __('admin.total_owners'), 'value' => $stats['total_owners'], 'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z', 'color' => 'purple'],
    ['label' => __('admin.total_bookings'), 'value' => $stats['total_bookings'], 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2', 'color' => 'orange'],
    ];
    $colors = [
    'blue' => ['bg' => 'bg-blue-50', 'text' => 'text-blue-600'],
    'green' => ['bg' => 'bg-green-50', 'text' => 'text-green-600'],
    'purple' => ['bg' => 'bg-purple-50', 'text' => 'text-purple-600'],
    'orange' => ['bg' => 'bg-orange-50', 'text' => 'text-orange-500'],
    ];
    @endphp

    @foreach($cards as $card)
    @php $c = $colors[$card['color']]; @endphp
    <div class="stat-card flex items-center gap-4">
        <div class="w-12 h-12 rounded-2xl {{ $c['bg'] }} flex items-center justify-center shrink-0">
            <svg class="w-6 h-6 {{ $c['text'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $card['icon'] }}" />
            </svg>
        </div>
        <div>
            <p class="text-2xl font-bold text-gray-900">{{ number_format($card['value']) }}</p>
            <p class="text-xs text-gray-400 font-medium">{{ $card['label'] }}</p>
        </div>
    </div>
    @endforeach
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

    {{-- Khách sạn gần đây --}}
    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
            <h2 class="text-sm font-bold text-gray-900">{{ __('admin.recent_hotels') }}</h2>
            <a href="{{ route('admin.hotels') }}" class="text-xs text-blue-600 font-semibold hover:underline">{{ __('admin.view_all') }} →</a>
        </div>
        <div class="divide-y divide-gray-50">
            @forelse($recentHotels as $hotel)
            <div class="flex items-center gap-3 px-5 py-3">
                <div class="w-10 h-10 rounded-xl overflow-hidden shrink-0 bg-gray-100">
                    <img src="{{ $hotel->image_url }}" alt="{{ $hotel->name }}"
                        class="w-full h-full object-cover"
                        onerror="this.src='https://images.unsplash.com/photo-1566073771259-6a8506099945?w=80&q=60'">
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-gray-800 truncate">{{ $hotel->name }}</p>
                    <p class="text-xs text-gray-400">{{ $hotel->city }} · ${{ number_format($hotel->price_per_night) }}/đêm</p>
                </div>
                <div class="flex items-center gap-0.5">
                    @for($s=0;$s<$hotel->star_rating;$s++)
                        <svg class="w-3 h-3 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        @endfor
                </div>
            </div>
            @empty
            <p class="px-5 py-6 text-sm text-gray-400 text-center">Chưa có khách sạn nào.</p>
            @endforelse
        </div>
    </div>

    {{-- Người dùng gần đây --}}
    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
            <h2 class="text-sm font-bold text-gray-900">{{ __('admin.recent_users') }}</h2>
            <a href="{{ route('admin.users') }}" class="text-xs text-blue-600 font-semibold hover:underline">{{ __('admin.view_all') }} →</a>
        </div>
        <div class="divide-y divide-gray-50">
            @forelse($recentUsers as $user)
            <div class="flex items-center gap-3 px-5 py-3">
                <div class="w-9 h-9 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold text-sm shrink-0">
                    {{ strtoupper(substr($user->name ?? $user->phone ?? 'U', 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-gray-800 truncate">{{ $user->name ?? 'Chưa có tên' }}</p>
                    <p class="text-xs text-gray-400">{{ $user->phone ?? $user->email ?? '–' }}</p>
                </div>
                <span class="badge {{ $user->role === 'admin' ? 'badge-admin' : ($user->role === 'hotel_owner' ? 'badge-owner' : 'badge-user') }}">
                    {{ $user->role === 'admin' ? __('admin.role_admin') : ($user->role === 'hotel_owner' ? __('admin.role_owner') : __('admin.role_user')) }}
                </span>
            </div>
            @empty
            <p class="px-5 py-6 text-sm text-gray-400 text-center">Chưa có người dùng nào.</p>
            @endforelse
        </div>
    </div>

</div>
@endsection