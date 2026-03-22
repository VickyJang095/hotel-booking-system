@extends('layouts.app')

@section('title', __('profile.title') . ' - Tripto')

@section('content')
@php $currency = app(\App\Services\CurrencyService::class); @endphp

<div class="bg-gray-50 min-h-screen py-10">
    <div class="max-w-4xl mx-auto px-4">

        {{-- Header --}}
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900">{{ __('profile.title') }}</h1>
            <p class="text-sm text-gray-500 mt-1">{{ __('profile.subtitle') }}</p>
        </div>

        <div class="flex gap-6 items-start">

            {{-- LEFT: Avatar + Nav --}}
            <div class="w-56 shrink-0">
                {{-- Avatar --}}
                <div class="bg-white rounded-2xl border border-gray-100 p-5 text-center mb-4">
                    <div class="relative inline-block mb-3">
                        @if($user->avatar)
                        <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}"
                            class="w-20 h-20 rounded-full object-cover border-4 border-white shadow">
                        @else
                        <div class="w-20 h-20 rounded-full bg-blue-100 flex items-center justify-center border-4 border-white shadow">
                            <span class="text-2xl font-bold text-blue-600">
                                {{ strtoupper(substr($user->first_name ?? $user->name ?? 'U', 0, 1)) }}
                            </span>
                        </div>
                        @endif
                    </div>
                    <p class="font-semibold text-gray-900 text-sm">{{ $user->name ?? __('profile.no_name') }}</p>
                    <p class="text-xs text-gray-400 truncate">{{ $user->email }}</p>
                </div>

                {{-- Nav --}}
                <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
                    @php
                    $navItems = [
                        ['tab' => 'personal', 'label' => __('profile.tab_personal'), 'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
                        ['tab' => 'payment',  'label' => __('profile.tab_payment'),  'icon' => 'M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z'],
                        ['tab' => 'trips',    'label' => __('profile.tab_trips'),    'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
                    ];
                    @endphp
                    @foreach($navItems as $item)
                    <button type="button" onclick="switchProfileTab('{{ $item['tab'] }}')"
                        id="nav-{{ $item['tab'] }}"
                        class="flex items-center gap-3 w-full px-4 py-3 text-sm font-medium transition border-b border-gray-50 last:border-0
                            {{ $loop->first ? 'text-blue-600 bg-blue-50' : 'text-gray-600 hover:bg-gray-50' }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}"/>
                        </svg>
                        {{ $item['label'] }}
                    </button>
                    @endforeach
                </div>
            </div>

            {{-- RIGHT: Content --}}
            <div class="flex-1 min-w-0">

                @if(session('success'))
                <div class="mb-4 flex items-center gap-2 bg-green-50 border border-green-200 text-green-800 rounded-xl px-4 py-3 text-sm">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    {{ session('success') }}
                </div>
                @endif

                {{-- TAB: Personal --}}
                <div id="tab-personal" class="profile-tab">
                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf @method('PUT')

                        {{-- Avatar upload --}}
                        <div class="bg-white rounded-2xl border border-gray-100 p-6 mb-4">
                            <h2 class="text-sm font-bold text-gray-900 mb-4">{{ __('profile.avatar') }}</h2>
                            <div class="flex items-center gap-4">
                                @if($user->avatar)
                                <img src="{{ asset('storage/' . $user->avatar) }}" class="w-16 h-16 rounded-full object-cover">
                                @else
                                <div class="w-16 h-16 rounded-full bg-blue-100 flex items-center justify-center">
                                    <span class="text-xl font-bold text-blue-600">{{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}</span>
                                </div>
                                @endif
                                <div>
                                    <label class="cursor-pointer inline-flex items-center gap-2 px-4 py-2 rounded-xl border border-gray-300 text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                                        </svg>
                                        {{ __('profile.change_avatar') }}
                                        <input type="file" name="avatar" accept="image/*" class="hidden" onchange="previewAvatar(this)">
                                    </label>
                                    <p class="text-xs text-gray-400 mt-1">JPG, PNG tối đa 2MB</p>
                                </div>
                            </div>
                        </div>

                        {{-- Basic info --}}
                        <div class="bg-white rounded-2xl border border-gray-100 p-6 mb-4">
                            <h2 class="text-sm font-bold text-gray-900 mb-4">Thông tin cơ bản</h2>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="text-xs font-semibold text-gray-500 mb-1.5 block">{{ __('profile.first_name') }}</label>
                                    <input type="text" name="first_name" value="{{ old('first_name', $user->first_name) }}"
                                        placeholder="Nguyễn"
                                        class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                                </div>
                                <div>
                                    <label class="text-xs font-semibold text-gray-500 mb-1.5 block">{{ __('profile.last_name') }}</label>
                                    <input type="text" name="last_name" value="{{ old('last_name', $user->last_name) }}"
                                        placeholder="Văn A"
                                        class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                                </div>
                                <div>
                                    <label class="text-xs font-semibold text-gray-500 mb-1.5 block">{{ __('profile.email') }}</label>
                                    <input type="email" value="{{ $user->email }}" disabled
                                        class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-400 cursor-not-allowed">
                                </div>
                                <div>
                                    <label class="text-xs font-semibold text-gray-500 mb-1.5 block">{{ __('profile.phone') }}</label>
                                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                                        placeholder="0901 234 567"
                                        class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                                </div>
                                <div>
                                    <label class="text-xs font-semibold text-gray-500 mb-1.5 block">{{ __('profile.date_of_birth') }}</label>
                                    <input type="date" name="date_of_birth" value="{{ old('date_of_birth', $user->date_of_birth?->format('Y-m-d')) }}"
                                        class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                                </div>
                                <div>
                                    <label class="text-xs font-semibold text-gray-500 mb-1.5 block">{{ __('profile.gender') }}</label>
                                    <select name="gender" class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                                        <option value="">-- Chọn --</option>
                                        <option value="male"   {{ old('gender', $user->gender) === 'male'   ? 'selected' : '' }}>{{ __('profile.gender_male') }}</option>
                                        <option value="female" {{ old('gender', $user->gender) === 'female' ? 'selected' : '' }}>{{ __('profile.gender_female') }}</option>
                                        <option value="other"  {{ old('gender', $user->gender) === 'other'  ? 'selected' : '' }}>{{ __('profile.gender_other') }}</option>
                                    </select>
                                </div>
                                <div class="col-span-2">
                                    <label class="text-xs font-semibold text-gray-500 mb-1.5 block">{{ __('profile.address') }}</label>
                                    <input type="text" name="address" value="{{ old('address', $user->address) }}"
                                        placeholder="Số nhà, tên đường..."
                                        class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                                </div>
                                <div>
                                    <label class="text-xs font-semibold text-gray-500 mb-1.5 block">{{ __('profile.city') }}</label>
                                    <input type="text" name="city" value="{{ old('city', $user->city) }}"
                                        placeholder="Hà Nội"
                                        class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="w-full py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm transition">
                            {{ __('profile.save_changes') }}
                        </button>
                    </form>
                </div>

                {{-- TAB: Payment --}}
                <div id="tab-payment" class="profile-tab hidden">
                    {{-- Saved card --}}
                    @if($user->card_number_masked)
                    <div class="bg-gradient-to-br from-blue-600 to-blue-800 rounded-2xl p-5 mb-4 text-white">
                        <div class="flex items-center justify-between mb-6">
                            <p class="text-sm font-semibold opacity-80">{{ __('profile.saved_card') }}</p>
                            <span class="text-xs font-bold uppercase bg-white/20 px-2 py-1 rounded-lg">{{ $user->card_type ?? 'card' }}</span>
                        </div>
                        <p class="text-xl font-mono tracking-widest mb-4">{{ $user->card_number_masked }}</p>
                        <div class="flex justify-between items-end">
                            <div>
                                <p class="text-xs opacity-60 mb-0.5">Chủ thẻ</p>
                                <p class="font-semibold text-sm">{{ $user->card_holder_name ?? $user->name }}</p>
                            </div>
                            <div>
                                <p class="text-xs opacity-60 mb-0.5">Hết hạn</p>
                                <p class="font-semibold text-sm">{{ $user->card_expiry }}</p>
                            </div>
                        </div>
                    </div>
                    @endif

                    <form action="{{ route('profile.payment') }}" method="POST">
                        @csrf @method('PUT')
                        <div class="bg-white rounded-2xl border border-gray-100 p-6 mb-4">
                            <h2 class="text-sm font-bold text-gray-900 mb-1">{{ __('profile.payment_title') }}</h2>
                            <p class="text-xs text-gray-400 mb-4">{{ __('profile.payment_sub') }}</p>

                            <div class="grid grid-cols-2 gap-4">
                                <div class="col-span-2">
                                    <label class="text-xs font-semibold text-gray-500 mb-1.5 block">{{ __('profile.card_holder') }}</label>
                                    <input type="text" name="card_holder_name" value="{{ old('card_holder_name', $user->card_holder_name) }}"
                                        placeholder="NGUYEN VAN A"
                                        class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                                </div>
                                <div class="col-span-2">
                                    <label class="text-xs font-semibold text-gray-500 mb-1.5 block">{{ __('profile.card_number') }}</label>
                                    <input type="text" name="card_number"
                                        placeholder="{{ __('profile.card_number_ph') }}"
                                        maxlength="19" oninput="formatCardInput(this)"
                                        class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100 font-mono tracking-widest">
                                    @if($user->card_number_masked)
                                    <p class="text-xs text-gray-400 mt-1">Thẻ hiện tại: {{ $user->card_number_masked }} — điền mới để thay đổi</p>
                                    @endif
                                </div>
                                <div>
                                    <label class="text-xs font-semibold text-gray-500 mb-1.5 block">{{ __('profile.card_expiry') }}</label>
                                    <input type="text" name="card_expiry" value="{{ old('card_expiry', $user->card_expiry) }}"
                                        placeholder="MM/YY" maxlength="5" oninput="formatExpiryInput(this)"
                                        class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                                </div>
                                <div>
                                    <label class="text-xs font-semibold text-gray-500 mb-1.5 block">{{ __('profile.card_type') }}</label>
                                    <select name="card_type" class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                                        <option value="">-- Chọn --</option>
                                        @foreach(['visa'=>'Visa','mastercard'=>'Mastercard','amex'=>'Amex','jcb'=>'JCB'] as $val => $label)
                                        <option value="{{ $val }}" {{ old('card_type', $user->card_type) === $val ? 'selected' : '' }}>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-2xl border border-gray-100 p-6 mb-4">
                            <h2 class="text-sm font-bold text-gray-900 mb-4">Địa chỉ thanh toán</h2>
                            <div class="grid grid-cols-2 gap-4">
                                <div class="col-span-2">
                                    <label class="text-xs font-semibold text-gray-500 mb-1.5 block">{{ __('profile.billing_address') }}</label>
                                    <input type="text" name="billing_address" value="{{ old('billing_address', $user->billing_address) }}"
                                        placeholder="Số nhà, tên đường..."
                                        class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                                </div>
                                <div>
                                    <label class="text-xs font-semibold text-gray-500 mb-1.5 block">{{ __('profile.billing_city') }}</label>
                                    <input type="text" name="billing_city" value="{{ old('billing_city', $user->billing_city) }}"
                                        placeholder="Hà Nội"
                                        class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                                </div>
                                <div>
                                    <label class="text-xs font-semibold text-gray-500 mb-1.5 block">{{ __('profile.billing_postal') }}</label>
                                    <input type="text" name="billing_postal_code" value="{{ old('billing_postal_code', $user->billing_postal_code) }}"
                                        placeholder="100000"
                                        class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="w-full py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm transition">
                            {{ __('profile.save_payment') }}
                        </button>
                    </form>
                </div>

                {{-- TAB: Trips --}}
                <div id="tab-trips" class="profile-tab hidden">
                    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100">
                            <h2 class="text-sm font-bold text-gray-900">{{ __('profile.trips_title') }}</h2>
                            <p class="text-xs text-gray-400 mt-0.5">{{ __('profile.trips_sub') }}</p>
                        </div>

                        @if(!isset($bookings) || $bookings->isEmpty())
                        <div class="text-center py-16 text-gray-400">
                            <svg class="w-14 h-14 mx-auto mb-4 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                            <p class="text-sm font-medium text-gray-500 mb-1">{{ __('profile.no_trips') }}</p>
                            <p class="text-xs text-gray-400 mb-4">{{ __('profile.no_trips_sub') }}</p>
                            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-blue-600 text-white text-sm font-medium hover:bg-blue-700 transition">
                                Tìm khách sạn
                            </a>
                        </div>
                        @else
                        <div class="divide-y divide-gray-50">
                            @foreach($bookings as $booking)
                            @php $color = $booking->statusColor(); @endphp
                            <div class="flex gap-4 px-6 py-4 hover:bg-gray-50 transition">
                                <div class="w-20 h-20 rounded-xl overflow-hidden shrink-0">
                                    <img src="{{ $booking->hotel->image_url ?? '' }}" alt="{{ $booking->hotel->name }}"
                                        class="w-full h-full object-cover"
                                        onerror="this.src='https://images.unsplash.com/photo-1566073771259-6a8506099945?w=200&q=80'">
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-start justify-between gap-2">
                                        <div>
                                            <p class="font-semibold text-gray-900 text-sm truncate">{{ $booking->hotel->name ?? '—' }}</p>
                                            <p class="text-xs text-gray-400 mt-0.5 font-mono">{{ $booking->booking_code }}</p>
                                        </div>
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium shrink-0
                                            bg-{{ $color }}-100 text-{{ $color }}-800">
                                            <span class="w-1.5 h-1.5 rounded-full bg-{{ $color }}-500"></span>
                                            {{ $booking->statusLabel() }}
                                        </span>
                                    </div>
                                    <div class="flex items-center gap-4 mt-2 text-xs text-gray-500">
                                        <span class="flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                            {{ $booking->check_in->format('d/m/Y') }} → {{ $booking->check_out->format('d/m/Y') }}
                                        </span>
                                        <span>{{ $booking->nights }} {{ __('profile.nights') }}</span>
                                    </div>
                                    <div class="flex items-center justify-between mt-2">
                                        <p class="text-sm font-bold text-gray-900">
                                            {{ $currency->formatPrice($booking->total_amount) }}
                                        </p>
                                        <div class="flex gap-2">
                                            <a href="#" class="text-xs text-blue-600 font-medium hover:underline">{{ __('profile.view_detail') }}</a>
                                            @if($booking->hotel)
                                            <a href="{{ route('hotels.show', $booking->hotel->id) }}" class="text-xs text-gray-500 hover:text-blue-600 transition">{{ __('profile.book_again') }}</a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @if(isset($bookings) && $bookings->hasPages())
                        <div class="px-6 py-4 border-t border-gray-100">{{ $bookings->links() }}</div>
                        @endif
                        @endif
                    </div>
                </div>

            </div>{{-- /right --}}
        </div>
    </div>
</div>

<script>
function switchProfileTab(tab) {
    // Hide all tabs
    document.querySelectorAll('.profile-tab').forEach(el => el.classList.add('hidden'));
    // Reset nav
    document.querySelectorAll('[id^="nav-"]').forEach(el => {
        el.classList.remove('text-blue-600', 'bg-blue-50');
        el.classList.add('text-gray-600');
    });
    // Show selected
    document.getElementById('tab-' + tab).classList.remove('hidden');
    const nav = document.getElementById('nav-' + tab);
    if (nav) {
        nav.classList.add('text-blue-600', 'bg-blue-50');
        nav.classList.remove('text-gray-600');
    }
}

function previewAvatar(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            document.querySelectorAll('img[alt="{{ $user->name }}"], .w-20.h-20.rounded-full').forEach(img => {
                if (img.tagName === 'IMG') img.src = e.target.result;
            });
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function formatCardInput(input) {
    let v = input.value.replace(/\D/g, '').substring(0, 16);
    input.value = v.replace(/(.{4})/g, '$1 ').trim();
}

function formatExpiryInput(input) {
    let v = input.value.replace(/\D/g, '').substring(0, 4);
    if (v.length >= 2) v = v.substring(0, 2) + '/' + v.substring(2);
    input.value = v;
}

// Auto switch tab from URL hash
const hash = window.location.hash.replace('#', '');
if (['personal','payment','trips'].includes(hash)) switchProfileTab(hash);
</script>
@endsection