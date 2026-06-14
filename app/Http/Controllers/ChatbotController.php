<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Hotel;

class ChatbotController extends Controller
{
    public function chat(Request $request)
    {
        set_time_limit(180);

        $request->validate([
            'message'  => 'required|string|max:500',
            'history'  => 'nullable|array',
            'hotel_id' => 'nullable|integer',
        ]);

        $userMessage = $request->message;
        $history     = $request->history ?? [];
        $hotelId     = $request->hotel_id;

        // ── Context khách sạn đang xem ──
        $hotelContext = '';
        if ($hotelId) {
            $hotel = Hotel::find($hotelId);
            if ($hotel) {
                $amenities = is_array($hotel->amenities)
                    ? implode(', ', $hotel->amenities)
                    : implode(', ', json_decode($hotel->amenities, true) ?? []);
                $hotelContext = "
Khách hàng đang xem: {$hotel->name}
- Địa chỉ: {$hotel->address}, {$hotel->city}
- Giá: \${$hotel->price_per_night}/đêm
- Hạng: {$hotel->star_rating} sao | Đánh giá: {$hotel->rating}/5
- Tiện nghi: {$amenities}
- Huỷ miễn phí: " . ($hotel->free_cancellation ? 'Có' : 'Không') . "
";
            }
        }

        // ── Detect intent: user có đang hỏi về địa điểm/loại KS không? ──
        $suggestedHotels = $this->detectAndSuggestHotels($userMessage, $hotelId);

        // ── Danh sách khách sạn có sẵn để AI biết ──
        $hotelListContext = $this->buildHotelListContext();

        $systemPrompt = "Bạn là trợ lý tư vấn đặt phòng của Tripto - nền tảng đặt khách sạn Việt Nam.
Trả lời ngắn gọn, thân thiện, bằng tiếng Việt. Tối đa 50000 từ mỗi câu trả lời.
Tư vấn về: giá phòng, tiện nghi, địa điểm, chính sách huỷ, check-in/out.
Khi gợi ý khách sạn, chỉ đề cập tên khách sạn — hệ thống sẽ tự hiển thị link.
KHÔNG bịa đặt thông tin. QUAN TRỌNG: Khi gợi ý khách sạn, CHỈ gợi ý đúng thành phố mà khách yêu cầu. Nếu không có dữ liệu, hãy nói thẳng thay vì gợi ý sai địa điểm. Nếu không biết, hãy nói thật.

{$hotelContext}

Các khách sạn hiện có trên hệ thống:
{$hotelListContext}";

        $messages = [['role' => 'system', 'content' => $systemPrompt]];

        foreach (array_slice($history, -6) as $msg) {
            if (isset($msg['role'], $msg['content'])) {
                $messages[] = ['role' => $msg['role'], 'content' => $msg['content']];
            }
        }
        $messages[] = ['role' => 'user', 'content' => $userMessage];

        try {
            $response = Http::timeout(120)->post('http://localhost:11434/api/chat', [
                'model'    => 'llama3.2',
                'messages' => $messages,
                'stream'   => false,
                'options'  => [
                    'num_predict' => 200,
                    'temperature' => 0.7,
                ],
            ]);
            /** @var \Illuminate\Http\Client\Response $response */
            if ($response->successful()) {
                $data  = $response->json();
                $reply = $data['message']['content'] ?? 'Xin lỗi, không có phản hồi.';

                return response()->json([
                    'reply'           => trim($reply),
                    'suggested_hotels' => $suggestedHotels,
                ]);
            }

            return response()->json([
                'reply' => 'Xin lỗi, có lỗi xảy ra. Vui lòng thử lại.',
                'error' => true,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'reply' => 'Kết nối Ollama thất bại. Đảm bảo Ollama đang chạy (`ollama serve`).',
                'error' => true,
            ]);
        }
    }

    // ── Detect intent và tìm khách sạn phù hợp ──
    private function detectAndSuggestHotels(string $message, ?int $excludeId = null): array
    {
        $msg = mb_strtolower($message, 'UTF-8');

        $msgNorm = $this->normalizeVi($msg);

        // Keywords trigger gợi ý
        $triggerKeywords = [
            'gợi ý',
            'recommend',
            'khách sạn nào',
            'ở đâu tốt',
            'tìm khách sạn',
            'muốn đặt',
            'đặt phòng',
            'tìm phòng',
            'hotel',
            'có khách sạn',
            'ngân sách',
            'rẻ',
            'sang',
            'tốt nhất',
            'nên ở đâu',
        ];

        $hasTrigger = false;
        foreach ($triggerKeywords as $kw) {
            if (str_contains($msg, $kw)) {
                $hasTrigger = true;
                break;
            }
        }

        if (!$hasTrigger) return [];

        // Detect thành phố được đề cập
        $cityMap = [
            'ha noi'      => 'Ha Noi',
            'hanoi'       => 'Ha Noi',
            'ho chi minh' => 'Ho Chi Minh',
            'sai gon'     => 'Ho Chi Minh',
            'saigon'      => 'Ho Chi Minh',
            'da nang'     => 'Da Nang',
            'danang'      => 'Da Nang',
            'hoi an'      => 'Hoi An',
            'hoian'       => 'Hoi An',
            'phu quoc'    => 'Phu Quoc',
            'phuquoc'     => 'Phu Quoc',
            'nha trang'   => 'Nha Trang',
            'nhatrang'    => 'Nha Trang',
            'sapa'        => 'Sapa',
            'sa pa'       => 'Sapa',
            'hue'         => 'Hue',
            'ha long'     => 'Ha Long',
            'halong'      => 'Ha Long',
            'da lat'      => 'Da Lat',
            'dalat'       => 'Da Lat',
            'mui ne'      => 'Mui Ne',
            'muine'       => 'Mui Ne',
            'can tho'     => 'Can Tho',
            'cantho'      => 'Can Tho',
            'vung tau'    => 'Vung Tau',
            'vungtau'     => 'Vung Tau',
            'ninh binh'   => 'Ninh Binh',
            'ninhbinh'    => 'Ninh Binh',
            'ha giang'    => 'Ha Giang',
            'hagiang'     => 'Ha Giang',
            'quy nhon'    => 'Quy Nhon',
            'quynhon'     => 'Quy Nhon',
            'phan thiet'  => 'Phan Thiet',
            'phanthiet'   => 'Phan Thiet',
        ];

        $targetCity = null;
        foreach ($cityMap as $keyword => $city) {
            if (str_contains($msg, $keyword) || str_contains($msgNorm, $keyword)) {
                $targetCity = $city;
                break;
            }
        }

        // Detect budget
        $maxPrice = null;
        if (str_contains($msgNorm, 're') || str_contains($msgNorm, 'tiet kiem') || str_contains($msg, 'budget')) {
            $maxPrice = 80;
        }
        if (preg_match('/\$\s*(\d+)/', $msg, $m))                       $maxPrice = (int)$m[1];
        if (preg_match('/(\d+)\s*(đô|usd|\$|dollar)/u', $msg, $m))     $maxPrice = (int)$m[1];
        if (preg_match('/duoi\s*(\d+)|dưới\s*(\d+)/u', $msgNorm, $m)) $maxPrice = (int)($m[1] ?: $m[2]);


        // Detect loại hình
        $typeMap = [
            'resort'      => 'Resort',
            'villa'       => 'Villa',
            'hostel'      => 'Hostel',
            'nha nghi'    => 'Guest House',
            'guest house' => 'Guest House',
            'can ho'      => 'Apartment',
            'capsule'     => 'Capsule Hotel',
            'khách sạn' => 'Hotel',
            'hotel'     => 'Hotel',
        ];
        $targetType = null;
        foreach ($typeMap as $kw => $type) {
            if (str_contains($msg, $kw)) {
                $targetType = $type;
                break;
            }
        }

        $starRating = null;
        if (preg_match('/(\d)\s*sao/', $msg, $m)) $starRating = (int)$m[1];
        // ── Query DB ──
        $query = Hotel::where('status', 'approved')
            ->orderByDesc('rating')
            ->limit(3);

        if ($targetCity) {
            $query->where(function ($q) use ($targetCity) {
                $q->where('city', 'like', "%{$targetCity}%")
                    ->orWhere('address', 'like', "%{$targetCity}%");
            });
        }

        if ($maxPrice) {
            $query->where('price_per_night', '<=', $maxPrice);
        }

        if ($targetType) {
            $query->where('type', $targetType);
        }

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        $hotels = $query->get();

        if ($hotels->isEmpty() && $targetCity && ($maxPrice || $targetType || $starRating)) {
            $hotels = Hotel::where('status', 'approved')
                ->where(function ($q) use ($targetCity) {
                    $q->where('city', 'like', "%{$targetCity}%")
                        ->orWhere('address', 'like', "%{$targetCity}%");
                })
                ->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))
                ->orderByDesc('rating')
                ->limit(3)
                ->get();
        }

        if ($hotels->isEmpty() && !$targetCity) {
            $hotels = Hotel::where('status', 'approved')
                ->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))
                ->orderByDesc('rating')
                ->limit(3)
                ->get();
        }

        return $hotels->map(fn($h) => [
            'id'             => $h->id,
            'name'           => $h->name,
            'city'           => $h->city,
            'price'          => $h->price_per_night,
            'rating'         => $h->rating,
            'star_rating'    => $h->star_rating,
            'image'          => $h->image_url,
            'type'           => $h->type,
            'url'            => route('hotels.show', $h->id),
            'free_cancel'    => $h->free_cancellation,
        ])->toArray();
    }
    private function normalizeVi(string $str): string
    {
        $str  = mb_strtolower(trim($str), 'UTF-8');
        $from = [
            'à',
            'á',
            'ạ',
            'ả',
            'ã',
            'â',
            'ầ',
            'ấ',
            'ậ',
            'ẩ',
            'ẫ',
            'ă',
            'ằ',
            'ắ',
            'ặ',
            'ẳ',
            'ẵ',
            'è',
            'é',
            'ẹ',
            'ẻ',
            'ẽ',
            'ê',
            'ề',
            'ế',
            'ệ',
            'ể',
            'ễ',
            'ì',
            'í',
            'ị',
            'ỉ',
            'ĩ',
            'ò',
            'ó',
            'ọ',
            'ỏ',
            'õ',
            'ô',
            'ồ',
            'ố',
            'ộ',
            'ổ',
            'ỗ',
            'ơ',
            'ờ',
            'ớ',
            'ợ',
            'ở',
            'ỡ',
            'ù',
            'ú',
            'ụ',
            'ủ',
            'ũ',
            'ư',
            'ừ',
            'ứ',
            'ự',
            'ử',
            'ữ',
            'ỳ',
            'ý',
            'ỵ',
            'ỷ',
            'ỹ',
            'đ'
        ];
        $to   = [
            'a',
            'a',
            'a',
            'a',
            'a',
            'a',
            'a',
            'a',
            'a',
            'a',
            'a',
            'a',
            'a',
            'a',
            'a',
            'a',
            'a',
            'e',
            'e',
            'e',
            'e',
            'e',
            'e',
            'e',
            'e',
            'e',
            'e',
            'e',
            'i',
            'i',
            'i',
            'i',
            'i',
            'o',
            'o',
            'o',
            'o',
            'o',
            'o',
            'o',
            'o',
            'o',
            'o',
            'o',
            'o',
            'o',
            'o',
            'o',
            'o',
            'o',
            'u',
            'u',
            'u',
            'u',
            'u',
            'u',
            'u',
            'u',
            'u',
            'u',
            'u',
            'y',
            'y',
            'y',
            'y',
            'y',
            'd'
        ];
        return str_replace($from, $to, $str);
    }

    // ── Build danh sách KS ngắn gọn cho system prompt ──
    private function buildHotelListContext(): string
    {
        $hotels = Hotel::where('status', 'approved')
            ->orderByDesc('rating')
            ->limit(20)
            ->get(['name', 'city', 'price_per_night', 'star_rating', 'rating', 'type']);

        return $hotels->map(
            fn($h) =>
            "- {$h->name} ({$h->city}) | {$h->star_rating}★ | \${$h->price_per_night}/đêm | {$h->type} | Điểm: {$h->rating}"
        )->implode("\n");
    }

    // ── Quick suggestions ──
    public function suggestions(Request $request)
    {
        $hotelId = $request->hotel_id;

        if ($hotelId) {
            return response()->json([
                'suggestions' => [
                    'Khách sạn này có hồ bơi không?',
                    'Chính sách huỷ phòng như thế nào?',
                    'Check-in lúc mấy giờ?',
                    'Có bãi đỗ xe miễn phí không?',
                    'Gợi ý khách sạn tương tự',
                ],
            ]);
        }

        return response()->json([
            'suggestions' => [
                'Gợi ý khách sạn ở Hà Nội',
                'Khách sạn đẹp ở Đà Nẵng?',
                'Tìm resort ở Phú Quốc',
                'Khách sạn rẻ dưới $80',
                'Khách sạn tốt nhất Nha Trang',
            ],
        ]);
    }
}
