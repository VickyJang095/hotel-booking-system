<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\User;
use App\Models\OwnerRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    // ── DASHBOARD ────────────────────────────────────────────
    public function dashboard()
    {
        $stats = [
            'total_hotels'           => Hotel::count(),
            'total_users'            => User::where('role', 'user')->count(),
            'total_owners'           => User::where('role', 'hotel_owner')->count(),
            'total_bookings'         => 0,
            'pending_hotels'         => Hotel::where('status', 'pending_review')->count(),
            'pending_owner_requests' => OwnerRequest::where('status', 'pending')->count(),
        ];
        $recentHotels = Hotel::latest()->take(5)->get();
        $recentUsers  = User::latest()->take(5)->get();
        return view('admin.dashboard', compact('stats', 'recentHotels', 'recentUsers'));
    }

    // ── HOTELS ───────────────────────────────────────────────
    public function hotels(Request $request)
    {
        $query = Hotel::query();
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('city', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('city'))   $query->where('city', $request->city);
        if ($request->filled('status')) $query->where('status', $request->status);
        $hotels = $query->latest()->paginate(15)->withQueryString();
        $cities = Hotel::distinct()->pluck('city');
        return view('admin.hotels.index', compact('hotels', 'cities'));
    }

    public function createHotel()
    {
        return view('admin.hotels.form', ['hotel' => null]);
    }

    public function storeHotel(Request $request)
    {
        $data = $this->validateHotel($request);
        $data['amenities']             = $request->amenities ?? [];
        $data['free_cancellation']     = $request->boolean('free_cancellation');
        $data['instant_booking']       = $request->boolean('instant_booking');
        $data['pay_at_property']       = $request->boolean('pay_at_property');
        $data['wheelchair_accessible'] = $request->boolean('wheelchair_accessible');
        $data['image']                 = $this->handleImage($request);
        $data['status']                = 'approved';
        Hotel::create($data);
        return redirect()->route('admin.hotels')->with('success', 'Hotel created successfully.');
    }

    public function editHotel($id)
    {
        $hotel = Hotel::findOrFail($id);
        return view('admin.hotels.form', compact('hotel'));
    }

    public function updateHotel(Request $request, $id)
    {
        $hotel = Hotel::findOrFail($id);
        $data  = $this->validateHotel($request);
        $data['amenities']             = $request->amenities ?? [];
        $data['free_cancellation']     = $request->boolean('free_cancellation');
        $data['instant_booking']       = $request->boolean('instant_booking');
        $data['pay_at_property']       = $request->boolean('pay_at_property');
        $data['wheelchair_accessible'] = $request->boolean('wheelchair_accessible');
        if ($request->hasFile('image_file') || $request->filled('image_url')) {
            $data['image'] = $this->handleImage($request);
        }
        $hotel->update($data);
        return redirect()->route('admin.hotels')->with('success', 'Hotel updated.');
    }

    public function deleteHotel($id)
    {
        Hotel::findOrFail($id)->delete();
        return back()->with('success', 'Hotel deleted.');
    }

    // ── DUYỆT HOTEL ──────────────────────────────────────────
    public function approveHotel($id)
    {
        Hotel::findOrFail($id)->update([
            'status'        => 'approved',
            'reject_reason' => null,
        ]);
        return back()->with('success', 'Hotel đã được duyệt và hiển thị công khai.');
    }

    public function rejectHotel(Request $request, $id)
    {
        $request->validate(['reason' => 'required|string|max:500']);
        Hotel::findOrFail($id)->update([
            'status'        => 'rejected',
            'reject_reason' => $request->reason,
        ]);
        return back()->with('success', 'Hotel đã bị từ chối.');
    }

    // ── USERS ────────────────────────────────────────────────
    public function users(Request $request)
    {
        $query = User::query();
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('email', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('role')) $query->where('role', $request->role);
        $users = $query->latest()->paginate(20)->withQueryString();
        return view('admin.users.index', compact('users'));
    }

    public function updateUserRole(Request $request, $id)
    {
        $request->validate(['role' => 'required|in:user,hotel_owner,admin']);
        User::findOrFail($id)->update(['role' => $request->role]);
        return back()->with('success', 'Role updated.');
    }

    public function deleteUser($id)
    {
        User::findOrFail($id)->delete();
        return back()->with('success', 'User deleted.');
    }

    // ── OWNER REQUESTS ────────────────────────────────────────
    public function ownerRequests(Request $request)
    {
        $query = OwnerRequest::with('user');
        if ($request->filled('status')) $query->where('status', $request->status);
        $requests = $query->latest()->paginate(20)->withQueryString();
        return view('admin.owner-requests.index', compact('requests'));
    }

    public function approveOwnerRequest($id)
    {
        /** @var User $admin */
        $admin = Auth::user();

        $ownerRequest = OwnerRequest::with('user')->findOrFail($id);

        $ownerRequest->update([
            'status'      => 'approved',
            'reviewed_at' => now(),
            'reviewed_by' => $admin->id,
        ]);

        // Đổi role user thành hotel_owner
        $ownerRequest->user->update(['role' => 'hotel_owner']);

        // Tạo hotel trống cho owner
        Hotel::create([
            'owner_id'            => $ownerRequest->user_id,
            'name'                => $ownerRequest->hotel_name,
            'city'                => $ownerRequest->city,
            'address'             => $ownerRequest->address ?? '',
            'status'              => 'pending_review',
            'price_per_night'     => 0,
            'star_rating'         => 3,
            'total_rooms'         => 1,
            'max_guests_per_room' => 2,
        ]);

        return back()->with('success', 'Đã duyệt. User đã trở thành hotel owner.');
    }

    public function rejectOwnerRequest(Request $request, $id)
    {
        $request->validate(['reason' => 'required|string|max:500']);

        /** @var User $admin */
        $admin = Auth::user();

        OwnerRequest::findOrFail($id)->update([
            'status'        => 'rejected',
            'reject_reason' => $request->reason,
            'reviewed_at'   => now(),
            'reviewed_by'   => $admin->id,
        ]);

        return back()->with('success', 'Đã từ chối đơn đăng ký.');
    }

    // ── BOOKINGS ─────────────────────────────────────────────
    public function bookings()
    {
        $bookings = collect();
        return view('admin.bookings.index', compact('bookings'));
    }

    // ── HELPERS ──────────────────────────────────────────────
    private function validateHotel(Request $request): array
    {
        return $request->validate([
            'name'                 => 'required|string|max:255',
            'city'                 => 'required|string|max:100',
            'address'              => 'nullable|string',
            'description'          => 'nullable|string',
            'price_per_night'      => 'required|numeric|min:0',
            'star_rating'          => 'required|integer|between:1,5',
            'rating'               => 'nullable|numeric|between:0,5',
            'review_count'         => 'nullable|integer|min:0',
            'total_rooms'          => 'required|integer|min:1',
            'max_guests_per_room'  => 'required|integer|min:1',
            'type'                 => 'nullable|string',
            'distance_from_centre' => 'nullable|numeric|min:0',
            'latitude'             => 'nullable|numeric',
            'longitude'            => 'nullable|numeric',
            'image_url'            => 'nullable|url',
            'image_file'           => 'nullable|image|max:2048',
        ]);
    }

    private function handleImage(Request $request): ?string
    {
        if ($request->hasFile('image_file')) {
            return $request->file('image_file')->store('hotels', 'public');
        }
        if ($request->filled('image_url')) {
            return $request->image_url;
        }
        return null;
    }
}
