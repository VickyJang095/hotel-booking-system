<section class="relative pb-20 scroll-animate fade-up zoom-in">
    <!-- Background image -->
    <div class="absolute inset-0">
        <img src="{{ asset('images/background-hero.jpg') }}" alt="hotel background hero section" class="w-full object-cover rounded-b-[40px] h-120">
        <div class="absolute inset-0 bg-black/40 rounded-b-[40px] h-120"></div>
    </div>
    <!-- content -->
    <div class="relative text-center text-white py-36 mx-auto max-w-7xl flex flex-col items-center justify-center text-white font-roboto">
        <p class="font-bold text-2xl sm:text-4xl lg:text-5xl">Your Trip Start Here</p>
        <p class="text-base sm:text-sm lg:text-xl py-4">Find unique stays across hotels, villas, and more.</p>
        <div class="flex space-x-4 mt-9">
            <div class="bg-gray-800/50 flex m-10 px-7 py-4 rounded-[30px] backdrop-blur-sm space-x-2">

                <!-- Hotel -->
                <button class="flex items-center space-x-2 px-4 py-2 text-white rounded-full font-medium hover:bg-white hover:text-gray-700 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 21V7a2 2 0 012-2h14a2 2 0 012 2v14M9 21V12h6v9M7 9h.01M17 9h.01" />
                    </svg>
                    <span>Hotel</span>
                </button>

                <!-- House -->
                <button class="flex items-center space-x-2 px-4 py-2 text-white rounded-full font-medium hover:bg-white hover:text-gray-700 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l9-7 9 7M5 10v10a1 1 0 001 1h3m6 0h3a1 1 0 001-1V10" />
                    </svg>
                    <span>House</span>
                </button>

                <!-- Guest House -->
                <button class="flex items-center space-x-2 px-4 py-2 text-white rounded-full font-medium hover:bg-white hover:text-gray-700 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5V8H2v12h5m10 0v-5a2 2 0 00-2-2H9a2 2 0 00-2 2v5m10 0H7" />
                    </svg>
                    <span>Guest House</span>
                </button>

                <!-- Cabins -->
                <button class="flex items-center space-x-2 px-4 py-2 text-white rounded-full font-medium hover:bg-white hover:text-gray-700 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 21h18M5 21V9l7-4 7 4v12M9 21v-6h6v6" />
                    </svg>
                    <span>Cabins</span>
                </button>

                <!-- Glamping -->
                <button class="flex items-center space-x-2 px-4 py-2 text-white rounded-full font-medium hover:bg-white hover:text-gray-700 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 21l9-18 9 18M6 18h12" />
                    </svg>
                    <span>Glamping</span>
                </button>

                <!-- Dorms -->
                <button class="flex items-center space-x-2 px-4 py-2 text-white rounded-full font-medium hover:bg-white hover:text-gray-700 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20v-2a4 4 0 00-3-3.87M7 20v-2a4 4 0 013-3.87M12 7a4 4 0 110 8 4 4 0 010-8z" />
                    </svg>
                    <span>Dorms</span>
                </button>

            </div>

        </div>
        <!-- Search Card -->
        <div class="max-w-400 mx-auto px-4 -mt-15">
            <div class="bg-white rounded-4xl p-10 py-12 search-card shadow-lg">
                <div class="flex space-x-3 pt-3">
                    <!-- Location -->
                    <div class="pr-12 border-r border-gray-400">
                        <label class="block text-[18px] font-semibold text-gray-700 text-start px-4">Location</label>
                        <input type="text" id="location" placeholder="Where are you going?" class="w-full text-[16px] px-4 py-3 border-none text-gray-500">
                    </div>

                    <!-- Check In -->
                    <div class="border-r border-gray-400 pr-6">
                        <label class="block text-[18px] font-semibold text-gray-700 text-start px-4">Check In</label>
                        <input type="date" id="checkin" placeholder="Add Dates" class="w-full px-4 py-3 border-none text-gray-500 text-[16px]">
                    </div>

                    <!-- Check Out -->
                    <div class="border-r border-gray-400 pr-6">
                        <label class="block text-[18px] font-semibold text-gray-700 text-start px-4">Check Out</label>
                        <input type="date" id="checkout" placeholder="Add Dates" class="w-full px-4 py-3 border-none text-gray-500 text-[16px]">
                    </div>

                    <!-- Rooms and Guests -->
                    <div class="pr-12">
                        <label class="block text-[18px] font-semibold text-gray-700 text-start px-4">Rooms and Guests</label>
                        <button onclick="toggleGuests()" class="w-full px-4 py-3 rounded-xl border text-left">
                            <span id="guestText" class="border-none text-gray-500 text-[16px]">1 room, 1 adult, 0 children</span>
                        </button>
                        <!-- Dropdown -->
                        <div id="guestBox" class="hidden absolute bg-white shadow-lg text-gray-700 rounded-xl p-4 mt-2 w-64 z-10">
                            <div class="flex justify-between">
                                <span>Rooms</span>
                                <div>
                                    <button onclick="change('rooms',-1)">-</button>
                                    <span id="rooms">1</span>
                                    <button onclick="change('rooms',1)">+</button>
                                </div>
                            </div>

                            <div class="flex justify-between mt-2">
                                <span>Adults</span>
                                <div>
                                    <button onclick="change('adults',-1)">-</button>
                                    <span id="adults">1</span>
                                    <button onclick="change('adults',1)">+</button>
                                </div>
                            </div>

                            <div class="flex justify-between mt-2">
                                <span>Children</span>
                                <div>
                                    <button onclick="change('children',-1)">-</button>
                                    <span id="children">0</span>
                                    <button onclick="change('children',1)">+</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Search Button -->
                    <div class="flex justify-end m-3">
                        <button onclick="search()" class="flex items-center space-x-2 px-4 bg-blue-600 text-white text-[20px] rounded-xl font-semibold hover:bg-blue-700 transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <span>Search</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    let data = {
        rooms: 1,
        adults: 1,
        children: 0
    };

    function toggleGuests() {
        document.getElementById('guestBox').classList.toggle('hidden');
    }

    function change(type, value) {
        data[type] = Math.max(type === 'adults' ? 1 : 0, data[type] + value);
        document.getElementById(type).innerText = data[type];
        updateGuestText();
    }

    function updateGuestText() {
        document.getElementById('guestText').innerText =
            `${data.rooms} room, ${data.adults} adult, ${data.children} children`;
    }

    function search() {
        const location = document.getElementById('location').value;
        const checkin = document.getElementById('checkin').value;
        const checkout = document.getElementById('checkout').value;

        if (!location || !checkin || !checkout) {
            alert('Please fill all fields');
            return;
        }

        if (checkout < checkin) {
            alert('Check-out must be after Check-in');
            return;
        }

        console.log({
            location,
            checkin,
            checkout,
            ...data
        });
        alert('Search success!');
    }
</script>