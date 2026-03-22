@extends('layouts.app')

@section('title', 'Chi tiết đặt phòng - Tripto')

@push('styles')
<style>
    .step-line {
        flex: 1;
        height: 2px;
        background: #e5e7eb;
    }

    .step-line.done {
        background: #2563eb;
    }

    .form-input {
        width: 100%;
        border: 1px solid #d1d5db;
        border-radius: 10px;
        padding: 10px 14px;
        font-size: .875rem;
        color: #111827;
        outline: none;
        transition: border-color .2s, box-shadow .2s;
        background: #fff;
    }

    .form-input:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, .1);
    }

    .form-input::placeholder {
        color: #9ca3af;
    }

    .phone-wrap {
        display: flex;
        border: 1px solid #d1d5db;
        border-radius: 10px;
        overflow: hidden;
        transition: border-color .2s, box-shadow .2s;
    }

    .phone-wrap:focus-within {
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, .1);
    }

    .phone-flag {
        display: flex;
        align-items: center;
        gap: 6px;
        padding: 10px 12px;
        background: #f9fafb;
        border-right: 1px solid #e5e7eb;
        font-size: .8125rem;
        font-weight: 500;
        color: #374151;
        cursor: pointer;
        white-space: nowrap;
    }

    .phone-flag select {
        background: transparent;
        border: none;
        outline: none;
        font-size: .8125rem;
        font-weight: 500;
        color: #374151;
        cursor: pointer;
    }

    .phone-input {
        flex: 1;
        border: none;
        outline: none;
        padding: 10px 14px;
        font-size: .875rem;
        color: #111827;
        background: transparent;
    }

    .email-wrap {
        position: relative;
    }

    .email-icon {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #9ca3af;
    }

    .guest-block {
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        padding: 20px;
        margin-bottom: 16px;
    }

    .summary-card {
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        overflow: hidden;
        position: sticky;
        top: 80px;
    }

    .addon-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 16px 0;
        border-bottom: 1px solid #f3f4f6;
    }

    .addon-row:last-child {
        border-bottom: none;
    }

    .btn-next {
        background: #2563eb;
        color: #fff;
        font-weight: 700;
        font-size: .9375rem;
        border-radius: 9999px;
        padding: 14px 60px;
        transition: background .2s, transform .1s;
    }

    .btn-next:hover {
        background: #1d4ed8;
    }

    .btn-next:active {
        transform: scale(.98);
    }

    .scrollbar-hide {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }

    .scrollbar-hide::-webkit-scrollbar {
        display: none;
    }
</style>
@endpush

@section('content')
@php
use App\Helpers\TranslationHelper as TH;
$currency = app(\App\Services\CurrencyService::class);
$hotel = $hotel ?? null;
$checkIn = request('check_in', '2025-08-14');
$checkOut = request('check_out', '2025-08-19');
$adults = (int) request('adults', 2);
$rooms = (int) request('rooms', 1);
$children = (int) request('children', 0);

$ciObj = \Carbon\Carbon::parse($checkIn);
$coObj = \Carbon\Carbon::parse($checkOut);
$nights = max(1, $ciObj->diffInDays($coObj));

$pricePerNight = $hotel ? $hotel->price_per_night : 300;
$roomTotal = $pricePerNight * $nights * $rooms;
$serviceFee = 4.20;
$taxes = round($roomTotal * 0.0165, 2);
$grandTotal = $roomTotal + $serviceFee + $taxes;

$ratingLabel = $hotel && $hotel->rating >= 4.5 ? __('search.excellent') : __('search.very_good');
$reviewCount = $hotel->review_count ?? 1200;
@endphp
{{-- PROGRESS STEPS --}}
<div class="bg-white border-b border-gray-100">
    <div class="max-w-2xl mx-auto px-4 py-5">
        <div class="flex items-center gap-0">
            <div class="flex flex-col items-center gap-1.5 shrink-0">
                <div class="w-8 h-8 rounded-full bg-gray-800 text-white flex items-center justify-center text-sm font-bold">1</div>
                <span class="text-xs font-medium text-gray-500 whitespace-nowrap">{{ __('booking.step_selection') }}</span>
            </div>
            <div class="step-line done mx-2 mb-5"></div>
            <div class="flex flex-col items-center gap-1.5 shrink-0">
                <div class="w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center text-sm font-bold">2</div>
                <span class="text-xs font-bold text-gray-900 whitespace-nowrap">{{ __('booking.step_details') }}</span>
            </div>
            <div class="step-line mx-2 mb-5"></div>
            <div class="flex flex-col items-center gap-1.5 shrink-0">
                <div class="w-8 h-8 rounded-full border-2 border-gray-300 text-gray-400 flex items-center justify-center text-sm font-semibold">3</div>
                <span class="text-xs font-medium text-gray-400 whitespace-nowrap">{{ __('booking.finish_booking') }}</span>
            </div>
        </div>
    </div>
</div>

{{-- MAIN --}}
<div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-5xl mx-auto px-4">
        <div class="flex gap-6 items-start">

            {{-- LEFT: FORM --}}
            <div class="flex-1 min-w-0">
                <h1 class="text-2xl font-bold text-gray-900 mb-6">{{ __('booking.booking_details') }}</h1>

                {{-- YOUR TRIP --}}
                <div class="bg-white rounded-2xl border border-gray-100 p-6 mb-5">
                    <h2 class="text-base font-bold text-gray-900 mb-4">{{ __('booking.your_trip') }}</h2>
                    <div class="divide-y divide-gray-100">
                        <div class="flex items-center justify-between py-3">
                            <div>
                                <p class="text-sm font-semibold text-gray-700">{{ __('booking.dates') }}</p>
                                <p class="text-sm text-gray-500 mt-0.5">{{ $ciObj->format('d/m') }} - {{ $coObj->format('d/m/Y') }}</p>
                            </div>
                            <a href="javascript:history.back()" class="text-sm font-semibold text-blue-600 hover:underline">{{ __('booking.edit') }}</a>
                        </div>
                        <div class="flex items-center justify-between py-3">
                            <div>
                                <p class="text-sm font-semibold text-gray-700">{{ __('booking.guests') }}</p>
                                <p class="text-sm text-gray-500 mt-0.5">{{ $adults + $children }} {{ __('search.guests') }}</p>
                            </div>
                            <a href="javascript:history.back()" class="text-sm font-semibold text-blue-600 hover:underline">{{ __('booking.edit') }}</a>
                        </div>
                    </div>
                </div>

                {{-- GUEST INFO --}}
                <div class="bg-white rounded-2xl border border-gray-100 p-6 mb-5">
                    <h2 class="text-base font-bold text-gray-900 mb-1">{{ __('booking.guest_info') }}</h2>
                    <p class="text-sm text-gray-400 mb-5">{{ __('booking.guest_id_note') }}</p>

                    <form id="bookingForm" action="{{ route('booking.payment', $hotel->id ?? 0) }}" method="GET">
                        <input type="hidden" name="guest_name">
                        <input type="hidden" name="guest_email">
                        <input type="hidden" name="guest_phone">
                        <input type="hidden" name="check_in" value="{{ $checkIn }}">
                        <input type="hidden" name="check_out" value="{{ $checkOut }}">
                        <input type="hidden" name="adults" value="{{ $adults }}">
                        <input type="hidden" name="rooms" value="{{ $rooms }}">
                        <input type="hidden" name="children" value="{{ $children }}">

                        <div class="guest-block" id="guest-1-block">
                            <div class="flex items-center justify-between mb-4">
                                <p class="text-sm font-bold text-gray-800">{{ __('booking.guest_label', ['n' => 1]) }}</p>
                                <button type="button" onclick="addGuest()"
                                    class="flex items-center gap-1.5 text-sm font-semibold text-blue-600 hover:text-blue-700 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    {{ __('booking.add_guest') }} <span class="text-gray-400 font-normal">({{ __('booking.optional') }})</span>
                                </button>
                            </div>
                            <div class="grid grid-cols-2 gap-3 mb-3">
                                <div>
                                    <label class="text-xs font-semibold text-gray-500 mb-1.5 block">{{ __('booking.first_name') }}</label>
                                    <input type="text" name="guests[0][first_name]" placeholder="Nguyễn" class="form-input" required>
                                </div>
                                <div class="relative">
                                    <label class="text-xs font-semibold text-gray-500 mb-1.5 block">{{ __('booking.last_name') }}</label>
                                    <input type="text" name="guests[0][last_name]" placeholder="Văn A" class="form-input pr-10" required>
                                    <button type="button" class="absolute right-3 top-[34px] text-gray-300 hover:text-red-400 transition">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="text-xs font-semibold text-gray-500 mb-1.5 block">{{ __('booking.email_address') }}</label>
                                    <div class="relative">
                                        <input type="email" name="guests[0][email]" placeholder="email@gmail.com" class="form-input pl-9" required>
                                    </div>
                                </div>
                                <div>
                                    <label class="text-xs font-semibold text-gray-500 mb-1.5 block">{{ __('booking.phone') }}</label>
                                    <div class="phone-wrap">
                                        <div class="phone-flag">
                                            <span>🇻🇳</span>
                                            <select name="phone_code" class="max-w-[60px]">
                                                <option value="+84">+84</option>
                                                <option value="+1">+1</option>
                                                <option value="+44">+44</option>
                                                <option value="+34">+34</option>
                                                <option value="+33">+33</option>
                                                <option value="+49">+49</option>
                                                <option value="+81">+81</option>
                                                <option value="+86">+86</option>
                                            </select>
                                        </div>
                                        <input type="tel" name="guests[0][phone]" placeholder="090 123 4567" class="phone-input">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="extra-guests"></div>

                        {{-- SPECIAL REQUESTS --}}
                        <div class="bg-white rounded-2xl border border-gray-100 p-6 mb-5">
                            <h2 class="text-base font-bold text-gray-900 mb-1">
                                {{ __('booking.special_requests') }} <span class="text-gray-400 font-normal text-sm">({{ __('booking.optional') }})</span>
                            </h2>
                            <p class="text-sm text-gray-400 mb-4">{{ __('booking.special_requests_note') }}</p>
                            <textarea name="special_requests" rows="4"
                                placeholder="{{ __('booking.special_requests_ph') }}"
                                class="form-input resize-none"></textarea>
                        </div>

                        {{-- ADD-ONS --}}
                        <div class="bg-white rounded-2xl border border-gray-100 p-6 mb-5">
                            <div class="addon-row">
                                <div>
                                    <p class="text-sm font-bold text-gray-900">{{ __('booking.add_travel_insurance') }}</p>
                                    <p class="text-sm text-gray-500 mt-0.5">{{ __('booking.insurance_desc') }}</p>
                                </div>
                                <button type="button" onclick="toggleAddon(this, 'insurance')"
                                    class="text-sm font-semibold text-gray-600 border border-gray-300 hover:border-blue-500 hover:text-blue-600 px-4 py-1.5 rounded-xl transition">
                                    {{ __('booking.add_btn') }}
                                </button>
                            </div>
                            <input type="hidden" name="travel_insurance" id="insurance" value="0">

                            <div class="addon-row">
                                <div>
                                    <p class="text-sm font-bold text-gray-900">{{ __('booking.required_trip') }}</p>
                                    <p class="text-sm font-semibold text-gray-700 mt-1">{{ __('booking.phone_confirm_label') }}</p>
                                    <p class="text-sm text-gray-500">{{ __('booking.phone_confirm_desc') }}</p>
                                </div>
                                <button type="button" onclick="toggleAddon(this, 'phone_confirmed')"
                                    class="text-sm font-semibold text-gray-600 border border-gray-300 hover:border-blue-500 hover:text-blue-600 px-4 py-1.5 rounded-xl transition">
                                    {{ __('booking.add_btn') }}
                                </button>
                            </div>
                            <input type="hidden" name="phone_confirmed" id="phone_confirmed" value="0">

                            <div class="pt-4">
                                <p class="text-sm font-bold text-gray-900 mb-1">{{ __('booking.cancellation_policy') }}</p>
                                <p class="text-sm text-gray-500">
                                    @if($hotel && $hotel->free_cancellation)
                                    {{ __('search.free_cancellation') }} trước {{ $ciObj->subDays(7)->format('d/m') }}.
                                    Hủy trước ngày {{ $ciObj->subDays(3)->format('d/m') }} để được hoàn tiền một phần.
                                    @else
                                    {{ __('booking.non_refundable') }}
                                    @endif
                                </p>
                                <a href="#" class="text-sm text-blue-600 font-semibold hover:underline mt-1 inline-block">{{ __('booking.learn_more') }}</a>
                            </div>
                        </div>

                        <div class="flex justify-center pb-8">
                            <button type="submit" class="btn-next">{{ __('booking.next_step') }}</button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- RIGHT: SUMMARY --}}
            <div class="w-72 shrink-0 hidden lg:block">
                <div class="summary-card">
                    <div class="p-4 border-b border-gray-100">
                        <div class="flex gap-3">
                            <div class="w-20 h-20 rounded-xl overflow-hidden shrink-0">
                                <img src="{{ $hotel ? $hotel->image_url : 'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=300&q=80' }}"
                                    alt="{{ $hotel->name ?? 'Hotel' }}" class="w-full h-full object-cover"
                                    onerror="this.src='https://images.unsplash.com/photo-1566073771259-6a8506099945?w=300&q=80'">
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-bold text-gray-900 leading-tight mb-1">{{ $hotel->name ?? 'Khách sạn' }}</p>
                                <div class="flex items-center gap-0.5 mb-1">
                                    @for($s=0;$s<($hotel->star_rating ?? 4);$s++)
                                        <svg class="w-3 h-3 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                        @endfor
                                </div>
                                <p class="text-xs text-gray-400">{{ $hotel->city ?? 'Việt Nam' }}</p>
                                <div class="flex items-center gap-1.5 mt-1">
                                    <span class="bg-blue-600 text-white text-xs font-bold px-1.5 py-0.5 rounded-md">{{ $hotel->rating ?? '5.0' }}</span>
                                    <span class="text-xs font-semibold text-blue-600">{{ $ratingLabel }}</span>
                                    <span class="text-xs text-gray-400">{{ number_format($reviewCount) }} {{ __('search.reviews') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="p-4 border-b border-gray-100">
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <div class="flex items-center gap-1.5 text-xs text-gray-400 mb-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    {{ __('booking.check_in') }}
                                </div>
                                <p class="text-sm font-semibold text-gray-800">{{ $ciObj->format('d/m/Y') }}</p>
                            </div>
                            <div>
                                <div class="flex items-center gap-1.5 text-xs text-gray-400 mb-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    {{ __('booking.check_out') }}
                                </div>
                                <p class="text-sm font-semibold text-gray-800">{{ $coObj->format('d/m/Y') }}</p>
                            </div>
                        </div>
                        <div class="mt-3 pt-3 border-t border-gray-100">
                            <p class="text-xs text-gray-500 font-semibold mb-0.5">{{ __('booking.rooms_and_guests') }}</p>
                            <p class="text-sm text-gray-700">{{ $rooms }} {{ __('booking.room_unit') }}, {{ $adults }} {{ __('booking.adult_unit') }}{{ $children > 0 ? ', '.$children.' '.(__('booking.child_unit')) : '' }}</p>
                        </div>
                    </div>

                    <div class="p-4">
                        <p class="text-sm font-bold text-gray-900 mb-3">{{ __('booking.price_details') }}</p>
                        <div class="space-y-2 mb-3">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">{{ $currency->formatPrice($pricePerNight) }} x {{ $nights }} {{ __('search.nights_label') }}</span>
                                <span class="font-medium text-gray-800">{{ $currency->formatPrice($roomTotal) }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">{{ __('booking.service_fee') }}</span>
                                <span class="font-medium text-gray-800">{{ $currency->formatPrice($serviceFee) }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">{{ __('booking.taxes') }}</span>
                                <span class="font-medium text-gray-800">{{ $currency->formatPrice($taxes) }}</span>
                            </div>
                        </div>
                        <div class="border-t border-gray-200 pt-3 flex justify-between">
                            <span class="text-sm font-bold text-gray-900">{{ __('booking.total') }}</span>
                            <span class="text-sm font-bold text-gray-900">{{ $currency->formatPrice($grandTotal) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let guestCount = 1;

    function addGuest() {
        guestCount++;
        const container = document.getElementById('extra-guests');
        const block = document.createElement('div');
        block.className = 'guest-block';
        block.id = 'guest-' + guestCount + '-block';
        block.innerHTML = `
        <div class="flex items-center justify-between mb-4">
            <p class="text-sm font-bold text-gray-800">Khách ${guestCount}</p>
            <button type="button" onclick="removeGuest(${guestCount})"
                class="text-xs font-semibold text-red-400 hover:text-red-600 transition flex items-center gap-1">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                Xóa
            </button>
        </div>
        <div class="grid grid-cols-2 gap-3">
            <div>
                <label class="text-xs font-semibold text-gray-500 mb-1.5 block">Họ</label>
                <input type="text" name="guests[${guestCount-1}][first_name]" placeholder="Họ" class="form-input">
            </div>
            <div>
                <label class="text-xs font-semibold text-gray-500 mb-1.5 block">Tên</label>
                <input type="text" name="guests[${guestCount-1}][last_name]" placeholder="Tên" class="form-input">
            </div>
        </div>`;
        container.appendChild(block);
    }

    function removeGuest(n) {
        const block = document.getElementById('guest-' + n + '-block');
        if (block) block.remove();
        guestCount = Math.max(1, guestCount - 1);
    }

    function toggleAddon(btn, fieldId) {
        const field = document.getElementById(fieldId);
        const isActive = btn.classList.contains('active');
        if (isActive) {
            btn.textContent = 'Thêm';
            btn.classList.remove('active', 'bg-blue-600', 'text-white', 'border-blue-600');
            btn.classList.add('text-gray-600', 'border-gray-300');
            field.value = '0';
        } else {
            btn.textContent = 'Đã thêm ✓';
            btn.classList.add('active', 'bg-blue-600', 'text-white', 'border-blue-600');
            btn.classList.remove('text-gray-600', 'border-gray-300');
            field.value = '1';
        }
    }

    document.getElementById('bookingForm').addEventListener('submit', function(e) {
        const firstName = this.querySelector('[name="guests[0][first_name]"]').value.trim();
        const lastName = this.querySelector('[name="guests[0][last_name]"]').value.trim();
        const email = this.querySelector('[name="guests[0][email]"]').value.trim();
        const phone = this.querySelector('[name="guests[0][phone]"]').value.trim();

        this.elements['guest_name'].value = firstName + ' ' + lastName;
        this.elements['guest_email'].value = email;
        this.elements['guest_phone'].value = phone;
        if (!firstName || !lastName || !email) {
            e.preventDefault();
            alert('Vui lòng điền đầy đủ thông tin khách hàng bắt buộc.');
        }
    });
</script>
@endpush