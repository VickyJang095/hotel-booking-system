@extends('layouts.panel')
@section('title', __('admin.edit_hotel'))
@section('page-title', __('admin.edit_hotel'))
@section('page-subtitle', 'Cập nhật thông tin khách sạn')

@section('content')
<div class="max-w-3xl">
    <form method="POST" action="{{ route('owner.hotel.update') }}" enctype="multipart/form-data" class="space-y-5">
        @csrf @method('PUT')

        @if(session('success'))
        <div class="bg-green-50 border border-green-200 rounded-xl p-4 text-green-700 text-sm font-medium flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            {{ session('success') }}
        </div>
        @endif

        @if($errors->any())
        <div class="bg-red-50 border border-red-200 rounded-xl p-4">
            <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $e)<li class="text-sm text-red-600">{{ $e }}</li>@endforeach
            </ul>
        </div>
        @endif

        <div class="bg-white rounded-2xl border border-gray-100 p-6">
            <h3 class="text-sm font-bold text-gray-800 mb-4">Thông tin cơ bản</h3>
            <div class="grid grid-cols-2 gap-4">
                <div class="col-span-2">
                    <label class="text-xs font-semibold text-gray-500 mb-1.5 block">{{ __('admin.hotel_name') }} *</label>
                    <input type="text" name="name" value="{{ old('name', $hotel->name) }}" class="form-input" required>
                </div>
                <div class="col-span-2">
                    <label class="text-xs font-semibold text-gray-500 mb-1.5 block">{{ __('admin.address') }}</label>
                    <input type="text" name="address" value="{{ old('address', $hotel->address) }}" class="form-input">
                </div>
                <div class="col-span-2">
                    <label class="text-xs font-semibold text-gray-500 mb-1.5 block">{{ __('admin.description') }}</label>
                    <textarea name="description" rows="3" class="form-input resize-none">{{ old('description', $hotel->description) }}</textarea>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 p-6">
            <h3 class="text-sm font-bold text-gray-800 mb-4">Giá & Phòng</h3>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-xs font-semibold text-gray-500 mb-1.5 block">Giá mỗi đêm ($) *</label>
                    <input type="number" name="price_per_night" step="0.01" min="0"
                           value="{{ old('price_per_night', $hotel->price_per_night) }}" class="form-input" required>
                </div>
                <div>
                    <label class="text-xs font-semibold text-gray-500 mb-1.5 block">{{ __('admin.stars') }} *</label>
                    <select name="star_rating" class="form-input" required>
                        @for($s=1;$s<=5;$s++)
                        <option value="{{ $s }}" {{ old('star_rating', $hotel->star_rating) == $s ? 'selected' : '' }}>{{ $s }} sao</option>
                        @endfor
                    </select>
                </div>
                <div>
                    <label class="text-xs font-semibold text-gray-500 mb-1.5 block">{{ __('admin.total_rooms') }} *</label>
                    <input type="number" name="total_rooms" min="1" value="{{ old('total_rooms', $hotel->total_rooms) }}" class="form-input" required>
                </div>
                <div>
                    <label class="text-xs font-semibold text-gray-500 mb-1.5 block">Khách tối đa/phòng *</label>
                    <input type="number" name="max_guests_per_room" min="1" value="{{ old('max_guests_per_room', $hotel->max_guests_per_room) }}" class="form-input" required>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 p-6">
            <h3 class="text-sm font-bold text-gray-800 mb-4">{{ __('search.amenities') }}</h3>
            @php
            $allAmenities = ['Wi-Fi','Pool','Gym','Restaurant','Spa access','Free Parking','Air Conditioning','TV','Mini Bar','Room Service','Breakfast','Concierge','Laundry','Pet Friendly'];
            $selected = old('amenities', is_array($hotel->amenities) ? $hotel->amenities : (json_decode($hotel->amenities ?? '[]', true) ?? []));
            @endphp
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                @foreach($allAmenities as $am)
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="amenities[]" value="{{ $am }}" {{ in_array($am, $selected) ? 'checked' : '' }} class="w-4 h-4 accent-blue-600">
                    <span class="text-sm text-gray-700">{{ $am }}</span>
                </label>
                @endforeach
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 p-6">
            <h3 class="text-sm font-bold text-gray-800 mb-4">Hình ảnh</h3>
            <div class="mb-3">
                <img src="{{ $hotel->image_url }}" alt="Hiện tại" class="h-32 rounded-xl object-cover" onerror="this.style.display='none'">
            </div>
            <div class="space-y-3">
                <div>
                    <label class="text-xs font-semibold text-gray-500 mb-1.5 block">Tải ảnh mới lên</label>
                    <input type="file" name="image_file" accept="image/*" class="form-input">
                </div>
                <div>
                    <label class="text-xs font-semibold text-gray-500 mb-1.5 block">Hoặc nhập URL ảnh</label>
                    <input type="url" name="image_url" value="{{ old('image_url', str_starts_with($hotel->image ?? '', 'http') ? $hotel->image : '') }}" class="form-input" placeholder="https://...">
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 p-6">
            <h3 class="text-sm font-bold text-gray-800 mb-4">Tùy chọn</h3>
            <div class="flex flex-wrap gap-5">
                @foreach(['free_cancellation'=>'Miễn phí hủy phòng','instant_booking'=>'Đặt phòng ngay','pay_at_property'=>'Thanh toán tại chỗ'] as $field => $label)
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="{{ $field }}" value="1" {{ old($field, $hotel->$field ?? false) ? 'checked' : '' }} class="w-4 h-4 accent-blue-600">
                    <span class="text-sm text-gray-700 font-medium">{{ $label }}</span>
                </label>
                @endforeach
            </div>
        </div>

        <button type="submit" class="btn-primary px-8">{{ __('common.save') }}</button>
    </form>
</div>
@endsection