@extends('layouts.panel')
@section('title', 'Bookings')
@section('page-title', 'Bookings')

@section('content')
<div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
    @if($bookings->isEmpty())
    <div class="text-center py-16">
        <svg class="w-14 h-14 text-gray-200 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
        </svg>
        <p class="text-gray-400 font-medium">No bookings yet.</p>
        <p class="text-gray-300 text-sm mt-1">Bookings will appear here once guests reserve your hotel.</p>
    </div>
    @else
    <table class="w-full text-sm">
        <thead>
            <tr class="border-b border-gray-100 bg-gray-50">
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Guest</th>
                <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Check-in</th>
                <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Check-out</th>
                <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Guests</th>
                <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Total</th>
                <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Status</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @foreach($bookings as $booking)
            <tr class="table-row">
                <td class="px-5 py-3 font-semibold text-gray-900">{{ $booking->guest_name ?? '—' }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $booking->check_in }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $booking->check_out }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $booking->adults ?? 1 }}</td>
                <td class="px-4 py-3 font-semibold text-gray-900">${{ number_format($booking->total_price ?? 0) }}</td>
                <td class="px-4 py-3">
                    <span class="badge {{ $booking->status === 'confirmed' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                        {{ ucfirst($booking->status ?? 'pending') }}
                    </span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
</div>
@endsection