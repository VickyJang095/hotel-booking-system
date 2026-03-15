@extends('layouts.app')

@section('title', 'Become a Hotel Owner')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-2xl mx-auto px-4">

        {{-- Header --}}
        <div class="text-center mb-10">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-blue-600 mb-4">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-gray-900">Become a Hotel Owner</h1>
            <p class="text-gray-500 mt-2">List your property on Tripto and reach millions of travelers</p>
        </div>

        {{-- Alert --}}
        @if(session('success'))
        <div class="mb-6 flex items-start gap-3 bg-green-50 border border-green-200 text-green-800 rounded-xl px-4 py-3">
            <svg class="w-5 h-5 mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            </svg>
            <p class="text-sm font-medium">{{ session('success') }}</p>
        </div>
        @endif

        @if(session('error'))
        <div class="mb-6 flex items-start gap-3 bg-red-50 border border-red-200 text-red-800 rounded-xl px-4 py-3">
            <svg class="w-5 h-5 mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm-1-9v4a1 1 0 102 0V9a1 1 0 10-2 0zm0-4a1 1 0 112 0 1 1 0 01-2 0z" clip-rule="evenodd" />
            </svg>
            <p class="text-sm font-medium">{{ session('error') }}</p>
        </div>
        @endif

        {{-- Pending state --}}
        @if($existingRequest)
        <div class="bg-white rounded-2xl border border-amber-200 p-8 text-center">
            <div class="inline-flex items-center justify-center w-14 h-14 rounded-full bg-amber-100 mb-4">
                <svg class="w-7 h-7 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <h2 class="text-xl font-semibold text-gray-900 mb-2">Application Under Review</h2>
            <p class="text-gray-500 text-sm mb-4">Your application for <strong>{{ $existingRequest->hotel_name }}</strong> is being reviewed. We'll notify you within 1-3 business days.</p>
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-sm font-medium bg-amber-100 text-amber-800">
                <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                Pending review
            </span>
        </div>

        @else
        {{-- Form --}}
        <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
            {{-- Benefits bar --}}
            <div class="bg-blue-600 px-6 py-4">
                <div class="flex items-center justify-around text-white text-sm">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Free to list
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Millions of travelers
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        24/7 support
                    </div>
                </div>
            </div>

            <form action="{{ route('owner-request.store') }}" method="POST" class="p-8 space-y-5">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    {{-- Hotel name --}}
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Hotel / Property name <span class="text-red-500">*</span></label>
                        <input type="text" name="hotel_name" value="{{ old('hotel_name') }}"
                            placeholder="e.g. Grand Sunrise Hotel"
                            class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100 @error('hotel_name') border-red-400 @enderror">
                        @error('hotel_name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    {{-- Phone --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Phone number <span class="text-red-500">*</span></label>
                        <input type="text" name="phone" value="{{ old('phone') }}"
                            placeholder="0901 234 567"
                            class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100 @error('phone') border-red-400 @enderror">
                        @error('phone') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    {{-- City --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">City <span class="text-red-500">*</span></label>
                        <input type="text" name="city" value="{{ old('city') }}"
                            placeholder="e.g. Hà Nội"
                            class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100 @error('city') border-red-400 @enderror">
                        @error('city') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    {{-- Address --}}
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Address</label>
                        <input type="text" name="address" value="{{ old('address') }}"
                            placeholder="Street address"
                            class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                    </div>

                    {{-- Description --}}
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Tell us about your property</label>
                        <textarea name="description" rows="4"
                            placeholder="Describe your property, its amenities, unique features..."
                            class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100 resize-none">{{ old('description') }}</textarea>
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit"
                        class="w-full rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 text-sm transition">
                        Submit Application
                    </button>
                    <p class="text-center text-xs text-gray-400 mt-3">By submitting, you agree to our Terms of Service and Partner Agreement.</p>
                </div>
            </form>
        </div>
        @endif

    </div>
</div>
@endsection