<script src="https://cdn.jsdelivr.net/npm/@tailwindplus/elements@1" type="module"></script>
<nav class="relative shadow-sm bg-white after:pointer-events-none after:absolute after:inset-x-0 after:bottom-0 after:h-px after:bg-white/10 scroll-animate zoom-in fade-up">
    <div class="mx-auto max-w-7xl px-2 sm:px-6 lg:px-4">
        <div class="relative flex h-24 items-center justify-between">
            <div class="absolute inset-y-0 left-0 flex items-center sm:hidden">
                <button type="button" command="--toggle" commandfor="mobile-menu" class="relative inline-flex items-center justify-center rounded-md p-2 text-gray-400 hover:bg-white/5 hover:text-white focus:outline-2 focus:-outline-offset-1 focus:outline-indigo-500">
                    <span class="absolute -inset-0.5"></span>
                    <span class="sr-only">Open main menu</span>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" data-slot="icon" aria-hidden="true" class="size-6 in-aria-expanded:hidden">
                        <path d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" data-slot="icon" aria-hidden="true" class="size-6 not-in-aria-expanded:hidden">
                        <path d="M6 18 18 6M6 6l12 12" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </button>
            </div>

            <div class="flex flex-1 items-center justify-center sm:items-stretch sm:justify-start">
                <div class="flex shrink-0 items-center">
                    <a href="{{ route('home') }}">
                        <img src="{{ asset('images/logo.png') }}" alt="Tripto Logo" class="h-14 w-auto object-contain">
                    </a>
                </div>
            </div>

            <div class="absolute inset-y-0 right-0 flex items-center space-x-2 pr-2 sm:static sm:inset-auto sm:ml-6 sm:pr-0">

                @auth
                <div class="flex items-center space-x-3">

                    {{-- Currency --}}
                    <button type="button" class="flex items-center space-x-2">
                        <img src="https://flagcdn.com/w40/gb.png" class="w-6 h-6 rounded-full" alt="UK">
                        <span class="text-lg font-bold text-blue-500 hover:text-yellow-500">USD</span>
                    </button>

                    {{-- Notification --}}
                    <button type="button" class="p-2">
                        <svg class="w-7 h-7 text-blue-500 hover:text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                    </button>

                    {{-- User dropdown --}}
                    <el-dropdown class="relative ml-3">
                        <button type="button" class="relative flex rounded-full focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-500">
                            <span class="absolute -inset-1.5"></span>
                            <span class="sr-only">Open user menu</span>
                            <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80"
                                alt="" class="size-9 rounded-full object-cover ring-2 ring-blue-500" />
                        </button>

                        <el-menu anchor="bottom end" popover class="w-56 rounded-2xl bg-white shadow-xl border border-gray-100 py-2 z-50">

                            {{-- User info --}}
                            <div class="px-4 py-3 border-b border-gray-100">
                                <p class="text-sm font-semibold text-gray-900">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</p>
                            </div>

                            {{-- Dashboard theo role --}}
                            @if(Auth::user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-blue-600 font-medium hover:bg-blue-50 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                                </svg>
                                Admin Dashboard
                            </a>
                            <div class="my-1 border-t border-gray-100"></div>
                            @elseif(Auth::user()->isHotelOwner())
                            <a href="{{ route('owner.dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-blue-600 font-medium hover:bg-blue-50 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                </svg>
                                Owner Dashboard
                            </a>
                            <div class="my-1 border-t border-gray-100"></div>
                            @endif

                            <a href="#" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                Personal Data
                            </a>
                            <a href="#" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                </svg>
                                Payment
                            </a>
                            <a href="#" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                                Trips
                            </a>
                            <a href="#" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                </svg>
                                Wish Lists
                            </a>
                            <a href="#" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                </svg>
                                Reviews
                            </a>

                            {{-- Become a host (chỉ hiện với user thường) --}}
                            @if(Auth::user()->isUser())
                            <div class="my-1 border-t border-gray-100"></div>
                            <a href="{{ route('owner-request.create') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-amber-600 font-medium hover:bg-amber-50 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                                Become a host
                            </a>
                            @endif

                            <div class="my-1 border-t border-gray-100"></div>

                            {{-- Logout --}}
                            <button type="button" onclick="doLogout()" class="flex w-full items-center gap-3 px-4 py-2.5 text-sm text-red-500 hover:bg-red-50 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                                Log out
                            </button>
                            <form id="logout-form" method="POST" action="{{ route('logout') }}" style="display:none;">
                                @csrf
                            </form>

                        </el-menu>
                    </el-dropdown>
                </div>
                @endauth

                @guest
                <div class="space-x-4 flex items-center">
                    <button type="button" class="flex items-center space-x-3">
                        <img src="https://flagcdn.com/w20/us.png" alt="USD" class="w-7 h-7 rounded-full">
                        <span class="text-lg font-bold text-blue-500 hover:text-yellow-500">USD</span>
                    </button>
                    <button type="button" class="p-2">
                        <svg class="w-7 h-8 text-blue-500 hover:text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                    </button>
                    <button type="button" command="show-modal" commandfor="auth-dialog" class="rounded-2xl px-6 py-2 text-base font-semibold text-white bg-[#0057FF] hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-yellow-500">Login</button>
                </div>
                @endguest

            </div>
        </div>
    </div>
</nav>
<script>
    function doLogout() {
        document.getElementById('logout-form').submit();
    }
</script>