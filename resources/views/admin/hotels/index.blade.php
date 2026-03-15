@extends('layouts.panel')

@section('title', 'Manage Hotels')

@section('content')
<div class="p-6">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-xl font-semibold text-gray-900">Hotels</h1>
            <p class="text-sm text-gray-500 mt-0.5">Manage and approve hotel listings</p>
        </div>
        <a href="{{ route('admin.hotels.create') }}"
            class="flex items-center gap-2 px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Add Hotel
        </a>
    </div>

    @if(session('success'))
    <div class="mb-4 flex items-center gap-2 bg-green-50 border border-green-200 text-green-800 rounded-xl px-4 py-3 text-sm">
        <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
        </svg>
        {{ session('success') }}
    </div>
    @endif

    <form method="GET" class="flex flex-wrap gap-3 mb-5">
        <input type="text" name="search" value="{{ request('search') }}"
            placeholder="Search by name or city..."
            class="rounded-xl border border-gray-300 text-sm px-4 py-2 focus:ring-2 focus:ring-blue-500 w-64">
        <select name="city" class="rounded-xl border border-gray-300 text-sm px-3 py-2">
            <option value="">All cities</option>
            @foreach($cities as $city)
            <option value="{{ $city }}" {{ request('city') === $city ? 'selected' : '' }}>{{ $city }}</option>
            @endforeach
        </select>
        <select name="status" class="rounded-xl border border-gray-300 text-sm px-3 py-2">
            <option value="">All status</option>
            <option value="pending_review" {{ request('status') === 'pending_review' ? 'selected' : '' }}>Pending review</option>
            <option value="approved" {{ request('status') === 'approved'       ? 'selected' : '' }}>Approved</option>
            <option value="rejected" {{ request('status') === 'rejected'       ? 'selected' : '' }}>Rejected</option>
        </select>
        <button type="submit" class="px-4 py-2 rounded-xl bg-gray-900 text-white text-sm font-medium hover:bg-gray-700 transition">Search</button>
        @if(request()->hasAny(['search','city','status']))
        <a href="{{ route('admin.hotels') }}" class="px-4 py-2 rounded-xl border border-gray-300 text-sm text-gray-600 hover:bg-gray-50 transition">Clear</a>
        @endif
    </form>

    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
        @if($hotels->isEmpty())
        <div class="text-center py-16 text-gray-400">
            <svg class="w-12 h-12 mx-auto mb-3 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1" />
            </svg>
            <p class="text-sm">No hotels found</p>
        </div>
        @else
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="text-left px-5 py-3 text-xs font-medium text-gray-500 uppercase tracking-wide">Hotel</th>
                    <th class="text-left px-5 py-3 text-xs font-medium text-gray-500 uppercase tracking-wide">City</th>
                    <th class="text-left px-5 py-3 text-xs font-medium text-gray-500 uppercase tracking-wide">Price</th>
                    <th class="text-left px-5 py-3 text-xs font-medium text-gray-500 uppercase tracking-wide">Stars</th>
                    <th class="text-left px-5 py-3 text-xs font-medium text-gray-500 uppercase tracking-wide">Status</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($hotels as $hotel)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-5 py-4">
                        <div class="flex items-center gap-3">
                            <img src="{{ $hotel->image_url }}" alt="{{ $hotel->name }}" class="w-12 h-10 rounded-lg object-cover shrink-0">
                            <span class="font-medium text-gray-900">{{ $hotel->name }}</span>
                        </div>
                    </td>
                    <td class="px-5 py-4 text-gray-600">{{ $hotel->city }}</td>
                    <td class="px-5 py-4 text-gray-600">${{ number_format($hotel->price_per_night) }}</td>
                    <td class="px-5 py-4">
                        <div class="flex items-center gap-0.5">
                            @for($i = 0; $i < $hotel->star_rating; $i++)
                                <svg class="w-3.5 h-3.5 text-amber-400 fill-current" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                                @endfor
                        </div>
                    </td>
                    <td class="px-5 py-4">
                        @if($hotel->status === 'pending_review')
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Pending
                        </span>
                        @elseif($hotel->status === 'approved')
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Approved
                        </span>
                        @else
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                            <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> Rejected
                        </span>
                        @endif
                    </td>
                    <td class="px-5 py-4">
                        <div class="flex items-center gap-2 justify-end">
                            @if($hotel->status === 'pending_review')
                            <form action="{{ route('admin.hotels.approve', $hotel->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="px-3 py-1.5 rounded-lg bg-green-600 hover:bg-green-700 text-white text-xs font-medium transition">Approve</button>
                            </form>
                            <button onclick="openRejectModal({{ $hotel->id }})"
                                class="px-3 py-1.5 rounded-lg bg-red-50 hover:bg-red-100 text-red-600 text-xs font-medium border border-red-200 transition">
                                Reject
                            </button>
                            @endif
                            <a href="{{ route('admin.hotels.edit', $hotel->id) }}"
                                class="px-3 py-1.5 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-medium transition">Edit</a>
                            <form action="{{ route('admin.hotels.delete', $hotel->id) }}" method="POST" onsubmit="return confirm('Delete this hotel?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="px-3 py-1.5 rounded-lg text-red-500 hover:bg-red-50 text-xs font-medium transition">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @if($hotels->hasPages())
        <div class="px-5 py-4 border-t border-gray-100">{{ $hotels->links() }}</div>
        @endif
        @endif
    </div>
</div>

<div id="rejectModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md mx-4 p-6">
        <h3 class="text-base font-semibold text-gray-900 mb-4">Reject Hotel</h3>
        <form id="rejectForm" method="POST">
            @csrf
            <label class="block text-sm font-medium text-gray-700 mb-2">Reason <span class="text-red-500">*</span></label>
            <textarea name="reason" rows="3" required placeholder="Why is this hotel being rejected?"
                class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100 resize-none"></textarea>
            <div class="flex gap-3 mt-4">
                <button type="button" onclick="closeRejectModal()"
                    class="flex-1 rounded-xl border border-gray-300 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 transition">Cancel</button>
                <button type="submit"
                    class="flex-1 rounded-xl bg-red-600 hover:bg-red-700 text-white py-2.5 text-sm font-medium transition">Reject</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openRejectModal(id) {
        document.getElementById('rejectForm').action = '/admin/hotels/' + id + '/reject';
        document.getElementById('rejectModal').classList.remove('hidden');
    }

    function closeRejectModal() {
        document.getElementById('rejectModal').classList.add('hidden');
    }
</script>
@endsection