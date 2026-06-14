@extends('layouts.panel')
@section('title', $room ? 'Chỉnh sửa phòng' : 'Thêm phòng')

@section('content')
<div class="p-6 max-w-2xl">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('owner.rooms') }}" class="p-2 rounded-xl hover:bg-gray-100 text-gray-500 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </a>
        <div>
            <h1 class="text-xl font-semibold text-gray-900">{{ $room ? 'Chỉnh sửa phòng' : 'Thêm phòng mới' }}</h1>
            <p class="text-sm text-gray-500">{{ $hotel->name }}</p>
        </div>
    </div>

    @if($errors->any())
    <div class="mb-5 bg-red-50 border border-red-200 rounded-xl px-4 py-3">
        <ul class="text-sm text-red-700 space-y-1">
            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
    </div>
    @endif

    <div class="bg-white rounded-2xl border border-gray-200 p-6">
        <form action="{{ $room ? route('owner.rooms.update', $room->id) : route('owner.rooms.store') }}"
            method="POST" class="space-y-5">
            @csrf
            @if($room) @method('PUT') @endif

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Tên phòng <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name', $room?->name) }}"
                    placeholder="VD: Phòng Deluxe Đôi"
                    class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100 @error('name') border-red-400 @enderror">
                @error('name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Loại phòng</label>
                    <select name="type" class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                        <option value="">Chọn loại</option>
                        @foreach(['Standard'=>'Tiêu chuẩn','Deluxe'=>'Deluxe','Superior'=>'Superior','Suite'=>'Suite','Family'=>'Gia đình','Twin'=>'Twin','Single'=>'Đơn'] as $val => $label)
                        <option value="{{ $val }}" {{ old('type', $room?->type) === $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Số lượng <span class="text-red-500">*</span></label>
                    <input type="number" name="quantity" value="{{ old('quantity', $room?->quantity ?? 1) }}" min="1"
                        class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Giá mỗi đêm (USD) <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">$</span>
                        <input type="number" name="price_per_night" value="{{ old('price_per_night', $room?->price_per_night) }}"
                            min="0" step="0.01" placeholder="0.00"
                            class="w-full rounded-xl border border-gray-300 pl-7 pr-4 py-2.5 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100 @error('price_per_night') border-red-400 @enderror">
                    </div>
                    @error('price_per_night') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Số khách tối đa <span class="text-red-500">*</span></label>
                    <input type="number" name="max_guests" value="{{ old('max_guests', $room?->max_guests ?? 2) }}" min="1" max="10"
                        class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100 @error('max_guests') border-red-400 @enderror">
                    @error('max_guests') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Loại giường</label>
                <div class="flex flex-wrap gap-2">
                    @foreach(['Single bed'=>'Giường đơn','Double bed'=>'Giường đôi','Queen bed'=>'Giường Queen','King bed'=>'Giường King','Twin beds'=>'2 giường đơn','Bunk bed'=>'Giường tầng'] as $val => $label)
                    <label class="cursor-pointer">
                        <input type="radio" name="bed_type" value="{{ $val }}" {{ old('bed_type', $room?->bed_type) === $val ? 'checked' : '' }} class="sr-only peer">
                        <span class="inline-block px-3 py-1.5 rounded-lg border border-gray-300 text-sm text-gray-600 peer-checked:border-blue-500 peer-checked:bg-blue-50 peer-checked:text-blue-700 transition">
                            {{ $label }}
                        </span>
                    </label>
                    @endforeach
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Mô tả</label>
                <textarea name="description" rows="3"
                    placeholder="Đặc điểm phòng, tầm nhìn, tiện nghi..."
                    class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100 resize-none">{{ old('description', $room?->description) }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tiện nghi phòng</label>
                <div class="grid grid-cols-2 gap-2">
                    @foreach([
                    'Air conditioning'=>'Điều hòa','Private bathroom'=>'Phòng tắm riêng',
                    'Free WiFi'=>'WiFi miễn phí','Flat-screen TV'=>'TV màn hình phẳng',
                    'Mini bar'=>'Mini bar','Safe box'=>'Két an toàn',
                    'Balcony'=>'Ban công','Sea view'=>'View biển',
                    'Bathtub'=>'Bồn tắm','Hair dryer'=>'Máy sấy tóc',
                    'Work desk'=>'Bàn làm việc','Coffee maker'=>'Máy pha cà phê',
                    ] as $val => $label)
                    <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer">
                        <input type="checkbox" name="amenities[]" value="{{ $val }}"
                            {{ in_array($val, old('amenities', $room?->amenities ?? [])) ? 'checked' : '' }}
                            class="w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        {{ $label }}
                    </label>
                    @endforeach
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">URL ảnh phòng</label>
                <input type="url" name="image" value="{{ old('image', $room?->image) }}"
                    placeholder="https://..."
                    class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
            </div>

            <div class="flex gap-3 pt-2">
                <a href="{{ route('owner.rooms') }}"
                    class="px-5 py-2.5 rounded-xl border border-gray-300 text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
                    {{ __('common.cancel') }}
                </a>
                <button type="submit"
                    class="px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium transition">
                    {{ $room ? __('common.save') : 'Thêm phòng' }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection