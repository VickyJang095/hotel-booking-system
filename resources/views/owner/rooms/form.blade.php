@extends('layouts.panel')

@section('title', $room ? 'Edit Room' : 'Add Room')

@section('content')
<div class="p-6 max-w-2xl">

    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('owner.rooms') }}"
            class="p-2 rounded-xl hover:bg-gray-100 text-gray-500 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </a>
        <div>
            <h1 class="text-xl font-semibold text-gray-900">{{ $room ? 'Edit Room' : 'Add New Room' }}</h1>
            <p class="text-sm text-gray-500">{{ $hotel->name }}</p>
        </div>
    </div>

    @if($errors->any())
    <div class="mb-5 bg-red-50 border border-red-200 rounded-xl px-4 py-3">
        <ul class="text-sm text-red-700 space-y-1">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="bg-white rounded-2xl border border-gray-200 p-6">
        <form action="{{ $room ? route('owner.rooms.update', $room->id) : route('owner.rooms.store') }}"
            method="POST" class="space-y-5">
            @csrf
            @if($room) @method('PUT') @endif

            {{-- Room name --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                    Room name <span class="text-red-500">*</span>
                </label>
                <input type="text" name="name" value="{{ old('name', $room?->name) }}"
                    placeholder="e.g. Deluxe Double Room"
                    class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100 @error('name') border-red-400 @enderror">
                @error('name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                {{-- Type --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Room type</label>
                    <select name="type" class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                        <option value="">Select type</option>
                        @foreach(['Standard', 'Deluxe', 'Superior', 'Suite', 'Family', 'Twin', 'Single'] as $type)
                        <option value="{{ $type }}" {{ old('type', $room?->type) === $type ? 'selected' : '' }}>
                            {{ $type }}
                        </option>
                        @endforeach
                    </select>
                </div>

                {{-- Quantity --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        Quantity <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="quantity" value="{{ old('quantity', $room?->quantity ?? 1) }}"
                        min="1" class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                {{-- Price --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        Price per night (USD) <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">$</span>
                        <input type="number" name="price_per_night" value="{{ old('price_per_night', $room?->price_per_night) }}"
                            min="0" step="0.01" placeholder="0.00"
                            class="w-full rounded-xl border border-gray-300 pl-7 pr-4 py-2.5 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100 @error('price_per_night') border-red-400 @enderror">
                    </div>
                    @error('price_per_night') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                {{-- Max guests --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        Max guests <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="max_guests" value="{{ old('max_guests', $room?->max_guests ?? 2) }}"
                        min="1" max="10"
                        class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100 @error('max_guests') border-red-400 @enderror">
                    @error('max_guests') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Bed type --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Bed type</label>
                <div class="flex flex-wrap gap-2">
                    @foreach(['Single bed', 'Double bed', 'Queen bed', 'King bed', 'Twin beds', 'Bunk bed'] as $bed)
                    <label class="cursor-pointer">
                        <input type="radio" name="bed_type" value="{{ $bed }}"
                            {{ old('bed_type', $room?->bed_type) === $bed ? 'checked' : '' }} class="sr-only peer">
                        <span class="inline-block px-3 py-1.5 rounded-lg border border-gray-300 text-sm text-gray-600
                            peer-checked:border-blue-500 peer-checked:bg-blue-50 peer-checked:text-blue-700 transition">
                            {{ $bed }}
                        </span>
                    </label>
                    @endforeach
                </div>
            </div>

            {{-- Description --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Description</label>
                <textarea name="description" rows="3"
                    placeholder="Room features, view, amenities..."
                    class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100 resize-none">{{ old('description', $room?->description) }}</textarea>
            </div>

            {{-- Amenities --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Room amenities</label>
                <div class="grid grid-cols-2 gap-2">
                    @foreach([
                    'Air conditioning', 'Private bathroom', 'Free WiFi', 'Flat-screen TV',
                    'Mini bar', 'Safe box', 'Balcony', 'Sea view',
                    'Bathtub', 'Hair dryer', 'Work desk', 'Coffee maker'
                    ] as $amenity)
                    <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer">
                        <input type="checkbox" name="amenities[]" value="{{ $amenity }}"
                            {{ in_array($amenity, old('amenities', $room?->amenities ?? [])) ? 'checked' : '' }}
                            class="w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        {{ $amenity }}
                    </label>
                    @endforeach
                </div>
            </div>

            {{-- Image --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Room image URL</label>
                <input type="url" name="image" value="{{ old('image', $room?->image) }}"
                    placeholder="https://..."
                    class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
            </div>

            {{-- Submit --}}
            <div class="flex gap-3 pt-2">
                <a href="{{ route('owner.rooms') }}"
                    class="px-5 py-2.5 rounded-xl border border-gray-300 text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
                    Cancel
                </a>
                <button type="submit"
                    class="px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium transition">
                    {{ $room ? 'Save Changes' : 'Add Room' }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection