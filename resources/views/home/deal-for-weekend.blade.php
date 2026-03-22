<section class="py-12 mt-16 bg-gray-100 scroll-animate zoom-in">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900">{{ __('home.weekend_deals') }}</h2>
            <div class="flex space-x-2">
                <button class="w-9 h-9 rounded-full border border-gray-300 flex items-center justify-center hover:bg-black hover:text-white hover:border-black transition"><i class="fa-solid fa-chevron-left"></i></button>
                <button class="w-9 h-9 rounded-full border border-gray-300 flex items-center justify-center hover:bg-black hover:text-white hover:border-black transition"><i class="fa-solid fa-chevron-right"></i></button>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @php
            $deals = [
            ['img'=>'https://images.unsplash.com/photo-1505691938895-1758d7feb511?w=600&q=80','rating'=>4.8,'label'=>'very_good','reviews'=>160,'name'=>'Seaside Serenity Villa','location'=>'Amalfi Coast','country'=>'italy','original'=>250,'price'=>175],
            ['img'=>'https://images.unsplash.com/photo-1661167207458-8ba64ca2a780?q=80&w=1112&auto=format&fit=crop','rating'=>3.8,'label'=>'good','reviews'=>210,'name'=>'Tropical Bungalow','location'=>'Phuket','country'=>'thailand','original'=>210,'price'=>160],
            ['img'=>'https://a0.muscache.com/im/pictures/861c810e-7115-47f8-afb7-13d58833647e.jpg?im_w=720','rating'=>4.9,'label'=>'excellent','reviews'=>185,'name'=>'Santorini Sunset Suites','location'=>'Santorini','country'=>'greece','original'=>300,'price'=>255],
            ['img'=>'https://thoe.com/wp-content/uploads/2024/06/Marbella-hotel-renders_web.jpg','rating'=>4.6,'label'=>'very_good','reviews'=>142,'name'=>'Marbella Resort','location'=>'Marbella','country'=>'spain','original'=>280,'price'=>190],
            ];
            $currency = app(\App\Services\CurrencyService::class);
            @endphp

            @foreach($deals as $deal)
            <div class="bg-white rounded-2xl shadow hover:shadow-xl transition overflow-hidden group cursor-pointer">
                <div class="relative h-48 overflow-hidden">
                    <img src="{{ $deal['img'] }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-300">
                    <button class="absolute top-3 right-3 bg-white/90 rounded-full w-9 h-9 flex items-center justify-center hover:bg-white transition">
                        <i class="fa-regular fa-heart text-gray-700 text-lg"></i>
                    </button>
                </div>
                <div class="p-4">
                    <div class="flex items-center text-sm mb-2">
                        <span class="bg-blue-100 text-blue-800 px-2 py-0.5 rounded text-xs font-semibold">{{ $deal['rating'] }}</span>
                        <span class="ml-2 text-blue-900 font-semibold">{{ __('home.'.$deal['label']) }}</span>
                        <span class="ml-2 text-gray-500">· {{ $deal['reviews'] }} {{ __('home.reviews') }}</span>
                    </div>
                    <h3 class="font-bold text-gray-900 text-lg mb-1">{{ $deal['name'] }}</h3>
                    <p class="text-sm text-gray-600 mb-2">{{ $deal['location'] }}, {{ __('home.'.$deal['country']) }}</p>
                    <span class="inline-block text-xs bg-green-600 text-white px-2.5 py-1 rounded-full font-medium">
                        {{ __('home.getaway_deal') }}
                    </span>
                    <div class="flex space-x-2 items-center mt-4">
                        <span class="text-xs text-gray-500">{{ __('home.per_night') }}</span>
                        <span class="text-sm text-gray-400 line-through">{{ $currency->formatPrice($deal['original']) }}</span>
                        <span class="text-xl font-bold text-gray-900">{{ $currency->formatPrice($deal['price']) }}</span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>