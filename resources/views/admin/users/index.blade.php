@extends('layouts.panel')
@section('title', __('admin.users'))
@section('page-title', __('admin.users'))
@section('page-subtitle', 'Quản lý tất cả người dùng đã đăng ký')

@section('content')
<div class="flex flex-wrap gap-3 mb-5">
    <form method="GET" action="{{ route('admin.users') }}" class="flex gap-2 flex-wrap">
        <input type="text" name="search" value="{{ request('search') }}"
               placeholder="Tìm theo tên hoặc số điện thoại..." class="form-input w-56">
        <select name="role" class="form-input w-36">
            <option value="">{{ __('common.all') }} vai trò</option>
            <option value="user"        {{ request('role') === 'user'        ? 'selected' : '' }}>{{ __('admin.role_user') }}</option>
            <option value="hotel_owner" {{ request('role') === 'hotel_owner' ? 'selected' : '' }}>{{ __('admin.role_owner') }}</option>
            <option value="admin"       {{ request('role') === 'admin'       ? 'selected' : '' }}>{{ __('admin.role_admin') }}</option>
        </select>
        <button type="submit" class="btn-primary">{{ __('common.search') }}</button>
        @if(request('search') || request('role'))
        <a href="{{ route('admin.users') }}" class="btn-edit">{{ __('common.clear') }}</a>
        @endif
    </form>
</div>

<div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
    <table class="w-full text-sm">
        <thead>
            <tr class="border-b border-gray-100 bg-gray-50">
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">{{ __('admin.user_name') }}</th>
                <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">{{ __('admin.phone') }}</th>
                <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">{{ __('admin.role') }}</th>
                <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Ngày tham gia</th>
                <th class="px-4 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($users as $user)
            <tr class="table-row">
                <td class="px-5 py-3">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold text-sm shrink-0">
                            {{ strtoupper(substr($user->name ?? $user->phone ?? 'U', 0, 1)) }}
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900">{{ $user->name ?? '—' }}</p>
                            <p class="text-xs text-gray-400">ID #{{ $user->id }}</p>
                        </div>
                    </div>
                </td>
                <td class="px-4 py-3 text-gray-600">{{ $user->phone ?? '—' }}</td>
                <td class="px-4 py-3">
                    <form method="POST" action="{{ route('admin.users.role', $user->id) }}" onchange="this.submit()">
                        @csrf @method('PUT')
                        <select name="role" class="text-xs font-semibold border border-gray-200 rounded-lg px-2 py-1 outline-none
                            {{ $user->role === 'admin' ? 'text-amber-700 bg-amber-50' : ($user->role === 'hotel_owner' ? 'text-blue-700 bg-blue-50' : 'text-gray-700 bg-gray-50') }}">
                            <option value="user"        {{ $user->role === 'user'        ? 'selected' : '' }}>{{ __('admin.role_user') }}</option>
                            <option value="hotel_owner" {{ $user->role === 'hotel_owner' ? 'selected' : '' }}>{{ __('admin.role_owner') }}</option>
                            <option value="admin"       {{ $user->role === 'admin'       ? 'selected' : '' }}>{{ __('admin.role_admin') }}</option>
                        </select>
                    </form>
                </td>
                <td class="px-4 py-3 text-gray-400 text-xs">{{ $user->created_at?->format('d/m/Y') ?? '—' }}</td>
                <td class="px-4 py-3">
                    @if($user->id !== auth()->id())
                    <form method="POST" action="{{ route('admin.users.delete', $user->id) }}"
                          onsubmit="return confirm('Xóa người dùng này?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-danger">{{ __('common.delete') }}</button>
                    </form>
                    @else
                    <span class="text-xs text-gray-300">Bạn</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr><td colspan="5" class="text-center py-12 text-gray-400 text-sm">{{ __('admin.no_users_found') }}</td></tr>
            @endforelse
        </tbody>
    </table>
    @if($users->hasPages())
    <div class="px-5 py-4 border-t border-gray-100">{{ $users->links() }}</div>
    @endif
</div>
@endsection