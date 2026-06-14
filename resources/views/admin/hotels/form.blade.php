@extends('layouts.panel')
@section('title', isset($hotel) ? 'Edit Hotel' : 'Add Hotel')
@section('page-title', isset($hotel) ? 'Edit Hotel' : 'Add Hotel')
@section('page-subtitle', isset($hotel) ? 'Update hotel information' : 'Add a new hotel to the platform')

@section('content')
<div class="max-w-3xl">
    <form method="POST"
          action="{{ isset($hotel) ? route('admin.hotels.update', $hotel->id) : route('admin.hotels.store') }}"
          enctype="multipart/form-data"
          class="space-y-5">
        @csrf
        @if(isset($hotel)) @method('PUT') @endif

        @if($errors->any())
        <div class="bg-red-50 border border-red-200 rounded-xl p-4">
            <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $e)
                <li class="text-sm text-red-600">{{ $e }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        {{-- Basic Info --}}
        <div class="bg-white rounded-2xl border border-gray-100 p-6">
            <h3 class="text-sm font-bold text-gray-800 mb-4">Basic Information</h3>
            <div class="grid grid-cols-2 gap-4">
                <div class="col-span-2">
                    <label class="text-xs font-semibold text-gray-500 mb-1.5 block">Hotel Name *</label>
                    <input type="text" name="name" value="{{ old('name', $hotel->name ?? '') }}"
                           class="form-input" required placeholder="e.g. Grand Hyatt Hanoi">
                </div>
                <div>
                    <label class="text-xs font-semibold text-gray-500 mb-1.5 block">City *</label>
                    <input type="text" name="city" value="{{ old('city', $hotel->city ?? '') }}"
                           class="form-input" required placeholder="Hanoi">
                </div>
                <div>
                    <label class="text-xs font-semibold text-gray-500 mb-1.5 block">Type</label>
                    <select name="type" class="form-input">
                        @foreach(['Hotel','Resort','Villa','Apartment','Hostel','Guesthouse'] as $t)
                        <option value="{{ $t }}" {{ old('type', $hotel->type ?? 'Hotel') === $t ? 'selected' : '' }}>{{ $t }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-span-2">
                    <label class="text-xs font-semibold text-gray-500 mb-1.5 block">Address</label>
                    <input type="text" name="address" value="{{ old('address', $hotel->address ?? '') }}"
                           class="form-input" placeholder="123 Street, District">
                </div>
                <div class="col-span-2">
                    <label class="text-xs font-semibold text-gray-500 mb-1.5 block">Description</label>
                    <textarea name="description" rows="3" class="form-input resize-none"
                              placeholder="Hotel description...">{{ old('description', $hotel->description ?? '') }}</textarea>
                </div>
            </div>
        </div>

        {{-- Pricing & Rooms --}}
        <div class="bg-white rounded-2xl border border-gray-100 p-6">
            <h3 class="text-sm font-bold text-gray-800 mb-4">Pricing & Rooms</h3>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-xs font-semibold text-gray-500 mb-1.5 block">Price per Night ($) *</label>
                    <input type="number" name="price_per_night" step="0.01" min="0"
                           value="{{ old('price_per_night', $hotel->price_per_night ?? '') }}"
                           class="form-input" required>
                </div>
                <div>
                    <label class="text-xs font-semibold text-gray-500 mb-1.5 block">Star Rating *</label>
                    <select name="star_rating" class="form-input" required>
                        @for($s=1;$s<=5;$s++)
                        <option value="{{ $s }}" {{ old('star_rating', $hotel->star_rating ?? 3) == $s ? 'selected' : '' }}>{{ $s }} Star{{ $s>1?'s':'' }}</option>
                        @endfor
                    </select>
                </div>
                <div>
                    <label class="text-xs font-semibold text-gray-500 mb-1.5 block">Rating (0-5)</label>
                    <input type="number" name="rating" step="0.1" min="0" max="5"
                           value="{{ old('rating', $hotel->rating ?? '') }}"
                           class="form-input" placeholder="4.5">
                </div>
                <div>
                    <label class="text-xs font-semibold text-gray-500 mb-1.5 block">Review Count</label>
                    <input type="number" name="review_count" min="0"
                           value="{{ old('review_count', $hotel->review_count ?? 0) }}"
                           class="form-input">
                </div>
                <div>
                    <label class="text-xs font-semibold text-gray-500 mb-1.5 block">Total Rooms *</label>
                    <input type="number" name="total_rooms" min="1"
                           value="{{ old('total_rooms', $hotel->total_rooms ?? '') }}"
                           class="form-input" required>
                </div>
                <div>
                    <label class="text-xs font-semibold text-gray-500 mb-1.5 block">Max Guests/Room *</label>
                    <input type="number" name="max_guests_per_room" min="1"
                           value="{{ old('max_guests_per_room', $hotel->max_guests_per_room ?? 2) }}"
                           class="form-input" required>
                </div>
            </div>
        </div>

        {{-- Amenities --}}
        <div class="bg-white rounded-2xl border border-gray-100 p-6">
            <h3 class="text-sm font-bold text-gray-800 mb-4">Amenities</h3>
            @php
            $allAmenities = ['Wi-Fi','Pool','Gym','Restaurant','Spa access','Free Parking','Air Conditioning','TV','Mini Bar','Room Service','Breakfast','Concierge','Laundry','Pet Friendly'];
            $selected = old('amenities', is_array($hotel->amenities ?? null) ? $hotel->amenities : (json_decode($hotel->amenities ?? '[]', true) ?? []));
            @endphp
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                @foreach($allAmenities as $am)
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="amenities[]" value="{{ $am }}"
                           {{ in_array($am, $selected) ? 'checked' : '' }}
                           class="w-4 h-4 accent-blue-600">
                    <span class="text-sm text-gray-700">{{ $am }}</span>
                </label>
                @endforeach
            </div>
        </div>

        {{-- Location --}}
        <div class="bg-white rounded-2xl border border-gray-100 p-6">
            <h3 class="text-sm font-bold text-gray-800 mb-4">Location</h3>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-xs font-semibold text-gray-500 mb-1.5 block">Latitude</label>
                    <input type="number" name="latitude" step="any"
                           value="{{ old('latitude', $hotel->latitude ?? '') }}"
                           class="form-input" placeholder="21.0285">
                </div>
                <div>
                    <label class="text-xs font-semibold text-gray-500 mb-1.5 block">Longitude</label>
                    <input type="number" name="longitude" step="any"
                           value="{{ old('longitude', $hotel->longitude ?? '') }}"
                           class="form-input" placeholder="105.8542">
                </div>
                <div>
                    <label class="text-xs font-semibold text-gray-500 mb-1.5 block">Distance from Centre (km)</label>
                    <input type="number" name="distance_from_centre" step="0.1" min="0"
                           value="{{ old('distance_from_centre', $hotel->distance_from_centre ?? '') }}"
                           class="form-input" placeholder="1.5">
                </div>
            </div>
        </div>

        {{-- Image --}}
        <div class="bg-white rounded-2xl border border-gray-100 p-6">
            <h3 class="text-sm font-bold text-gray-800 mb-4">Image</h3>
            @if(isset($hotel) && $hotel->image)
            <div class="mb-3">
                <img src="{{ $hotel->image_url }}" alt="Current image"
                     class="h-32 rounded-xl object-cover"
                     onerror="this.style.display='none'">
            </div>
            @endif
            <div class="space-y-3">
                <div>
                    <label class="text-xs font-semibold text-gray-500 mb-1.5 block">Upload Image</label>
                    <input type="file" name="image_file" accept="image/*" class="form-input">
                </div>
                <div>
                    <label class="text-xs font-semibold text-gray-500 mb-1.5 block">Or Image URL</label>
                    <input type="url" name="image_url"
                           value="{{ old('image_url', str_starts_with($hotel->image ?? '', 'http') ? $hotel->image : '') }}"
                           class="form-input" placeholder="https://images.unsplash.com/...">
                </div>
            </div>
        </div>

        {{-- Options --}}
        <div class="bg-white rounded-2xl border border-gray-100 p-6">
            <h3 class="text-sm font-bold text-gray-800 mb-4">Options</h3>
            <div class="flex flex-wrap gap-5">
                @foreach(['free_cancellation'=>'Free Cancellation','instant_booking'=>'Instant Booking','pay_at_property'=>'Pay at Property','wheelchair_accessible'=>'Wheelchair Accessible'] as $field => $label)
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="{{ $field }}" value="1"
                           {{ old($field, $hotel->$field ?? false) ? 'checked' : '' }}
                           class="w-4 h-4 accent-blue-600">
                    <span class="text-sm text-gray-700 font-medium">{{ $label }}</span>
                </label>
                @endforeach
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex gap-3">
            <button type="submit" class="btn-primary px-8">
                {{ isset($hotel) ? 'Update Hotel' : 'Create Hotel' }}
            </button>
            <a href="{{ route('admin.hotels') }}" class="btn-edit px-6">Cancel</a>
        </div>
    </form>
</div>
@endsection