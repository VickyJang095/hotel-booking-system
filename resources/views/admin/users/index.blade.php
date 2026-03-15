@extends('layouts.panel')
@section('title', 'Users')
@section('page-title', 'Users')
@section('page-subtitle', 'Manage all registered users')

@section('content')
{{-- FILTERS --}}
<div class="flex flex-wrap gap-3 mb-5">
    <form method="GET" action="{{ route('admin.users') }}" class="flex gap-2 flex-wrap">
        <input type="text" name="search" value="{{ request('search') }}"
               placeholder="Search name or phone…" class="form-input w-56">
        <select name="role" class="form-input w-36">
            <option value="">All roles</option>
            <option value="user"        {{ request('role') === 'user'        ? 'selected' : '' }}>User</option>
            <option value="hotel_owner" {{ request('role') === 'hotel_owner' ? 'selected' : '' }}>Hotel Owner</option>
            <option value="admin"       {{ request('role') === 'admin'       ? 'selected' : '' }}>Admin</option>
        </select>
        <button type="submit" class="btn-primary">Search</button>
        @if(request('search') || request('role'))
        <a href="{{ route('admin.users') }}" class="btn-edit">Clear</a>
        @endif
    </form>
</div>

<div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
    <table class="w-full text-sm">
        <thead>
            <tr class="border-b border-gray-100 bg-gray-50">
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">User</th>
                <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Phone</th>
                <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Role</th>
                <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Joined</th>
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
                    <form method="POST" action="{{ route('admin.users.role', $user->id) }}"
                          onchange="this.submit()">
                        @csrf @method('PUT')
                        <select name="role"
                                class="text-xs font-semibold border border-gray-200 rounded-lg px-2 py-1 outline-none
                                       {{ $user->role === 'admin' ? 'text-amber-700 bg-amber-50' : ($user->role === 'hotel_owner' ? 'text-blue-700 bg-blue-50' : 'text-gray-700 bg-gray-50') }}">
                            <option value="user"        {{ $user->role === 'user'        ? 'selected' : '' }}>User</option>
                            <option value="hotel_owner" {{ $user->role === 'hotel_owner' ? 'selected' : '' }}>Hotel Owner</option>
                            <option value="admin"       {{ $user->role === 'admin'       ? 'selected' : '' }}>Admin</option>
                        </select>
                    </form>
                </td>
                <td class="px-4 py-3 text-gray-400 text-xs">{{ $user->created_at?->format('M d, Y') ?? '—' }}</td>
                <td class="px-4 py-3">
                    @if($user->id !== auth()->id())
                    <form method="POST" action="{{ route('admin.users.delete', $user->id) }}"
                          onsubmit="return confirm('Delete this user?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-danger">Delete</button>
                    </form>
                    @else
                    <span class="text-xs text-gray-300">You</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr><td colspan="5" class="text-center py-12 text-gray-400 text-sm">No users found.</td></tr>
            @endforelse
        </tbody>
    </table>

    @if($users->hasPages())
    <div class="px-5 py-4 border-t border-gray-100">
        {{ $users->links() }}
    </div>
    @endif
</div>
@endsection