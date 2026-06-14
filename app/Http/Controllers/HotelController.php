<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HotelController extends Controller
{
    public function show($id)
    {
        $hotel = Hotel::findOrFail($id);
        return view('hotels.hotel-detail', compact('hotel'));
    }

    // Ví dụ nếu có trang admin tạo/sửa hotel
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'           => 'required|string',
            'image_file'     => 'nullable|image|max:5120', // upload file
            'image_url'      => 'nullable|url',            // hoặc nhập URL
            // ... các field khác
        ]);

        $data['image'] = $this->handleImage($request);

        Hotel::create($data);
        return redirect()->back()->with('success', 'Hotel created!');
    }

    public function update(Request $request, Hotel $hotel)
    {
        $request->validate([
            'image_file' => 'nullable|image|max:5120',
            'image_url'  => 'nullable|url',
        ]);

        $newImage = $this->handleImage($request);

        if ($newImage) {
            // Xóa ảnh cũ nếu là file local
            if ($hotel->image && !str_starts_with($hotel->image, 'http')) {
                Storage::disk('public')->delete($hotel->image);
            }
            $hotel->image = $newImage;
        }

        $hotel->save();
        return redirect()->back()->with('success', 'Updated!');
    }

    private function handleImage(Request $request): ?string
    {
        // Ưu tiên file upload
        if ($request->hasFile('image_file')) {
            return $request->file('image_file')
                ->store('hotels', 'public'); // lưu vào storage/app/public/hotels/
        }

        // Nếu không có file thì dùng URL
        if ($request->filled('image_url')) {
            return $request->image_url;
        }

        return null;
    }
}
