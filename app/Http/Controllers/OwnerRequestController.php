<?php

namespace App\Http\Controllers;

use App\Models\OwnerRequest;
use App\Models\User;
use App\Models\Hotel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class OwnerRequestController extends Controller
{
    // Hiển thị form đăng ký
    public function create()
    {
        /** @var User $user */
        $user = Auth::user();

        // Nếu đã là owner rồi thì redirect
        if ($user->isHotelOwner()) {
            return redirect()->route('owner.dashboard')
                ->with('info', 'Bạn đã là hotel owner.');
        }

        // Kiểm tra đã có đơn pending chưa
        $existingRequest = OwnerRequest::where('user_id', $user->id)
            ->where('status', 'pending')
            ->first();

        return view('owner-request.create', compact('user', 'existingRequest'));
    }

    // Gửi đơn đăng ký
    public function store(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        // Không cho gửi nếu đã pending
        $already = OwnerRequest::where('user_id', $user->id)
            ->where('status', 'pending')
            ->exists();

        if ($already) {
            return back()->with('error', 'Bạn đã có đơn đang chờ duyệt.');
        }

        $request->validate([
            'hotel_name'  => 'required|string|max:255',
            'phone'       => 'required|string|max:20',
            'city'        => 'required|string|max:100',
            'address'     => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        OwnerRequest::create([
            'user_id'     => $user->id,
            'hotel_name'  => $request->hotel_name,
            'phone'       => $request->phone,
            'city'        => $request->city,
            'address'     => $request->address,
            'description' => $request->description,
            'status'      => 'pending',
        ]);

        return redirect()->route('owner-request.create')
            ->with('success', 'Đơn đăng ký đã được gửi. Chúng tôi sẽ xem xét trong vòng 1-3 ngày làm việc.');
    }
}