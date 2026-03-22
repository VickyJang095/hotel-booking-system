<section class="bg-gray-100 scroll-animate zoom-in fade-up">
    <div class="max-w-7xl mx-auto px-4 py-13">
        <h2 class="text-[35px] font-bold mb-4">{{ __('home.explore_tripto_title') }}</h2>

        <div class="grid grid-cols-1 lg:grid-cols-7 gap-3 mb-16 max-w-7xl">
            <!-- Main Video Card -->
            <div class="lg:col-span-6 relative rounded-2xl overflow-hidden h-124 group cursor-pointer">
                <img src="https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=1200&q=80" alt="Luxury Destinations" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-r from-black/60 via-black/30 to-transparent"></div>
                <div class="absolute bottom-2 left-6 text-white max-w-lg p-10">
                    <h3 class="text-[55px] font-bold mb-3">{{ __('home.explore_tripto_heading') }}</h3>
                    <p class="text-[20px] mb-6">{{ __('home.explore_tripto_sub') }}</p>
                    <button class="px-6 py-3 bg-blue-600 text-white text-[18px] rounded-xl font-medium hover:bg-blue-700 transition">
                        {{ __('home.explore_all_videos') }}
                    </button>
                </div>
            </div>

            <!-- Side Video Cards -->
            <div class="flex flex-col gap-2">
                @php
                $videos = [
                ['img' => 'https://images.unsplash.com/photo-1514282401047-d79a71a590e8?w=400&q=80', 'alt' => 'Maldives', 'name' => 'Maldives, Asia', 'stars' => 4],
                ['img' => 'https://images.unsplash.com/photo-1589394815804-964ed0be2eb5?w=400&q=80', 'alt' => 'Phuket', 'name' => 'Phuket, Thailand', 'stars' => 3],
                ['img' => 'https://images.unsplash.com/photo-1559827260-dc66d52bef19?w=400&q=80', 'alt' => 'Maui', 'name' => 'Maui, Hawaii USA', 'stars' => 5],
                ];
                @endphp

                @foreach($videos as $video)
                <div class="relative rounded-2xl overflow-hidden h-40 group cursor-pointer">
                    <img src="{{ $video['img'] }}" alt="{{ $video['alt'] }}" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-black/30"></div>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <div class="w-10 h-10 bg-white/90 rounded-full flex items-center justify-center hover:scale-110 transition">
                            <svg class="w-5 h-5 text-gray-900 ml-0.5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M8 5v14l11-7z" />
                            </svg>
                        </div>
                    </div>
                    <div class="absolute bottom-3 left-3 text-white">
                        <h4 class="font-bold text-sm">{{ $video['name'] }}</h4>
                        <div class="flex items-center mt-1">
                            <span class="text-yellow-400 text-xs">
                                @for($i = 1; $i <= 5; $i++)
                                    {{ $i <= $video['stars'] ? '★' : '☆' }}
                                    @endfor
                                    </span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>