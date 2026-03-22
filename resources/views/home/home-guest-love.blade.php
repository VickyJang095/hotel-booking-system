<section class="py-24 scroll-animate zoom-in fade-up">
    <div class="max-w-7xl mx-auto px-4">

        <div class="flex items-center justify-between mb-6">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900">
                {{ __('home.guests_love_title') }}
            </h2>
            <div class="flex space-x-2">
                <button class="w-9 h-9 rounded-full border border-gray-300 flex items-center justify-center hover:bg-black hover:text-white hover:border-black transition">
                    <i class="fa-solid fa-chevron-left"></i>
                </button>
                <button class="w-9 h-9 rounded-full border border-gray-300 flex items-center justify-center hover:bg-black hover:text-white hover:border-black transition">
                    <i class="fa-solid fa-chevron-right"></i>
                </button>
            </div>
        </div>

        @php
        $cards = [
        ['img'=>'https://images.unsplash.com/photo-1505691938895-1758d7feb511?w=600&q=80', 'rating'=>4.8,'label'=>'very_good','reviews'=>160,'name'=>'Seaside Serenity Villa', 'location'=>'Amalfi Coast','country'=>'italy', 'price'=>175],
        ['img'=>'https://images.unsplash.com/photo-1661167207458-8ba64ca2a780?q=80&w=1112&auto=format&fit=crop','rating'=>3.8,'label'=>'good', 'reviews'=>210,'name'=>'Tropical Bungalow', 'location'=>'Phuket', 'country'=>'thailand','price'=>160],
        ['img'=>'https://a0.muscache.com/im/pictures/861c810e-7115-47f8-afb7-13d58833647e.jpg?im_w=720', 'rating'=>4.9,'label'=>'excellent', 'reviews'=>185,'name'=>'Santorini Sunset Suites', 'location'=>'Santorini', 'country'=>'greece', 'price'=>255],
        ['img'=>'https://thoe.com/wp-content/uploads/2024/06/Marbella-hotel-renders_web.jpg','rating'=>4.6,'label'=>'very_good','reviews'=>142,'name'=>'Marbella Resort', 'location'=>'Marbella', 'country'=>'spain', 'price'=>190],
        ];
        $currency = app(\App\Services\CurrencyService::class);
        @endphp

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($cards as $card)
            <div class="bg-white rounded-2xl shadow hover:shadow-xl transition overflow-hidden group cursor-pointer">
                <div class="relative h-48 overflow-hidden">
                    <img src="{{ $card['img'] }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-300">
                    <button class="absolute top-3 right-3 bg-white/90 rounded-full w-9 h-9 flex items-center justify-center hover:bg-white transition">
                        <i class="fa-regular fa-heart text-gray-700 text-lg"></i>
                    </button>
                </div>
                <div class="p-4">
                    <div class="flex items-center text-sm mb-2">
                        <span class="bg-blue-100 text-blue-800 px-2 py-0.5 rounded text-xs font-semibold">{{ $card['rating'] }}</span>
                        <span class="ml-2 text-blue-900 font-semibold">{{ __('home.'.$card['label']) }}</span>
                        <span class="ml-2 text-gray-500">· {{ $card['reviews'] }} {{ __('home.reviews') }}</span>
                    </div>
                    <h3 class="font-bold text-gray-900 text-lg mb-1">{{ $card['name'] }}</h3>
                    <p class="text-sm text-gray-600 mb-2">{{ $card['location'] }}, {{ __('home.'.$card['country']) }}</p>
                    <div class="flex space-x-2 items-center mt-4">
                        <span class="text-xs text-gray-500">{{ __('home.per_night') }}</span>
                        <span class="text-xl font-bold text-gray-900">{{ $currency->formatPrice($card['price']) }}</span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>