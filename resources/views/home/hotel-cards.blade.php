<section class="max-w-7xl mx-auto px-6 py-2 font-roboto scroll-animate fade-up">
    <h2 class="text-[35px] font-bold text-gray-900 mb-6">
        {{ __('home.trending_destinations') }}
    </h2>

    <div class="flex flex-wrap gap-3 mb-10">
        <button class="px-5 py-2 rounded-full border hover:bg-black hover:text-white text-gray-700 text-base font-semibold">
            <i class="fa-solid fa-seedling"></i> {{ __('home.spring_picks') }}
        </button>
        <button class="px-5 py-2 rounded-full border text-gray-700 text-base font-semibold hover:bg-black hover:text-white">
            <i class="fa-solid fa-sun"></i> {{ __('home.summer_hotspot') }}
        </button>
        <button class="px-5 py-2 rounded-full border text-gray-700 text-base font-semibold hover:bg-black hover:text-white">
            <i class="fa-solid fa-leaf"></i> {{ __('home.autumn_escape') }}
        </button>
        <button class="px-5 py-2 rounded-full border text-gray-700 text-base font-semibold hover:bg-black hover:text-white">
            <i class="fa-solid fa-snowflake"></i> {{ __('home.winter_getaway') }}
        </button>
    </div>

    @php
    $destinations = [
    ['img'=>'https://images.unsplash.com/photo-1502602898657-3e91760cbb34','name'=>'Paris, France', 'price'=>128,'desc_key'=>'paris_desc'],
    ['img'=>'https://images.unsplash.com/photo-1505761671935-60b3a7427bad','name'=>'Santorini, Greece', 'price'=>225,'desc_key'=>'santorini_desc'],
    ['img'=>'https://images.unsplash.com/photo-1507525428034-b723cf961d3e','name'=>'Bali, Indonesia', 'price'=>26, 'desc_key'=>'bali_desc'],
    ['img'=>'https://images.unsplash.com/photo-1549692520-acc6669e2f0c', 'name'=>'Kyoto, Japan', 'price'=>190,'desc_key'=>'kyoto_desc'],
    ];
    $currency = app(\App\Services\CurrencyService::class);
    @endphp

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        @foreach($destinations as $dest)
        <div class="relative h-[500px] rounded-2xl overflow-hidden shadow-lg group">
            <img src="{{ $dest['img'] }}"
                class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition duration-500" />
            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/30 to-transparent"></div>
            <div class="absolute bottom-0 p-5 text-white">
                <h3 class="text-lg font-semibold">{{ $dest['name'] }}</h3>
                <p class="text-sm mt-1">
                    {{ __('home.from') }} <span class="text-yellow-400 font-semibold">{{ $currency->formatPrice($dest['price']) }}/{{ __('home.night') }}</span>
                </p>
                <p class="text-xs text-gray-300 mt-2">{{ __('home.'.$dest['desc_key']) }}</p>
            </div>
        </div>
        @endforeach
    </div>
</section>