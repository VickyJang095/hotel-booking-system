<section class="relative pb-20 scroll-animate fade-up zoom-in">
    <div class="absolute inset-0">
        <img src="{{ asset('images/background-hero.jpg') }}" alt="hotel background hero section" class="w-full object-cover rounded-b-[40px] h-120">
        <div class="absolute inset-0 bg-black/40 rounded-b-[40px] h-120"></div>
    </div>
    <div class="relative text-center text-white py-36 mx-auto max-w-7xl flex flex-col items-center justify-center font-roboto">
        <p class="font-bold text-2xl sm:text-4xl lg:text-5xl">{{ __('home.hero_title') }}</p>
        <p class="text-base sm:text-sm lg:text-xl py-4">{{ __('home.hero_subtitle') }}</p>

        <div class="flex space-x-4 mt-9">
            <div class="bg-gray-800/50 flex m-10 px-7 py-4 rounded-[30px] backdrop-blur-sm space-x-2">
                <button class="flex items-center space-x-2 px-4 py-2 text-white rounded-full font-medium hover:bg-white hover:text-gray-700 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21V7a2 2 0 012-2h14a2 2 0 012 2v14M9 21V12h6v9M7 9h.01M17 9h.01" />
                    </svg>
                    <span>{{ __('home.hotel') }}</span>
                </button>
                <button class="flex items-center space-x-2 px-4 py-2 text-white rounded-full font-medium hover:bg-white hover:text-gray-700 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l9-7 9 7M5 10v10a1 1 0 001 1h3m6 0h3a1 1 0 001-1V10" />
                    </svg>
                    <span>{{ __('home.house') }}</span>
                </button>
                <button class="flex items-center space-x-2 px-4 py-2 text-white rounded-full font-medium hover:bg-white hover:text-gray-700 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5V8H2v12h5m10 0v-5a2 2 0 00-2-2H9a2 2 0 00-2 2v5m10 0H7" />
                    </svg>
                    <span>{{ __('home.guest_house') }}</span>
                </button>
                <button class="flex items-center space-x-2 px-4 py-2 text-white rounded-full font-medium hover:bg-white hover:text-gray-700 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21h18M5 21V9l7-4 7 4v12M9 21v-6h6v6" />
                    </svg>
                    <span>{{ __('home.cabins') }}</span>
                </button>
                <button class="flex items-center space-x-2 px-4 py-2 text-white rounded-full font-medium hover:bg-white hover:text-gray-700 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21l9-18 9 18M6 18h12" />
                    </svg>
                    <span>{{ __('home.glamping') }}</span>
                </button>
                <button class="flex items-center space-x-2 px-4 py-2 text-white rounded-full font-medium hover:bg-white hover:text-gray-700 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20v-2a4 4 0 00-3-3.87M7 20v-2a4 4 0 013-3.87M12 7a4 4 0 110 8 4 4 0 010-8z" />
                    </svg>
                    <span>{{ __('home.dorms') }}</span>
                </button>
            </div>
        </div>

        {{-- Search Card --}}
        <div class="max-w-400 mx-auto px-4 -mt-15">
            <form action="{{ route('hotels.search') }}" method="GET">
                <div class="bg-white rounded-4xl p-10 py-12 search-card shadow-lg">
                    <div class="flex space-x-3 pt-3">

                        {{-- Location --}}
                        <div class="pr-12 border-r border-gray-400">
                            <label class="block text-[18px] font-semibold text-gray-700 text-start px-4">
                                {{ __('home.search_location_label') }}
                            </label>
                            <input name="location" type="text" id="location"
                                placeholder="{{ __('home.search_location') }}"
                                class="w-full text-[16px] px-4 py-3 border-none text-gray-500">
                        </div>

                        {{-- Check In --}}
                        <div class="border-r border-gray-400 pr-6">
                            <label class="block text-[18px] font-semibold text-gray-700 text-start px-4">
                                {{ __('home.check_in') }}
                            </label>
                            <input type="date" name="check_in" id="checkin"
                                class="w-full px-4 py-3 border-none text-gray-500 text-[16px]">
                        </div>

                        {{-- Check Out --}}
                        <div class="border-r border-gray-400 pr-6">
                            <label class="block text-[18px] font-semibold text-gray-700 text-start px-4">
                                {{ __('home.check_out') }}
                            </label>
                            <input type="date" name="check_out" id="checkout"
                                class="w-full px-4 py-3 border-none text-gray-500 text-[16px]">
                        </div>

                        {{-- Rooms and Guests --}}
                        <div class="pr-12">
                            <label class="block text-[18px] font-semibold text-gray-700 text-start px-4">
                                {{ __('home.rooms_guests') }}
                            </label>
                            <button type="button" onclick="toggleGuests()" class="w-full px-4 py-3 rounded-xl border text-left">
                                <span id="guestText" class="border-none text-gray-500 text-[16px]">
                                    {{ __('home.default_guests') }}
                                </span>
                            </button>
                            <div id="guestBox" class="hidden absolute bg-white shadow-lg text-gray-700 rounded-xl p-4 mt-2 w-64 z-10">
                                <div class="flex justify-between">
                                    <span>{{ __('home.rooms') }}</span>
                                    <div>
                                        <button type="button" onclick="change('rooms',-1)">-</button>
                                        <span id="rooms">1</span>
                                        <button type="button" onclick="change('rooms',1)">+</button>
                                    </div>
                                </div>
                                <div class="flex justify-between mt-2">
                                    <span>{{ __('home.adults') }}</span>
                                    <div>
                                        <button type="button" onclick="change('adults',-1)">-</button>
                                        <span id="adults">1</span>
                                        <button type="button" onclick="change('adults',1)">+</button>
                                    </div>
                                </div>
                                <div class="flex justify-between mt-2">
                                    <span>{{ __('home.children') }}</span>
                                    <div>
                                        <button type="button" onclick="change('children',-1)">-</button>
                                        <span id="children">0</span>
                                        <button type="button" onclick="change('children',1)">+</button>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="rooms" id="roomsInput" value="1">
                            <input type="hidden" name="adults" id="adultsInput" value="1">
                            <input type="hidden" name="children" id="childrenInput" value="0">
                        </div>

                        {{-- Search Button --}}
                        <div class="flex justify-end m-3">
                            <button type="submit" class="flex items-center space-x-2 px-4 bg-blue-600 text-white text-[18px] rounded-xl font-semibold hover:bg-blue-700 transition">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                <span>{{ __('home.search_btn') }}</span>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>

<div id="hero-trans"
    data-fill-all="{{ __('home.fill_all_fields') }}"
    data-checkout-after="{{ __('home.checkout_after_checkin') }}"
    data-room="{{ __('home.room') }}"
    data-rooms="{{ __('home.rooms_plural') }}"
    data-adult="{{ __('home.adult') }}"
    data-adults="{{ __('home.adults_plural') }}"
    data-children="{{ __('home.children') }}"
    style="display:none">
</div>

<script>
    let data = {
        rooms: 1,
        adults: 1,
        children: 0
    };

    const t = document.getElementById('hero-trans').dataset;
    const trans = {
        room: t.room,
        rooms: t.rooms,
        adult: t.adult,
        adults: t.adults,
        children: t.children,
        fillAll: t.fillAll,
        checkoutAfter: t.checkoutAfter,
    };

    function toggleGuests() {
        document.getElementById('guestBox').classList.toggle('hidden');
    }

    function change(type, value) {
        const min = (type === 'children') ? 0 : 1;
        data[type] = Math.max(min, data[type] + value);
        document.getElementById(type).innerText = data[type];
        document.getElementById(type + 'Input').value = data[type];
        updateGuestText();
    }

    function updateGuestText() {
        const r = data.rooms,
            a = data.adults,
            c = data.children;
        document.getElementById('guestText').innerText =
            `${r} ${r > 1 ? trans.rooms : trans.room}, ${a} ${a > 1 ? trans.adults : trans.adult}, ${c} ${trans.children}`;
    }

    document.addEventListener('click', function(e) {
        const box = document.getElementById('guestBox');
        const wrapper = box?.closest('.relative');
        if (wrapper && !wrapper.contains(e.target)) box.classList.add('hidden');
    });

    document.querySelector('form').addEventListener('submit', function(e) {
        const location = document.getElementById('location').value.trim();
        const checkin = document.getElementById('checkin').value;
        const checkout = document.getElementById('checkout').value;
        if (!location || !checkin || !checkout) {
            e.preventDefault();
            alert(trans.fillAll);
            return;
        }
        if (checkout <= checkin) {
            e.preventDefault();
            alert(trans.checkoutAfter);
        }
    });
</script>