@extends('layouts.panel')

@section('title', __('admin.bookings'))

@section('content')
<div class="p-6">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-xl font-semibold text-gray-900">{{ __('admin.bookings') }}</h1>
            <p class="text-sm text-gray-500 mt-0.5">{{ __('admin.bookings_sub') }}</p>
        </div>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
        @php
        $stats = [
        ['label' => __('booking.status_pending'), 'count' => $bookings->where('status','pending')->count(), 'color' => 'amber'],
        ['label' => __('booking.status_confirmed'), 'count' => $bookings->where('status','confirmed')->count(), 'color' => 'blue'],
        ['label' => __('booking.status_checked_in'), 'count' => $bookings->where('status','checked_in')->count(), 'color' => 'green'],
        ['label' => __('booking.status_cancelled'), 'count' => $bookings->where('status','cancelled')->count(), 'color' => 'red'],
        ];
        @endphp
        @foreach($stats as $stat)
        <div class="bg-white rounded-2xl border border-gray-200 px-5 py-4">
            <p class="text-xs font-medium text-gray-500 mb-1">{{ $stat['label'] }}</p>
            <p class="text-2xl font-bold text-gray-900">{{ $stat['count'] }}</p>
        </div>
        @endforeach
    </div>

    {{-- Filter --}}
    <form method="GET" class="flex flex-wrap gap-3 mb-5">
        <input type="text" name="search" value="{{ request('search') }}"
            placeholder="{{ __('admin.search_booking') }}"
            class="rounded-xl border border-gray-300 text-sm px-4 py-2 focus:ring-2 focus:ring-blue-500 w-64">
        <select name="status" class="rounded-xl border border-gray-300 text-sm px-3 py-2">
            <option value="">{{ __('admin.all_status') }}</option>
            <option value="pending" {{ request('status') === 'pending'    ? 'selected' : '' }}>{{ __('booking.status_pending') }}</option>
            <option value="confirmed" {{ request('status') === 'confirmed'  ? 'selected' : '' }}>{{ __('booking.status_confirmed') }}</option>
            <option value="checked_in" {{ request('status') === 'checked_in' ? 'selected' : '' }}>{{ __('booking.status_checked_in') }}</option>
            <option value="checked_out" {{ request('status') === 'checked_out'? 'selected' : '' }}>{{ __('booking.status_checked_out') }}</option>
            <option value="cancelled" {{ request('status') === 'cancelled'  ? 'selected' : '' }}>{{ __('booking.status_cancelled') }}</option>
        </select>
        <button type="submit" class="px-4 py-2 rounded-xl bg-gray-900 text-white text-sm font-medium hover:bg-gray-700 transition">
            {{ __('common.search') }}
        </button>
    </form>

    {{-- Table --}}
    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
        @if($bookings->isEmpty())
        <div class="text-center py-20 text-gray-400">
            <svg class="w-14 h-14 mx-auto mb-4 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
            </svg>
            <p class="text-sm font-medium text-gray-500">{{ __('booking.no_bookings') }}</p>
        </div>
        @else
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="text-left px-5 py-3 text-xs font-medium text-gray-500 uppercase tracking-wide">{{ __('booking.booking_code') }}</th>
                    <th class="text-left px-5 py-3 text-xs font-medium text-gray-500 uppercase tracking-wide">{{ __('booking.guest') }}</th>
                    <th class="text-left px-5 py-3 text-xs font-medium text-gray-500 uppercase tracking-wide">{{ __('admin.hotel_name') }}</th>
                    <th class="text-left px-5 py-3 text-xs font-medium text-gray-500 uppercase tracking-wide">{{ __('booking.check_in') }}</th>
                    <th class="text-left px-5 py-3 text-xs font-medium text-gray-500 uppercase tracking-wide">{{ __('booking.check_out') }}</th>
                    <th class="text-left px-5 py-3 text-xs font-medium text-gray-500 uppercase tracking-wide">{{ __('booking.total') }}</th>
                    <th class="text-left px-5 py-3 text-xs font-medium text-gray-500 uppercase tracking-wide">{{ __('admin.status') }}</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($bookings as $booking)
                @php $color = $booking->statusColor(); @endphp
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-5 py-4 font-mono text-xs text-gray-600">{{ $booking->booking_code }}</td>
                    <td class="px-5 py-4">
                        <p class="font-medium text-gray-900">{{ $booking->guest_name }}</p>
                        <p class="text-xs text-gray-400">{{ $booking->guest_email }}</p>
                    </td>
                    <td class="px-5 py-4 text-gray-700">{{ $booking->hotel->name ?? '—' }}</td>
                    <td class="px-5 py-4 text-gray-600">{{ $booking->check_in->format('d/m/Y') }}</td>
                    <td class="px-5 py-4 text-gray-600">{{ $booking->check_out->format('d/m/Y') }}</td>
                    <td class="px-5 py-4 font-semibold text-gray-900">
                        ${{ number_format($booking->total_amount) }}
                    </td>
                    <td class="px-5 py-4">
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium
                            bg-{{ $color }}-100 text-{{ $color }}-800">
                            <span class="w-1.5 h-1.5 rounded-full bg-{{ $color }}-500"></span>
                            {{ $booking->statusLabel() }}
                        </span>
                    </td>
                    <td class="px-5 py-4">
                        <a href="#" class="px-3 py-1.5 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-medium transition">
                            {{ __('common.view') }}
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @if($bookings->hasPages())
        <div class="px-5 py-4 border-t border-gray-100">{{ $bookings->links() }}</div>
        @endif
        @endif
    </div>
</div>
@endsection