@extends('layouts.app')

@section('title', 'Finish Booking - Tripto')

@push('styles')
<style>
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

    /* Pay option radio */
    .pay-option {
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        padding: 16px 18px;
        cursor: pointer;
        transition: all .2s;
    }

    .pay-option.selected {
        border-color: #2563eb;
        background: #eff6ff;
    }

    .pay-option input[type=radio] {
        accent-color: #2563eb;
        width: 18px;
        height: 18px;
        cursor: pointer;
    }

    /* Payment method pill */
    .pm-pill {
        border: 2px solid #e5e7eb;
        border-radius: 10px;
        padding: 8px 14px;
        cursor: pointer;
        transition: all .2s;
        display: flex;
        align-items: center;
        justify-content: center;
        min-width: 64px;
        height: 40px;
        font-weight: 700;
        font-size: .875rem;
    }

    .pm-pill.active {
        border-color: #2563eb;
        background: #eff6ff;
    }

    .pm-pill:hover {
        border-color: #93c5fd;
    }

    /* Card number input with icon */
    .card-input-wrap {
        position: relative;
    }

    .card-input-wrap .card-icons {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        display: flex;
        align-items: center;
        gap: 4px;
    }

    /* Checkbox */
    input[type=checkbox] {
        accent-color: #2563eb;
        width: 16px;
        height: 16px;
        cursor: pointer;
    }

    /* Confirm btn */
    .btn-confirm {
        background: #2563eb;
        color: #fff;
        font-weight: 700;
        font-size: 1rem;
        border-radius: 9999px;
        padding: 14px 64px;
        transition: background .2s, transform .1s;
    }

    .btn-confirm:hover {
        background: #1d4ed8;
    }

    .btn-confirm:active {
        transform: scale(.98);
    }

    /* Summary card */
    .summary-card {
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        overflow: hidden;
        position: sticky;
        top: 24px;
    }

    /* Step line */
    .step-line {
        flex: 1;
        height: 2px;
        background: #e5e7eb;
    }

    .step-line.done {
        background: #2563eb;
    }

    /* Footer */
    footer a {
        color: #9ca3af;
        font-size: .8125rem;
        transition: color .15s;
    }

    footer a:hover {
        color: #fff;
    }
</style>
@endpush

@section('content')
@php
$checkIn = request('check_in', '2025-08-14');
$checkOut = request('check_out', '2025-08-19');
$adults = (int) request('adults', 2);
$rooms = (int) request('rooms', 1);
$children = (int) request('children', 0);

$ciObj = \Carbon\Carbon::parse($checkIn);
$coObj = \Carbon\Carbon::parse($checkOut);
$nights = max(1, $ciObj->diffInDays($coObj));

$pricePerNight = $hotel->price_per_night ?? 300;
$roomTotal = $pricePerNight * $nights * $rooms;
$serviceFee = 4.20;
$taxes = round($roomTotal * 0.0165, 2);
$grandTotal = $roomTotal + $serviceFee + $taxes;
$halfTotal = round($grandTotal / 2, 2);
$installment = round($grandTotal / 3, 2);
$payLaterDate = $ciObj->copy()->subDays(10)->format('F d');

$ratingLabel = ($hotel->rating ?? 5) >= 4.5 ? 'Excellent' : 'Very Good';
@endphp

{{-- PROGRESS STEPS --}}
<div class="bg-white border-b border-gray-100">
    <div class="max-w-2xl mx-auto px-4 py-5">
        <div class="flex items-center gap-0">
            <div class="flex flex-col items-center gap-1.5 shrink-0">
                <div class="w-8 h-8 rounded-full bg-gray-700 text-white flex items-center justify-center text-sm font-bold">1</div>
                <span class="text-xs font-medium text-gray-400 whitespace-nowrap">Your selection</span>
            </div>
            <div class="step-line done mx-2 mb-5"></div>
            <div class="flex flex-col items-center gap-1.5 shrink-0">
                <div class="w-8 h-8 rounded-full bg-gray-700 text-white flex items-center justify-center text-sm font-bold">2</div>
                <span class="text-xs font-medium text-gray-400 whitespace-nowrap">Your details</span>
            </div>
            <div class="step-line done mx-2 mb-5"></div>
            <div class="flex flex-col items-center gap-1.5 shrink-0">
                <div class="w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center text-sm font-bold">3</div>
                <span class="text-xs font-bold text-gray-900 whitespace-nowrap">Finish booking</span>
            </div>
        </div>
    </div>
</div>

{{-- MAIN --}}
<div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-5xl mx-auto px-4">
        <div class="flex gap-6 items-start">

            {{-- LEFT COLUMN --}}
            <div class="flex-1 min-w-0">

                {{-- CHOOSE WHEN TO PAY --}}
                <div class="bg-white rounded-2xl border border-gray-100 p-6 mb-5">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Choose when to pay</h2>

                    <div class="space-y-3">

                        {{-- Option 1: Pay now --}}
                        <label class="pay-option selected flex items-start gap-3 cursor-pointer" id="opt-now-label">
                            <input type="radio" name="pay_when" value="now" checked
                                onchange="selectPayOption('now')" class="mt-0.5 shrink-0">
                            <div>
                                <p class="text-sm font-bold text-gray-900">Pay ${{ number_format($grandTotal, 0) }} now</p>
                            </div>
                        </label>

                        {{-- Option 2: Part now part later --}}
                        <label class="pay-option flex items-start gap-3 cursor-pointer" id="opt-split-label">
                            <input type="radio" name="pay_when" value="split"
                                onchange="selectPayOption('split')" class="mt-0.5 shrink-0">
                            <div>
                                <p class="text-sm font-bold text-gray-900">Pay part now and part later</p>
                                <p class="text-sm text-gray-500 mt-0.5">
                                    you will pay ${{ number_format($halfTotal, 0) }} now and ${{ number_format($grandTotal - $halfTotal, 0) }} on {{ $payLaterDate }}.
                                    No additional costs.
                                    <a href="#" class="text-blue-600 font-semibold hover:underline">Learn more</a>
                                </p>
                            </div>
                        </label>

                        {{-- Option 3: Klarna --}}
                        <label class="pay-option flex items-start gap-3 cursor-pointer" id="opt-klarna-label">
                            <input type="radio" name="pay_when" value="klarna"
                                onchange="selectPayOption('klarna')" class="mt-0.5 shrink-0">
                            <div>
                                <p class="text-sm font-bold text-gray-900">Pay in 3 installments with Klarna</p>
                                <p class="text-sm text-gray-500 mt-0.5">
                                    Pay in 3 installments of ${{ number_format($installment, 2) }} without interest.
                                    <a href="#" class="text-blue-600 font-semibold hover:underline">Learn more</a>
                                </p>
                            </div>
                        </label>

                    </div>
                </div>

                {{-- COMPLETE REGISTRATION PAYMENT --}}
                <div class="bg-white rounded-2xl border border-gray-100 p-6 mb-5">
                    <h2 class="text-xl font-bold text-gray-900 mb-5">Complete registration payment</h2>

                    {{-- Personal details --}}
                    <h3 class="text-sm font-bold text-gray-800 mb-3">Personal details</h3>
                    <div class="grid grid-cols-2 gap-3 mb-3">
                        <div>
                            <label class="text-xs font-semibold text-gray-500 mb-1.5 block">Address line</label>
                            <input type="text" name="address" placeholder="P.O.Box 1223" class="form-input">
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-gray-500 mb-1.5 block">City</label>
                            <input type="text" name="city" placeholder="Arusha" class="form-input">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3 mb-6">
                        <div>
                            <label class="text-xs font-semibold text-gray-500 mb-1.5 block">Postal code</label>
                            <input type="text" name="postal_code" placeholder="9090" class="form-input">
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-gray-500 mb-1.5 block">State</label>
                            <input type="text" name="state" placeholder="Arusha, Tanzania" class="form-input">
                        </div>
                    </div>

                    {{-- Payment methods --}}
                    <h3 class="text-sm font-bold text-gray-800 mb-3">Payment methods</h3>
                    <div class="flex gap-2 flex-wrap mb-6">
                        <button type="button" onclick="selectPM(this,'visa')"
                            class="pm-pill active" data-pm="visa">
                            <span class="font-black text-blue-700 text-base tracking-tight">VISA</span>
                        </button>
                        <button type="button" onclick="selectPM(this,'diners')"
                            class="pm-pill" data-pm="diners">
                            <svg class="w-8 h-5" viewBox="0 0 48 30" fill="none">
                                <circle cx="17" cy="15" r="13" fill="#004A97" />
                                <circle cx="31" cy="15" r="13" fill="#F7A600" fill-opacity=".9" />
                                <path d="M24 5.4A13 13 0 0124 24.6 13 13 0 0124 5.4z" fill="#7B4F2E" />
                            </svg>
                        </button>
                        <button type="button" onclick="selectPM(this,'mastercard')"
                            class="pm-pill" data-pm="mastercard">
                            <svg class="w-9 h-6" viewBox="0 0 56 36" fill="none">
                                <circle cx="21" cy="18" r="13" fill="#EB001B" />
                                <circle cx="35" cy="18" r="13" fill="#F79E1B" />
                                <path d="M28 7.3a13 13 0 010 21.4A13 13 0 0128 7.3z" fill="#FF5F00" />
                            </svg>
                        </button>
                        <button type="button" onclick="selectPM(this,'stripe')"
                            class="pm-pill" data-pm="stripe">
                            <span class="font-black text-indigo-600 text-base tracking-widest">stripe</span>
                        </button>
                        <button type="button" onclick="selectPM(this,'paypal')"
                            class="pm-pill" data-pm="paypal">
                            <span class="font-black text-[#003087] text-base">Pay<span class="text-[#009cde]">Pal</span></span>
                        </button>
                        <button type="button" onclick="selectPM(this,'gpay')"
                            class="pm-pill" data-pm="gpay">
                            <span class="font-medium text-gray-700 text-sm">G <span class="text-blue-500">P</span><span class="text-red-500">a</span><span class="text-yellow-500">y</span></span>
                        </button>
                        <button type="button" onclick="selectPM(this,'applepay')"
                            class="pm-pill" data-pm="applepay">
                            <svg class="w-8 h-5" viewBox="0 0 50 30" fill="currentColor">
                                <path d="M16.5 6.2c-.9 1-2.3 1.8-3.7 1.7-.2-1.4.5-2.9 1.3-3.8.9-1 2.4-1.8 3.7-1.8.2 1.5-.4 2.9-1.3 3.9zm1.3 2c-2-.1-3.8 1.1-4.7 1.1-.9 0-2.4-1-4-1-2 0-3.9 1.2-5 3-.2.3-1.8 3.1-.5 7.4.8 2.6 2 5.3 3.5 5.3 1.3 0 1.8-.8 3.4-.8s2 .8 3.4.8c1.5 0 2.6-2.4 3.5-4.9-1.5-.7-2.6-2.3-2.6-4.1 0-1.6.8-3 2-3.8-.7-1-1.8-1.6-3-1z" />
                            </svg>
                        </button>
                    </div>
                    <input type="hidden" name="payment_method" id="paymentMethodInput" value="visa">

                    {{-- Card details --}}
                    <h3 class="text-sm font-bold text-gray-800 mb-3">Card details</h3>
                    <div class="space-y-3" id="cardDetails">
                        <div>
                            <label class="text-xs font-semibold text-gray-500 mb-1.5 block">
                                Cardholder's name <span class="text-red-400">*</span>
                            </label>
                            <input type="text" name="cardholder_name" placeholder="As printed on card" class="form-input">
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-gray-500 mb-1.5 block">
                                Card number <span class="text-red-400">*</span>
                            </label>
                            <div class="card-input-wrap">
                                <input type="text" name="card_number" placeholder="Seen on your card"
                                    maxlength="19" oninput="formatCard(this)"
                                    class="form-input pr-20">
                                <div class="card-icons">
                                    <svg class="w-8 h-5" viewBox="0 0 56 36" fill="none">
                                        <circle cx="21" cy="18" r="13" fill="#EB001B" />
                                        <circle cx="35" cy="18" r="13" fill="#F79E1B" />
                                        <path d="M28 7.3a13 13 0 010 21.4A13 13 0 0128 7.3z" fill="#FF5F00" />
                                    </svg>
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="text-xs font-semibold text-gray-500 mb-1.5 block">
                                    Expiry <span class="text-red-400">*</span>
                                </label>
                                <input type="text" name="expiry" placeholder="MM/YY"
                                    maxlength="5" oninput="formatExpiry(this)"
                                    class="form-input">
                            </div>
                            <div>
                                <label class="text-xs font-semibold text-gray-500 mb-1.5 block">
                                    CVC <span class="text-red-400">*</span>
                                </label>
                                <input type="text" name="cvc" placeholder="CVC"
                                    maxlength="4" oninput="this.value=this.value.replace(/\D/g,'')"
                                    class="form-input">
                            </div>
                        </div>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="save_card" value="1" checked>
                            <span class="text-sm text-gray-700 font-medium">Save Card</span>
                        </label>
                    </div>
                </div>

                {{-- CONFIRM BUTTON --}}
                <div class="flex justify-center pb-8">
                    <button type="button" onclick="confirmPayment()"
                        class="btn-confirm">
                        Confirm and pay
                    </button>
                </div>

            </div>{{-- /left --}}

            {{-- RIGHT: SUMMARY --}}
            <div class="w-72 shrink-0 hidden lg:block">
                <div class="summary-card">

                    {{-- Hotel info --}}
                    <div class="p-4 border-b border-gray-100">
                        <div class="flex gap-3">
                            <div class="w-20 h-20 rounded-xl overflow-hidden shrink-0">
                                <img src="{{ $hotel->image_url ?? 'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=300&q=80' }}"
                                    alt="{{ $hotel->name ?? 'Hotel' }}"
                                    class="w-full h-full object-cover"
                                    onerror="this.src='https://images.unsplash.com/photo-1566073771259-6a8506099945?w=300&q=80'">
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-bold text-gray-900 leading-tight mb-1">{{ $hotel->name ?? 'Hotel Arts Barcelona' }}</p>
                                <div class="flex items-center gap-0.5 mb-1">
                                    @for($s = 0; $s < ($hotel->star_rating ?? 4); $s++)
                                        <svg class="w-3 h-3 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                        @endfor
                                </div>
                                <p class="text-xs text-gray-400">{{ $hotel->city ?? 'Barcelona' }}, Spain</p>
                                <div class="flex items-center gap-1.5 mt-1">
                                    <span class="bg-blue-600 text-white text-xs font-bold px-1.5 py-0.5 rounded-md">{{ $hotel->rating ?? '5.0' }}</span>
                                    <span class="text-xs font-semibold text-blue-600">{{ $ratingLabel }}</span>
                                    <span class="text-xs text-gray-400">{{ number_format($hotel->review_count ?? 1260) }} reviews</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Check-in/out --}}
                    <div class="p-4 border-b border-gray-100">
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <div class="flex items-center gap-1 text-xs text-gray-400 mb-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    Check-in
                                </div>
                                <p class="text-sm font-semibold text-gray-800">{{ $ciObj->format('m/d/Y') }}</p>
                            </div>
                            <div>
                                <div class="flex items-center gap-1 text-xs text-gray-400 mb-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    Check-out
                                </div>
                                <p class="text-sm font-semibold text-gray-800">{{ $coObj->format('m/d/Y') }}</p>
                            </div>
                        </div>
                        <div class="mt-3 pt-3 border-t border-gray-100">
                            <p class="text-xs font-semibold text-gray-500 mb-0.5">Rooms and Guests</p>
                            <p class="text-sm text-gray-700">{{ $rooms }} room{{ $rooms > 1 ? 's' : '' }}, {{ $adults }} adult{{ $adults > 1 ? 's' : '' }}</p>
                        </div>
                    </div>

                    {{-- Price --}}
                    <div class="p-4">
                        <p class="text-sm font-bold text-gray-900 mb-3">Price details:</p>
                        <div class="space-y-2 mb-3">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">${{ number_format($pricePerNight, 0) }} x {{ $nights }} nights</span>
                                <span class="font-medium text-gray-800">${{ number_format($roomTotal, 2) }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Tripto service fee</span>
                                <span class="font-medium text-gray-800">${{ number_format($serviceFee, 2) }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Taxes</span>
                                <span class="font-medium text-gray-800">${{ number_format($taxes, 2) }}</span>
                            </div>
                        </div>
                        <div class="border-t border-gray-200 pt-3 flex justify-between">
                            <span class="text-sm font-bold text-gray-900">Total USD</span>
                            <span class="text-sm font-bold text-gray-900">$ {{ number_format($grandTotal, 0) }}</span>
                        </div>
                    </div>

                </div>
            </div>{{-- /right --}}

        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Pay option toggle
    function selectPayOption(val) {
        document.querySelectorAll('.pay-option').forEach(el => el.classList.remove('selected'));
        const map = {
            now: 'opt-now-label',
            split: 'opt-split-label',
            klarna: 'opt-klarna-label'
        };
        document.getElementById(map[val])?.classList.add('selected');
    }

    // Payment method toggle
    function selectPM(btn, pm) {
        document.querySelectorAll('.pm-pill').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        document.getElementById('paymentMethodInput').value = pm;

        // Show/hide card details for non-card methods
        const cardMethods = ['visa', 'mastercard', 'diners'];
        document.getElementById('cardDetails').style.display = cardMethods.includes(pm) ? '' : 'none';
    }

    // Card number formatter
    function formatCard(input) {
        let v = input.value.replace(/\D/g, '').substring(0, 16);
        input.value = v.replace(/(.{4})/g, '$1 ').trim();
    }

    // Expiry formatter
    function formatExpiry(input) {
        let v = input.value.replace(/\D/g, '').substring(0, 4);
        if (v.length >= 2) v = v.substring(0, 2) + '/' + v.substring(2);
        input.value = v;
    }

    // Confirm payment
    function confirmPayment() {
        const cardHolder = document.querySelector('[name="cardholder_name"]').value.trim();
        const cardNum = document.querySelector('[name="card_number"]').value.trim();
        const expiry = document.querySelector('[name="expiry"]').value.trim();
        const cvc = document.querySelector('[name="cvc"]').value.trim();

        const pm = document.getElementById('paymentMethodInput').value;
        const cardMethods = ['visa', 'mastercard', 'diners'];

        if (cardMethods.includes(pm) && (!cardHolder || !cardNum || !expiry || !cvc)) {
            alert('Please fill in all card details.');
            return;
        }

        // Show success state
        const btn = event.target;
        btn.textContent = 'Processing...';
        btn.disabled = true;
        btn.classList.add('opacity-75');

        setTimeout(() => {
            btn.textContent = '✓ Booking Confirmed!';
            btn.classList.remove('opacity-75');
            btn.classList.add('bg-green-600');
            btn.classList.remove('bg-blue-600');
        }, 1500);
    }
</script>
@endpush