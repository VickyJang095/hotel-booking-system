@extends('layouts.panel')

@section('title', 'Bookings')

@section('content')
<div class="p-6">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-xl font-semibold text-gray-900">Bookings</h1>
            <p class="text-sm text-gray-500 mt-0.5">All bookings across all hotels</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
        <div class="text-center py-20 text-gray-400">
            <svg class="w-14 h-14 mx-auto mb-4 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
            </svg>
            <p class="text-sm font-medium text-gray-500">Bookings module coming soon</p>
            <p class="text-xs text-gray-400 mt-1">Cần tạo Booking model và migration trước</p>
        </div>
    </div>
</div>
@endsection