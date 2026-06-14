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
                        <div class="pr-12 border-r border-gray-400 relative">
                            <label class="block text-[18px] font-semibold text-gray-700 text-start px-4">
                                {{ __('home.search_location_label') }}
                            </label>
                            {{-- hidden input submit giá trị normalize --}}
                            <input type="hidden" name="location" id="homeLocationNormalized">
                            <input type="text" id="homeLocationInput" autocomplete="off"
                                placeholder="{{ __('home.search_location') }}"
                                class="w-full text-[16px] px-4 py-3 border-none text-gray-500 outline-none bg-transparent">

                            {{-- Dropdown --}}
                            <div id="homeLocationSuggestions"
                                class="hidden absolute top-full left-0 mt-1 w-80 bg-white rounded-2xl shadow-xl border border-gray-100 z-[9999] overflow-hidden">
                                <div id="homePopularList"></div>
                                <div id="homeSearchList" class="hidden"></div>
                                <div id="homeLocationLoading" class="hidden px-4 py-4 text-center">
                                    <div class="inline-flex items-center gap-2 text-sm text-gray-400">
                                        <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                                        </svg>
                                        ({{ __('home.loading') }})
                                    </div>
                                </div>
                            </div>
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
        const location = document.getElementById('homeLocationNormalized    ').value.trim();
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
    // ===== HOME LOCATION AUTOCOMPLETE =====
    (function() {
        const input = document.getElementById('homeLocationInput');
        const norm = document.getElementById('homeLocationNormalized');
        const dropdown = document.getElementById('homeLocationSuggestions');
        const popularEl = document.getElementById('homePopularList');
        const searchEl = document.getElementById('homeSearchList');
        const loadingEl = document.getElementById('homeLocationLoading');
        if (!input) return;

        let timer = null;

        // ── Normalize tiếng Việt ──
        function nvi(str) {
            return str.normalize('NFD').replace(/[\u0300-\u036f]/g, '')
                .replace(/đ/g, 'd').replace(/Đ/g, 'D').toLowerCase().trim();
        }

        function viMatch(text, q) {
            return nvi(text).includes(nvi(q)) || text.toLowerCase().includes(q.toLowerCase());
        }

        function syncNorm(val) {
            norm.value = nvi(val);
        }

        const POP = [{
                name: 'Hà Nội',
                sub: 'Thủ đô Việt Nam'
            },
            {
                name: 'Hồ Chí Minh',
                sub: 'Thành phố sôi động nhất'
            },
            {
                name: 'Đà Nẵng',
                sub: 'Thành phố biển miền Trung'
            },
            {
                name: 'Hội An',
                sub: 'Phố cổ UNESCO'
            },
            {
                name: 'Phú Quốc',
                sub: 'Đảo ngọc Việt Nam'
            },
            {
                name: 'Nha Trang',
                sub: 'Thiên đường biển xanh'
            },
            {
                name: 'Sapa',
                sub: 'Ruộng bậc thang & sương mù'
            },
            {
                name: 'Huế',
                sub: 'Cố đô lịch sử'
            },
            {
                name: 'Hạ Long',
                sub: 'Kỳ quan thiên nhiên thế giới'
            },
            {
                name: 'Đà Lạt',
                sub: 'Thành phố ngàn hoa'
            },
        ];

        // ── Render popular list ──
        function renderPopular(items, rawQ) {
            let html = `<p class="text-xs font-semibold text-gray-400 uppercase tracking-wide px-4 pt-3 pb-1">
            ${rawQ ? 'Gợi ý' : 'Điểm đến nổi bật'}
        </p>`;
            items.forEach(p => {
                const hi = rawQ ? hlMatch(p.name, rawQ) : p.name;
                html += `
            <div class="popular-item flex items-center gap-3 px-4 py-2.5 hover:bg-blue-50 cursor-pointer transition border-b border-gray-50 last:border-0" data-name="${p.name}">
                <div class="w-8 h-8 rounded-xl bg-blue-50 flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-sm font-semibold text-gray-900 truncate">${hi}</p>
                    <p class="text-xs text-gray-400">${p.sub}</p>
                </div>
                <svg class="w-3.5 h-3.5 text-gray-300 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </div>`;
            });
            popularEl.innerHTML = html;
            popularEl.querySelectorAll('.popular-item').forEach(item => {
                item.addEventListener('click', function() {
                    input.value = this.dataset.name;
                    syncNorm(this.dataset.name);
                    dropdown.classList.add('hidden');
                    // Không submit ngay — để user điền tiếp ngày/khách
                    input.closest('form')?.querySelector('input[name="check_in"]')?.focus();
                });
            });
            popularEl.classList.remove('hidden');
        }

        // ── Highlight match ──
        function hlMatch(text, q) {
            const nt = nvi(text),
                nq = nvi(q),
                idx = nt.indexOf(nq);
            if (idx === -1) return text;
            const chars = [...text];
            let ni = 0,
                s = -1,
                e = -1;
            for (let i = 0; i < chars.length; i++) {
                const nc = nvi(chars[i]);
                if (ni === idx) s = i;
                if (ni === idx + nq.length) {
                    e = i;
                    break;
                }
                ni += nc.length;
            }
            if (e === -1) e = chars.length;
            return text.slice(0, s) + `<span class="text-blue-600">${text.slice(s,e)}</span>` + text.slice(e);
        }

        // ── Events ──
        input.addEventListener('focus', function() {
            if (!this.value.trim()) {
                renderPopular(POP, '');
                searchEl.classList.add('hidden');
                loadingEl.classList.add('hidden');
                dropdown.classList.remove('hidden');
            }
        });

        input.addEventListener('input', function() {
            const raw = this.value.trim();
            syncNorm(raw);
            if (!raw) {
                renderPopular(POP, '');
                searchEl.classList.add('hidden');
                loadingEl.classList.add('hidden');
                dropdown.classList.remove('hidden');
                return;
            }
            const matched = POP.filter(p => viMatch(p.name, raw) || viMatch(p.sub, raw));
            if (matched.length) {
                renderPopular(matched, raw);
                searchEl.classList.add('hidden');
                loadingEl.classList.add('hidden');
                dropdown.classList.remove('hidden');
            } else {
                popularEl.classList.add('hidden');
            }
            clearTimeout(timer);
            timer = setTimeout(() => searchNominatim(raw), 350);
        });

        input.addEventListener('keydown', e => {
            if (e.key === 'Escape') dropdown.classList.add('hidden');
        });

        document.addEventListener('click', e => {
            if (!input.closest('div').contains(e.target)) dropdown.classList.add('hidden');
        });

        // ── Nominatim (có dấu + không dấu) ──
        async function searchNominatim(raw) {
            const queries = [...new Set([raw, nvi(raw)])];
            popularEl.classList.add('hidden');
            searchEl.classList.add('hidden');
            loadingEl.classList.remove('hidden');
            dropdown.classList.remove('hidden');
            try {
                const results = await Promise.all(queries.map(q =>
                    fetch('https://nominatim.openstreetmap.org/search?' + new URLSearchParams({
                        q,
                        format: 'json',
                        addressdetails: 1,
                        limit: 5,
                        countrycodes: 'vn',
                        'accept-language': 'vi'
                    }), {
                        headers: {
                            'Accept-Language': 'vi'
                        }
                    }).then(r => r.json())
                ));
                const seen = new Set();
                const merged = results.flat().filter(p => {
                    if (seen.has(p.place_id)) return false;
                    seen.add(p.place_id);
                    return true;
                }).slice(0, 7);
                loadingEl.classList.add('hidden');
                renderResults(merged);
            } catch (err) {
                loadingEl.classList.add('hidden');
                searchEl.innerHTML = `<p class="px-4 py-3 text-sm text-gray-400 text-center">Không tìm thấy kết quả</p>`;
                searchEl.classList.remove('hidden');
            }
        }

        function renderResults(data) {
            searchEl.innerHTML = '';
            if (!data.length) {
                searchEl.innerHTML = `
            <div class="px-4 py-5 text-center">
                <svg class="w-8 h-8 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <p class="text-sm text-gray-400">Không tìm thấy địa điểm</p>
            </div>`;
                searchEl.classList.remove('hidden');
                return;
            }
            data.forEach(place => {
                const addr = place.address || {};
                const main = addr.city || addr.town || addr.village || addr.county || place.display_name.split(',')[0];
                const sec = [addr.state, addr.country].filter(Boolean).join(', ');
                const item = document.createElement('div');
                item.className = 'flex items-center gap-3 px-4 py-2.5 hover:bg-blue-50 cursor-pointer transition border-b border-gray-50 last:border-0';
                item.innerHTML = `
                <div class="w-8 h-8 rounded-xl bg-blue-50 flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-sm font-semibold text-gray-900 truncate">${main}</p>
                    <p class="text-xs text-gray-400 truncate">${sec}</p>
                </div>
                <svg class="w-3.5 h-3.5 text-gray-300 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>`;
                item.addEventListener('click', () => {
                    input.value = main;
                    syncNorm(main);
                    dropdown.classList.add('hidden');
                    // Focus sang field tiếp theo thay vì submit ngay
                    input.closest('form')?.querySelector('input[name="check_in"]')?.focus();
                });
                searchEl.appendChild(item);
            });
            searchEl.classList.remove('hidden');
        }
    })();
</script>