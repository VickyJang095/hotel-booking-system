<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') - Tripto</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 16px;
            border-radius: 12px;
            font-size: .875rem;
            font-weight: 600;
            color: #6b7280;
            transition: all .2s;
        }

        .sidebar-link:hover {
            background: #f3f4f6;
            color: #111827;
        }

        .sidebar-link.active {
            background: #eff6ff;
            color: #2563eb;
        }

        .sidebar-link svg {
            width: 18px;
            height: 18px;
        }

        .stat-card {
            background: #fff;
            border: 1px solid #f3f4f6;
            border-radius: 16px;
            padding: 20px;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            padding: 3px 10px;
            border-radius: 9999px;
            font-size: .75rem;
            font-weight: 600;
        }

        .badge-admin {
            background: #fef3c7;
            color: #92400e;
        }

        .badge-owner {
            background: #dbeafe;
            color: #1e40af;
        }

        .badge-user {
            background: #f3f4f6;
            color: #374151;
        }

        .form-input {
            width: 100%;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: 9px 13px;
            font-size: .875rem;
            outline: none;
            transition: border-color .2s, box-shadow .2s;
        }

        .form-input:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, .1);
        }

        .btn-primary {
            background: #2563eb;
            color: #fff;
            font-weight: 700;
            font-size: .875rem;
            padding: 9px 20px;
            border-radius: 10px;
            transition: background .2s;
        }

        .btn-primary:hover {
            background: #1d4ed8;
        }

        .btn-danger {
            background: #fee2e2;
            color: #dc2626;
            font-weight: 600;
            font-size: .8125rem;
            padding: 6px 14px;
            border-radius: 8px;
            transition: all .2s;
        }

        .btn-danger:hover {
            background: #dc2626;
            color: #fff;
        }

        .btn-edit {
            background: #eff6ff;
            color: #2563eb;
            font-weight: 600;
            font-size: .8125rem;
            padding: 6px 14px;
            border-radius: 8px;
            transition: all .2s;
        }

        .btn-edit:hover {
            background: #2563eb;
            color: #fff;
        }

        .table-row:hover td {
            background: #f9fafb;
        }

        ::-webkit-scrollbar {
            width: 4px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: #d1d5db;
            border-radius: 4px;
        }
    </style>
    @stack('styles')
</head>

<body class="bg-gray-50">
    <div class="flex min-h-screen">

        <aside class="w-60 bg-white border-r border-gray-100 flex flex-col shrink-0 fixed top-0 left-0 h-full z-20">
            <div class="px-5 py-5 border-b border-gray-100">
                <a href="{{ route('home') }}" class="flex items-center gap-1.5">
                    <span class="text-blue-600 font-black text-xl tracking-tight">tripto</span>
                    <span class="w-2 h-2 rounded-full bg-yellow-400 mb-1"></span>
                </a>
                <p class="text-xs text-gray-400 mt-1 font-medium">
                    @if(auth()->user()->role === 'admin') {{ __('admin.role_admin') }}
                    @else {{ __('admin.role_owner') }}
                    @endif
                </p>
            </div>

            <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
                @if(auth()->user()->role === 'admin')
                @php
                $pendingOwnerRequests = \App\Models\OwnerRequest::where('status','pending')->count();
                $pendingHotels = \App\Models\Hotel::where('status','pending_review')->count();
                @endphp

                <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    {{ __('admin.dashboard') }}
                </a>

                <a href="{{ route('admin.hotels') }}" class="sidebar-link {{ request()->routeIs('admin.hotels*') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    <span class="flex-1">{{ __('admin.hotels') }}</span>
                    @if($pendingHotels > 0)
                    <span class="text-xs font-bold bg-amber-100 text-amber-700 px-2 py-0.5 rounded-full">{{ $pendingHotels }}</span>
                    @endif
                </a>

                <a href="{{ route('admin.users') }}" class="sidebar-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    {{ __('admin.users') }}
                </a>

                <a href="{{ route('admin.owner-requests') }}" class="sidebar-link {{ request()->routeIs('admin.owner-requests*') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span class="flex-1">{{ __('admin.owner_requests') }}</span>
                    @if($pendingOwnerRequests > 0)
                    <span class="text-xs font-bold bg-red-100 text-red-600 px-2 py-0.5 rounded-full">{{ $pendingOwnerRequests }}</span>
                    @endif
                </a>

                <a href="{{ route('admin.bookings') }}" class="sidebar-link {{ request()->routeIs('admin.bookings') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    {{ __('admin.bookings') }}
                </a>

                @else
                <a href="{{ route('owner.dashboard') }}" class="sidebar-link {{ request()->routeIs('owner.dashboard') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    {{ __('admin.dashboard') }}
                </a>
                <a href="{{ route('owner.hotel.edit') }}" class="sidebar-link {{ request()->routeIs('owner.hotel*') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    {{ __('admin.my_hotel') }}
                </a>
                <a href="{{ route('owner.rooms') }}" class="sidebar-link {{ request()->routeIs('owner.rooms*') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                    </svg>
                    {{ __('admin.rooms') }}
                </a>
                <a href="{{ route('owner.bookings') }}" class="sidebar-link {{ request()->routeIs('owner.bookings') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    {{ __('admin.bookings') }}
                </a>
                @endif
            </nav>

            <div class="px-4 py-4 border-t border-gray-100">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-9 h-9 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold text-sm shrink-0">
                        {{ strtoupper(substr(auth()->user()->name ?? auth()->user()->email ?? 'U', 0, 1)) }}
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm font-semibold text-gray-800 truncate">{{ auth()->user()->name ?? 'User' }}</p>
                        <span class="badge {{ auth()->user()->role === 'admin' ? 'badge-admin' : 'badge-owner' }}">
                            {{ auth()->user()->role === 'admin' ? __('admin.role_admin') : __('admin.role_owner') }}
                        </span>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-2 text-sm text-gray-500 hover:text-red-500 transition font-medium py-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        {{ __('common.logout') }}
                    </button>
                </form>
            </div>
        </aside>

        <div class="flex-1 ml-60">
            <header class="bg-white border-b border-gray-100 px-6 py-4 flex items-center justify-between sticky top-0 z-10">
                <div>
                    <h1 class="text-lg font-bold text-gray-900">@yield('page-title', __('admin.dashboard'))</h1>
                    <p class="text-xs text-gray-400">@yield('page-subtitle', '')</p>
                </div>
                <a href="{{ route('home') }}" target="_blank" class="text-xs text-blue-600 font-semibold hover:underline flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                    </svg>
                    {{ __('admin.view_site') }}
                </a>
            </header>

            @if(session('success'))
            <div class="mx-6 mt-4 bg-green-50 border border-green-200 text-green-700 text-sm font-medium px-4 py-3 rounded-xl flex items-center gap-2">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                {{ session('success') }}
            </div>
            @endif
            @if(session('error'))
            <div class="mx-6 mt-4 bg-red-50 border border-red-200 text-red-700 text-sm font-medium px-4 py-3 rounded-xl flex items-center gap-2">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
                {{ session('error') }}
            </div>
            @endif

            <main class="p-6">
                @yield('content')
            </main>
        </div>
    </div>
    @stack('scripts')
</body>

</html>