@extends('layouts.panel')
@section('title', __('admin.hotels'))
@section('page-title', __('admin.hotels'))
@section('page-subtitle', 'Quản lý tất cả khách sạn trên hệ thống')

@section('content')
{{-- FILTERS --}}
<div class="flex flex-wrap gap-3 mb-5">
    <form method="GET" action="{{ route('admin.hotels') }}" class="flex gap-2 flex-wrap">
        <input type="text" name="search" value="{{ request('search') }}"
               placeholder="{{ __('admin.search_placeholder') }}" class="form-input w-56">
        <select name="city" class="form-input w-36">
            <option value="">{{ __('admin.all_cities') }}</option>
            @foreach($cities ?? [] as $city)
            <option value="{{ $city }}" {{ request('city') === $city ? 'selected' : '' }}>{{ $city }}</option>
            @endforeach
        </select>
        <select name="status" class="form-input w-36">
            <option value="">{{ __('admin.all_status') }}</option>
            <option value="approved"       {{ request('status') === 'approved'       ? 'selected' : '' }}>{{ __('common.approved') }}</option>
            <option value="pending_review" {{ request('status') === 'pending_review' ? 'selected' : '' }}>{{ __('common.pending') }}</option>
            <option value="rejected"       {{ request('status') === 'rejected'       ? 'selected' : '' }}>{{ __('common.rejected') }}</option>
        </select>
        <button type="submit" class="btn-primary">{{ __('common.search') }}</button>
        @if(request('search') || request('city') || request('status'))
        <a href="{{ route('admin.hotels') }}" class="btn-edit">{{ __('common.clear') }}</a>
        @endif
    </form>
</div>

<div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
    <table class="w-full text-sm">
        <thead>
            <tr class="border-b border-gray-100 bg-gray-50">
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">{{ __('admin.hotel_name') }}</th>
                <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">{{ __('admin.city') }}</th>
                <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">{{ __('admin.stars') }}</th>
                <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">{{ __('admin.price') }}</th>
                <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">{{ __('admin.status') }}</th>
                <th class="px-4 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($hotels as $hotel)
            <tr class="table-row">
                <td class="px-5 py-3">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl overflow-hidden shrink-0 bg-gray-100">
                            <img src="{{ $hotel->image_url }}" alt="{{ $hotel->name }}"
                                class="w-full h-full object-cover"
                                onerror="this.src='https://images.unsplash.com/photo-1566073771259-6a8506099945?w=80&q=60'">
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900">{{ $hotel->name }}</p>
                            <p class="text-xs text-gray-400">ID #{{ $hotel->id }}</p>
                        </div>
                    </div>
                </td>
                <td class="px-4 py-3 text-gray-600">{{ $hotel->city }}</td>
                <td class="px-4 py-3">
                    <div class="flex gap-0.5">
                        @for($s=0;$s<$hotel->star_rating;$s++)
                        <svg class="w-3.5 h-3.5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        @endfor
                    </div>
                </td>
                <td class="px-4 py-3 text-gray-600">${{ number_format($hotel->price_per_night) }}</td>
                <td class="px-4 py-3">
                    @if($hotel->status === 'approved')
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>{{ __('common.approved') }}
                    </span>
                    @elseif($hotel->status === 'pending_review')
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>{{ __('common.pending') }}
                    </span>
                    @else
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                        <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>{{ __('common.rejected') }}
                    </span>
                    @endif
                </td>
                <td class="px-4 py-3">
                    <div class="flex items-center gap-2 justify-end">
                        @if($hotel->status === 'pending_review')
                        <form method="POST" action="{{ route('admin.hotels.approve', $hotel->id) }}">
                            @csrf
                            <button type="submit" class="px-3 py-1.5 rounded-lg bg-green-600 hover:bg-green-700 text-white text-xs font-medium transition">
                                {{ __('admin.approve') }}
                            </button>
                        </form>
                        <button onclick="openRejectModal({{ $hotel->id }})"
                            class="px-3 py-1.5 rounded-lg bg-red-50 hover:bg-red-100 text-red-600 text-xs font-medium border border-red-200 transition">
                            {{ __('admin.reject') }}
                        </button>
                        @endif
                        <a href="{{ route('hotels.show', $hotel->id) }}" target="_blank" class="btn-edit">
                            {{ __('common.view') }}
                        </a>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="text-center py-12 text-gray-400 text-sm">{{ __('admin.no_hotels_found') }}</td></tr>
            @endforelse
        </tbody>
    </table>

    @if(isset($hotels) && $hotels->hasPages())
    <div class="px-5 py-4 border-t border-gray-100">{{ $hotels->links() }}</div>
    @endif
</div>

{{-- Reject Modal --}}
<div id="rejectModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md mx-4 p-6">
        <h3 class="text-base font-semibold text-gray-900 mb-4">{{ __('admin.reject_hotel') }}</h3>
        <form id="rejectForm" method="POST">
            @csrf
            <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('admin.reject_reason') }} <span class="text-red-500">*</span></label>
            <textarea name="reason" rows="4" required
                placeholder="{{ __('admin.reject_reason_ph') }}"
                class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100 resize-none"></textarea>
            <div class="flex gap-3 mt-4">
                <button type="button" onclick="closeRejectModal()"
                    class="flex-1 rounded-xl border border-gray-300 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
                    {{ __('common.cancel') }}
                </button>
                <button type="submit"
                    class="flex-1 rounded-xl bg-red-600 hover:bg-red-700 text-white py-2.5 text-sm font-medium transition">
                    {{ __('admin.reject') }}
                </button>
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