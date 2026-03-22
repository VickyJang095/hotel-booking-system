@extends('layouts.panel')

@section('title', __('admin.bookings'))
@php
$currency = app(\App\Services\CurrencyService::class);
@endphp
@section('content')
<div class="p-6">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-xl font-semibold text-gray-900">{{ __('admin.bookings') }}</h1>
            <p class="text-sm text-gray-500 mt-0.5">{{ $hotel->name }}</p>
        </div>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-2xl border border-gray-200 px-5 py-4">
            <p class="text-xs font-medium text-gray-500 mb-1">{{ __('booking.total_bookings') }}</p>
            <p class="text-2xl font-bold text-gray-900">{{ $bookings->count() }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-200 px-5 py-4">
            <p class="text-xs font-medium text-gray-500 mb-1">{{ __('booking.status_pending') }}</p>
            <p class="text-2xl font-bold text-amber-600">{{ $bookings->where('status','pending')->count() }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-200 px-5 py-4">
            <p class="text-xs font-medium text-gray-500 mb-1">{{ __('booking.status_confirmed') }}</p>
            <p class="text-2xl font-bold text-blue-600">{{ $bookings->where('status','confirmed')->count() }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-200 px-5 py-4">
            <p class="text-xs font-medium text-gray-500 mb-1">{{ __('booking.total_revenue') }}</p>
            @php
            $total = $bookings->where('payment_status','paid')->sum('total_amount');
            @endphp

            <p class="text-2xl font-bold text-green-600">
                {{ $currency->formatPrice($total) }}
            </p>
        </div>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
        @if($bookings->isEmpty())
        <div class="text-center py-20 text-gray-400">
            <svg class="w-14 h-14 mx-auto mb-4 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
            </svg>
            <p class="text-sm font-medium text-gray-500">{{ __('booking.no_bookings') }}</p>
            <p class="text-xs text-gray-400 mt-1">{{ __('booking.no_bookings_sub') }}</p>
        </div>
        @else
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="text-left px-5 py-3 text-xs font-medium text-gray-500 uppercase tracking-wide">{{ __('booking.booking_code') }}</th>
                    <th class="text-left px-5 py-3 text-xs font-medium text-gray-500 uppercase tracking-wide">{{ __('booking.guest') }}</th>
                    <th class="text-left px-5 py-3 text-xs font-medium text-gray-500 uppercase tracking-wide">{{ __('booking.check_in') }}</th>
                    <th class="text-left px-5 py-3 text-xs font-medium text-gray-500 uppercase tracking-wide">{{ __('booking.check_out') }}</th>
                    <th class="text-left px-5 py-3 text-xs font-medium text-gray-500 uppercase tracking-wide">{{ __('booking.nights') }}</th>
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
                        <p class="text-xs text-gray-400">{{ $booking->guest_phone }}</p>
                    </td>
                    <td class="px-5 py-4 text-gray-600">{{ $booking->check_in->format('d/m/Y') }}</td>
                    <td class="px-5 py-4 text-gray-600">{{ $booking->check_out->format('d/m/Y') }}</td>
                    <td class="px-5 py-4 text-gray-600">{{ $booking->nights }}</td>
                    <td class="px-5 py-4 font-semibold text-gray-900">${{ number_format($booking->total_amount) }}</td>
                    <td class="px-5 py-4">
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium
                            bg-{{ $color }}-100 text-{{ $color }}-800">
                            <span class="w-1.5 h-1.5 rounded-full bg-{{ $color }}-500"></span>
                            {{ $booking->statusLabel() }}
                        </span>
                    </td>
                    <td class="px-5 py-4">
                        @if($booking->isPending())
                        <div class="flex gap-2">
                            <form action="{{ route('owner.bookings.confirm', $booking->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="px-3 py-1.5 rounded-lg bg-green-600 hover:bg-green-700 text-white text-xs font-medium transition">
                                    {{ __('admin.approve') }}
                                </button>
                            </form>
                            <form action="{{ route('owner.bookings.cancel', $booking->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="px-3 py-1.5 rounded-lg bg-red-50 hover:bg-red-100 text-red-600 text-xs font-medium border border-red-200 transition">
                                    {{ __('admin.reject') }}
                                </button>
                            </form>
                        </div>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>
</div>
@endsection