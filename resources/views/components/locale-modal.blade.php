{{-- resources/views/components/locale-modal.blade.php --}}

<div id="locale-modal" class="hidden fixed inset-0 z-[999] flex items-center justify-center bg-black/40 backdrop-blur-sm">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-3xl mx-4 overflow-hidden" style="max-height:92vh">

        {{-- Header tabs --}}
        <div class="flex items-center justify-between border-b border-gray-100 px-8 pt-6 pb-0">
            <div class="flex gap-8">
                <button type="button" onclick="switchTab('languages')" id="tab-languages"
                    class="pb-4 text-base font-semibold border-b-2 border-gray-900 text-gray-900 transition">
                    {{ app()->getLocale() === 'vi' ? 'Ngôn ngữ' : 'Languages' }}
                </button>
                <button type="button" onclick="switchTab('currency')" id="tab-currency"
                    class="pb-4 text-base font-semibold border-b-2 border-transparent text-gray-400 hover:text-gray-600 transition">
                    {{ app()->getLocale() === 'vi' ? 'Tiền tệ' : 'Currency' }}
                </button>
            </div>
            <button type="button" onclick="closeLocaleModal()" class="mb-4 w-9 h-9 rounded-full hover:bg-gray-100 flex items-center justify-center transition">
                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <div class="overflow-y-auto" style="max-height:calc(92vh - 65px)">

            {{-- Languages tab --}}
            <div id="panel-languages" class="p-8">

                {{-- Auto-translate toggle --}}
                <div class="flex items-center justify-between bg-gray-50 rounded-2xl px-5 py-4 mb-7">
                    <div class="flex items-center gap-4">
                        <svg class="w-6 h-6 text-gray-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129" />
                        </svg>
                        <div>
                            <p class="text-base font-semibold text-gray-800">{{ app()->getLocale() === 'vi' ? 'Dịch tự động' : 'Translation' }}</p>
                            <p class="text-sm text-gray-400 mt-0.5">{{ app()->getLocale() === 'vi' ? 'Tự động dịch mô tả và đánh giá.' : 'Automatically translate descriptions and reviews.' }}</p>
                        </div>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer shrink-0">
                        <input type="checkbox" id="auto-translate" class="sr-only peer" checked>
                        <div class="w-12 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                    </label>
                </div>

                <p class="text-base font-semibold text-gray-700 mb-4">{{ app()->getLocale() === 'vi' ? 'Chọn ngôn ngữ' : 'Choose a language' }}</p>

                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5">
                    @php
                    $languages = [
                    ['code' => 'vi', 'label' => 'Tiếng Việt', 'flag' => 'vn'],
                    ['code' => 'en', 'label' => 'English (US)', 'flag' => 'us'],
                    ['code' => 'en', 'label' => 'English (UK)', 'flag' => 'gb'],
                    ['code' => 'de', 'label' => 'Deutsch', 'flag' => 'de'],
                    ['code' => 'fr', 'label' => 'Français', 'flag' => 'fr'],
                    ['code' => 'es', 'label' => 'Español', 'flag' => 'es'],
                    ['code' => 'it', 'label' => 'Italiano', 'flag' => 'it'],
                    ['code' => 'pt', 'label' => 'Português', 'flag' => 'pt'],
                    ['code' => 'nl', 'label' => 'Nederlands', 'flag' => 'nl'],
                    ['code' => 'ja', 'label' => '日本語', 'flag' => 'jp'],
                    ['code' => 'zh', 'label' => '简体中文', 'flag' => 'cn'],
                    ['code' => 'ko', 'label' => '한국어', 'flag' => 'kr'],
                    ['code' => 'ru', 'label' => 'Русский', 'flag' => 'ru'],
                    ['code' => 'ar', 'label' => 'العربية', 'flag' => 'sa'],
                    ['code' => 'tr', 'label' => 'Türkçe', 'flag' => 'tr'],
                    ['code' => 'th', 'label' => 'ภาษาไทย', 'flag' => 'th'],
                    ['code' => 'id', 'label' => 'Bahasa Indonesia', 'flag' => 'id'],
                    ['code' => 'ms', 'label' => 'Bahasa Malaysia', 'flag' => 'my'],
                    ['code' => 'pl', 'label' => 'Polski', 'flag' => 'pl'],
                    ['code' => 'sv', 'label' => 'Svenska', 'flag' => 'se'],
                    ];
                    $supported = ['vi', 'en'];
                    @endphp

                    @foreach($languages as $lang)
                    @php $isSupported = in_array($lang['code'], $supported); @endphp
                    <a href="{{ $isSupported ? route('locale.switch', $lang['code']) : '#' }}"
                        onclick="{{ !$isSupported ? 'return false' : '' }}"
                        class="flex items-center gap-3 px-4 py-3 rounded-xl border transition
                            {{ app()->getLocale() === $lang['code'] ? 'border-blue-500 bg-blue-50' : 'border-transparent hover:bg-gray-50' }}
                            {{ !$isSupported ? 'opacity-40 cursor-not-allowed' : 'cursor-pointer' }}">
                        <img src="https://flagcdn.com/w20/{{ $lang['flag'] }}.png" class="w-6 h-4 object-cover rounded-sm shrink-0" alt="{{ $lang['label'] }}">
                        <span class="text-sm font-medium text-gray-700 truncate">{{ $lang['label'] }}</span>
                        @if(app()->getLocale() === $lang['code'])
                        <svg class="w-4 h-4 text-blue-600 ml-auto shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                        </svg>
                        @endif
                    </a>
                    @endforeach
                </div>
            </div>

            {{-- Currency tab --}}
            <div id="panel-currency" class="p-8 hidden">
                <p class="text-base font-semibold text-gray-700 mb-4">{{ app()->getLocale() === 'vi' ? 'Chọn tiền tệ' : 'Choose a currency' }}</p>

                <div class="grid grid-cols-2 sm:grid-cols-3 gap-2.5">
                    @php
                    $currencies = [
                    ['code' => 'VND', 'label' => 'Việt Nam Đồng', 'symbol' => '₫', 'flag' => 'vn'],
                    ['code' => 'USD', 'label' => 'US Dollar', 'symbol' => '$', 'flag' => 'us'],
                    ['code' => 'EUR', 'label' => 'Euro', 'symbol' => '€', 'flag' => 'eu'],
                    ['code' => 'GBP', 'label' => 'British Pound', 'symbol' => '£', 'flag' => 'gb'],
                    ['code' => 'JPY', 'label' => 'Japanese Yen', 'symbol' => '¥', 'flag' => 'jp'],
                    ['code' => 'KRW', 'label' => 'Korean Won', 'symbol' => '₩', 'flag' => 'kr'],
                    ['code' => 'THB', 'label' => 'Thai Baht', 'symbol' => '฿', 'flag' => 'th'],
                    ['code' => 'SGD', 'label' => 'Singapore Dollar', 'symbol' => 'S$', 'flag' => 'sg'],
                    ['code' => 'AUD', 'label' => 'Australian Dollar','symbol' => 'A$', 'flag' => 'au'],
                    ['code' => 'CNY', 'label' => 'Chinese Yuan', 'symbol' => '¥', 'flag' => 'cn'],
                    ];
                    $currentCurrency = session('currency', 'VND');
                    @endphp

                    @foreach($currencies as $currency)
                    <button type="button"
                        data-currency="{{ $currency['code'] }}"
                        onclick="setCurrency(this.dataset.currency)"
                        class="flex items-center gap-3 px-5 py-4 rounded-xl border text-left transition
                            {{ $currentCurrency === $currency['code'] ? 'border-blue-500 bg-blue-50' : 'border-gray-200 hover:bg-gray-50' }}">
                        <img src="https://flagcdn.com/w20/{{ $currency['flag'] }}.png" class="w-6 h-4 object-cover rounded-sm shrink-0" alt="{{ $currency['code'] }}">
                        <div class="min-w-0">
                            <p class="text-sm font-semibold text-gray-800">{{ $currency['code'] }} <span class="font-normal text-gray-400">{{ $currency['symbol'] }}</span></p>
                            <p class="text-xs text-gray-400 truncate mt-0.5">{{ $currency['label'] }}</p>
                        </div>
                        @if($currentCurrency === $currency['code'])
                        <svg class="w-4 h-4 text-blue-600 ml-auto shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                        </svg>
                        @endif
                    </button>
                    @endforeach
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    function openLocaleModal(tab) {
        document.getElementById('locale-modal').classList.remove('hidden');
        switchTab(tab || 'languages');
        document.body.style.overflow = 'hidden';
    }

    function closeLocaleModal() {
        document.getElementById('locale-modal').classList.add('hidden');
        document.body.style.overflow = '';
    }

    function switchTab(tab) {
        ['languages', 'currency'].forEach(t => {
            const btn = document.getElementById('tab-' + t);
            const panel = document.getElementById('panel-' + t);
            if (t === tab) {
                btn.classList.add('border-gray-900', 'text-gray-900');
                btn.classList.remove('border-transparent', 'text-gray-400');
                panel.classList.remove('hidden');
            } else {
                btn.classList.remove('border-gray-900', 'text-gray-900');
                btn.classList.add('border-transparent', 'text-gray-400');
                panel.classList.add('hidden');
            }
        });
    }

    function setCurrency(code) {
        fetch('/currency/' + code, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        }).then(() => location.reload());
    }

    document.getElementById('locale-modal').addEventListener('click', function(e) {
        if (e.target === this) closeLocaleModal();
    });

    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') closeLocaleModal();
    });
</script>