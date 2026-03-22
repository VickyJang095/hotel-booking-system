<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function show()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        return view('profile.index', compact('user'));
    }

    public function update(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $request->validate([
            'first_name'    => 'nullable|string|max:100',
            'last_name'     => 'nullable|string|max:100',
            'phone'         => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date|before:today',
            'gender'        => 'nullable|in:male,female,other',
            'address'       => 'nullable|string|max:255',
            'city'          => 'nullable|string|max:100',
            'avatar'        => 'nullable|image|max:2048',
        ]);

        // Upload avatar
        if ($request->hasFile('avatar')) {
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $path;
        }

        $user->update([
            'first_name'    => $request->first_name,
            'last_name'     => $request->last_name,
            'name'          => trim(($request->first_name ?? '') . ' ' . ($request->last_name ?? '')) ?: $user->name,
            'phone'         => $request->phone,
            'date_of_birth' => $request->date_of_birth,
            'gender'        => $request->gender,
            'address'       => $request->address,
            'city'          => $request->city,
        ]);

        return back()->with('success', __('profile.updated'));
    }

    public function updatePayment(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $request->validate([
            'card_holder_name'   => 'nullable|string|max:100',
            'card_number'        => 'nullable|string|min:16|max:19',
            'card_expiry'        => 'nullable|string|max:5',
            'card_type'          => 'nullable|in:visa,mastercard,amex,jcb',
            'billing_address'    => 'nullable|string|max:255',
            'billing_city'       => 'nullable|string|max:100',
            'billing_postal_code' => 'nullable|string|max:20',
        ]);

        // Chỉ lưu 4 số cuối của thẻ
        $masked = null;
        if ($request->filled('card_number')) {
            $raw = preg_replace('/\D/', '', $request->card_number);
            $masked = '****' . substr($raw, -4);
        }

        $user->update([
            'card_holder_name'    => $request->card_holder_name,
            'card_number_masked'  => $masked ?? $user->card_number_masked,
            'card_expiry'         => $request->card_expiry,
            'card_type'           => $request->card_type,
            'billing_address'     => $request->billing_address,
            'billing_city'        => $request->billing_city,
            'billing_postal_code' => $request->billing_postal_code,
        ]);

        return back()->with('success', __('profile.payment_updated'));
    }

    public function trips()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $bookings = \App\Models\Booking::with('hotel')
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(10);

        return view('profile.trips', compact('bookings'));
    }
}
