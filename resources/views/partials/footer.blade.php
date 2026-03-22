<footer class="bg-[#0B0F14] text-gray-300 pt-16">
    <div class="max-w-7xl mx-auto px-6">

        <!-- Top Footer -->
        <div class="grid grid-cols-1 md:grid-cols-6 gap-10 pb-12 border-b border-gray-700">

            <!-- Logo + Description -->
            <div class="md:col-span-2">
                <h2 class="text-white text-2xl font-bold mb-4">Tripto</h2>
                <p class="text-sm text-gray-400 mb-6">
                    {{__('home.footer_description')}}
                </p>

                <p class="text-base text-sky-300 font-bold mb-3">{{__('home.download_app')}}</p>
                <div class="flex space-x-3">
                    <img src="https://developer.apple.com/assets/elements/badges/download-on-the-app-store.svg"
                        class="h-10" alt="App Store">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/7/78/Google_Play_Store_badge_EN.svg"
                        class="h-10" alt="Google Play">
                </div>
            </div>

            <!-- Explore -->
            <div>
                <h3 class="text-base text-sky-300 font-bold mb-3">{{__('home.explore')}}</h3>
                <ul class="space-y-2 text-sm">
                    <li><a href="#" class="hover:text-white">{{__('home.trending')}}</a></li>
                    <li><a href="#" class="hover:text-white">{{__('home.summer')}}</a></li>
                    <li><a href="#" class="hover:text-white">{{__('home.winter')}}</a></li>
                    <li><a href="#" class="hover:text-white">{{__('home.weekend')}}</a></li>
                    <li><a href="#" class="hover:text-white">{{__('home.family')}}</a></li>
                </ul>
            </div>

            <!-- Property Types -->
            <div>
                <h3 class="text-base text-sky-300 font-bold mb-3">
                    {{ __('home.property_types') }}
                </h3>

                <ul class="space-y-2 text-sm">
                    <li><a href="#" class="hover:text-white">{{ __('home.hotels') }}</a></li>
                    <li><a href="#" class="hover:text-white">{{ __('home.apartments') }}</a></li>
                    <li><a href="#" class="hover:text-white">{{ __('home.villas') }}</a></li>
                    <li><a href="#" class="hover:text-white">{{ __('home.cabins') }}</a></li>
                    <li><a href="#" class="hover:text-white">{{ __('home.glamping') }}</a></li>
                    <li><a href="#" class="hover:text-white">{{ __('home.domes') }}</a></li>
                </ul>
            </div>

            <!-- Support -->
            <div>
                <h3 class="text-base text-sky-300 font-bold mb-3">{{ __('home.support') }}</h3>
                <ul class="space-y-2 text-sm">
                    <li><a href="#" class="hover:text-white">{{ __('home.help_center') }}</a></li>
                    <li><a href="#" class="hover:text-white">{{ __('home.live_chat') }}</a></li>
                    <li><a href="#" class="hover:text-white">{{ __('home.faqs') }}</a></li>
                    <li><a href="#" class="hover:text-white">{{ __('home.contact') }}</a></li>
                </ul>
            </div>

            <!-- Get In Touch -->
            <div>
                <h3 class="text-base text-sky-300 font-bold mb-3">{{ __('home.get_in_touch') }}</h3>
                <p class="text-sm mb-2">+1 (800) 123-456</p>
                <p class="text-sm mb-4">support@tripto.com</p>

                <div class="flex items-center space-x-4 text-gray-400 text-lg">
                    <a href="#" class="hover:text-white transition">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="#" class="hover:text-white transition">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="#" class="hover:text-white transition">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="#" class="hover:text-white transition">
                        <i class="fab fa-youtube"></i>
                    </a>
                </div>

            </div>

        </div>

        <!-- Bottom Footer -->
        <div class="flex flex-col md:flex-row justify-between items-center py-6 text-sm text-gray-400">
            <p>{{ __('home.copyright') }}</p>

            <!-- Right -->
            <div class="flex items-center space-x-4 mt-4 md:mt-0">
                <img src="https://upload.wikimedia.org/wikipedia/commons/4/41/Visa_Logo.png"
                    class="h-5 opacity-80" />

                <img src="https://upload.wikimedia.org/wikipedia/commons/0/04/Mastercard-logo.png"
                    class="h-5 opacity-80" />

                <img src="https://upload.wikimedia.org/wikipedia/commons/b/b5/PayPal.svg"
                    class="h-5 opacity-80" />

                <img src="https://cdn.worldvectorlogo.com/logos/stripe-4.svg"
                    class="h-5 opacity-80" />
            </div>

        </div>

    </div>
</footer>