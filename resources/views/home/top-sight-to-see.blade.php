<!-- Du lịch nhiều hơn, chi tiêu ít hơn -->
<section class="max-w-7xl mx-auto px-4 pt-16 scroll-animate fade-up">
    <h2 class="text-[35px] font-bold mb-6">{{ __('home.travel_more_spend_less') }}</h2>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <!-- Card 1 -->
        <div class="border border-blue-500 rounded-2xl p-6 h-40 items-center justify-center hover:shadow-lg hover:border-blue-600 transition">
            <h3 class="font-bold text-xl mb-2">{{ __('home.discount_10_stays') }}</h3>
            <p class="text-base text-gray-800">{{ __('home.discount_properties_worldwide') }}</p>
        </div>

        <!-- Card 2 -->
        <div class="border border-blue-500 rounded-2xl p-6 hover:shadow-lg transition">
            <h3 class="font-bold text-xl mb-2">{{ __('home.travel_season') }}</h3>
            <p class="text-base text-gray-800">{{ __('home.avoid_peak_times') }}</p>
        </div>

        <!-- Card 3 -->
        <div class="border border-blue-500 rounded-2xl p-6 hover:shadow-lg transition">
            <h3 class="font-bold text-xl mb-2">{{ __('home.exclusive_deals') }}</h3>
            <p class="text-base text-gray-800">{{ __('home.enjoy_worldwide_discounts') }}</p>
        </div>

        <!-- Card 4 -->
        <div class="border border-blue-500 rounded-2xl p-6 hover:shadow-lg transition">
            <h3 class="font-bold text-xl mb-2">{{ __('home.weekend_special') }}</h3>
            <p class="text-base text-gray-800">{{ __('home.weekend_12_off') }}</p>
        </div>
    </div>
</section>

<!-- Những điểm đến hàng đầu -->
<section class="max-w-7xl mx-auto px-4 py-4 space-y-4">
    <h2 class="text-[35px] font-bold mb-6">{{ __('home.top_sights_to_see') }}</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-4">
        <!-- Sassnitz -->
        <div class="relative rounded-3xl overflow-hidden h-69 shadow-lg group cursor-pointer">
            <img src="https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=800&q=80" alt="Sassnitz" class="w-full h-full object-cover group-hover:scale-110 transition duration-300">
            <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
            <div class="absolute bottom-4 left-4 text-white">
                <div class="flex items-center space-x-2 mb-1">
                    <span class="text-xl font-bold">{{ __('home.sassnitz') }}</span>
                    <img src="https://flagcdn.com/w40/de.png" class="w-6 h-6 rounded-full" alt="{{ __('home.germany') }}">
                </div>
            </div>
        </div>

        <!-- Việt Nam -->
        <div class="relative rounded-3xl shadow-lg overflow-hidden h-69 group cursor-pointer">
            <img src="https://dulichsaigon.edu.vn/wp-content/uploads/2024/01/da-nang-top-10-thanh-pho-dang-de-du-lich-nhat.jpg" alt="Việt Nam" class="w-full h-full object-cover group-hover:scale-110 transition duration-300">
            <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
            <div class="absolute bottom-4 left-4 text-white">
                <div class="flex items-center space-x-2 mb-1">
                    <span class="text-xl font-bold">{{ __('home.vietnam') }}</span>
                    <img src="https://flagcdn.com/w40/vn.png" class="w-6 h-6 rounded-full" alt="{{ __('home.vietnam') }}">
                </div>
            </div>
        </div>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <!-- Sagard -->
        <div class="relative rounded-3xl shadow-lg overflow-hidden h-64 group cursor-pointer">
            <img src="https://images.unsplash.com/photo-1502602898657-3e91760cbb34?w=800&q=80" alt="Sagard" class="w-full h-full object-cover group-hover:scale-110 transition duration-300">
            <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
            <div class="absolute bottom-4 left-4 text-white">
                <div class="flex items-center space-x-2 mb-1">
                    <span class="text-xl font-bold">{{ __('home.sagard') }}</span>
                    <img src="https://flagcdn.com/w40/fr.png" class="w-6 h-6 rounded-full" alt="{{ __('home.france') }}">
                </div>
            </div>
        </div>

        <!-- Bergen -->
        <div class="relative rounded-3xl shadow-lg overflow-hidden h-64 group cursor-pointer">
            <img src="https://images.unsplash.com/photo-1513635269975-59663e0ac1ad?w=800&q=80" alt="Bergen" class="w-full h-full object-cover group-hover:scale-110 transition duration-300">
            <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
            <div class="absolute bottom-4 left-4 text-white">
                <div class="flex items-center space-x-2 mb-1">
                    <span class="text-xl font-bold">{{ __('home.bergen') }}</span>
                    <img src="https://flagcdn.com/w40/gb.png" class="w-6 h-6 rounded-full" alt="{{ __('home.uk') }}">
                </div>
            </div>
        </div>

        <!-- Freedom -->
        <div class="relative rounded-2xl shadow-lg overflow-hidden h-64 group cursor-pointer">
            <img src="https://images.unsplash.com/photo-1485738422979-f5c462d49f74?w=800&q=80" alt="Freedom" class="w-full h-full object-cover group-hover:scale-110 transition duration-300">
            <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
            <div class="absolute bottom-4 left-4 text-white">
                <div class="flex items-center space-x-2 mb-1">
                    <span class="text-xl font-bold">{{ __('home.freedom') }}</span>
                    <img src="https://flagcdn.com/w40/us.png" class="w-6 h-6 rounded-full" alt="{{ __('home.usa') }}">
                </div>
            </div>
        </div>
    </div>
</section>
