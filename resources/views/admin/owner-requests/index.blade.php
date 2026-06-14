@extends('layouts.panel')
@section('title', __('admin.owner_requests'))

@section('content')
<div class="p-6">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-xl font-semibold text-gray-900">{{ __('admin.owner_requests') }}</h1>
            <p class="text-sm text-gray-500 mt-0.5">Xem xét và phê duyệt đơn đăng ký chủ khách sạn</p>
        </div>
        <form method="GET" class="flex items-center gap-2">
            <select name="status" onchange="this.form.submit()"
                class="rounded-lg border border-gray-300 text-sm px-3 py-2 focus:ring-2 focus:ring-blue-500">
                <option value="">{{ __('admin.all_status') }}</option>
                <option value="pending" {{ request('status') === 'pending'  ? 'selected' : '' }}>{{ __('common.pending') }}</option>
                <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>{{ __('common.approved') }}</option>
                <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>{{ __('common.rejected') }}</option>
            </select>
        </form>
    </div>

    @if(session('success'))
    <div class="mb-4 flex items-center gap-2 bg-green-50 border border-green-200 text-green-800 rounded-xl px-4 py-3 text-sm">
        <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
        </svg>
        {{ session('success') }}
    </div>
    @endif

    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
        @if($requests->isEmpty())
        <div class="text-center py-16 text-gray-400">
            <svg class="w-12 h-12 mx-auto mb-3 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <p class="text-sm">{{ __('admin.no_requests') }}</p>
        </div>
        @else
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="text-left px-5 py-3 text-xs font-medium text-gray-500 uppercase tracking-wide">{{ __('admin.applicant') }}</th>
                    <th class="text-left px-5 py-3 text-xs font-medium text-gray-500 uppercase tracking-wide">{{ __('admin.hotel_name') }}</th>
                    <th class="text-left px-5 py-3 text-xs font-medium text-gray-500 uppercase tracking-wide">{{ __('admin.city') }}</th>
                    <th class="text-left px-5 py-3 text-xs font-medium text-gray-500 uppercase tracking-wide">{{ __('admin.application_date') }}</th>
                    <th class="text-left px-5 py-3 text-xs font-medium text-gray-500 uppercase tracking-wide">{{ __('admin.status') }}</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($requests as $req)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-5 py-4">
                        <p class="font-medium text-gray-900">{{ $req->user->name }}</p>
                        <p class="text-xs text-gray-400">{{ $req->user->email }}</p>
                    </td>
                    <td class="px-5 py-4">
                        <p class="text-gray-900">{{ $req->hotel_name }}</p>
                        @if($req->address)
                        <p class="text-xs text-gray-400 truncate max-w-[200px]">{{ $req->address }}</p>
                        @endif
                    </td>
                    <td class="px-5 py-4 text-gray-700">{{ $req->city }}</td>
                    <td class="px-5 py-4 text-gray-500 text-xs">{{ $req->created_at->format('d/m/Y') }}</td>
                    <td class="px-5 py-4">
                        @if($req->status === 'pending')
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> {{ __('common.pending') }}
                        </span>
                        @elseif($req->status === 'approved')
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> {{ __('common.approved') }}
                        </span>
                        @else
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                            <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> {{ __('common.rejected') }}
                        </span>
                        @endif
                    </td>
                    <td class="px-5 py-4">
                        @if($req->status === 'pending')
                        <div class="flex items-center gap-2 justify-end">
                            <form action="{{ route('admin.owner-requests.approve', $req->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="px-3 py-1.5 rounded-lg bg-green-600 hover:bg-green-700 text-white text-xs font-medium transition">
                                    {{ __('admin.approve') }}
                                </button>
                            </form>
                            <button data-id="{{ $req->id }}" onclick="openRejectModal(this.dataset.id)"
                                class="px-3 py-1.5 rounded-lg bg-red-50 hover:bg-red-100 text-red-600 text-xs font-medium border border-red-200 transition">
                                {{ __('admin.reject') }}
                            </button>
                        </div>
                        @elseif($req->status === 'rejected' && $req->reject_reason)
                        <p class="text-xs text-gray-400 max-w-[180px] truncate" title="{{ $req->reject_reason }}">
                            {{ $req->reject_reason }}
                        </p>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @if($requests->hasPages())
        <div class="px-5 py-4 border-t border-gray-100">{{ $requests->links() }}</div>
        @endif
        @endif
    </div>
</div>

<div id="rejectModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md mx-4 p-6">
        <h3 class="text-base font-semibold text-gray-900 mb-4">{{ __('admin.reject_owner') }}</h3>
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
        document.getElementById('rejectForm').action = '/admin/owner-requests/' + id + '/reject';
        document.getElementById('rejectModal').classList.remove('hidden');
    }

    function closeRejectModal() {
        document.getElementById('rejectModal').classList.add('hidden');
    }
</script>
@endsection