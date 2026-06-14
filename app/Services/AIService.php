<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class AIService
{
    public function chat($message)
    {
        $response = Http::withToken(env('OPENAI_API_KEY'))
            ->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-4o-mini',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' =>
                        'Bạn là trợ lý AI của hệ thống đặt phòng khách sạn. Hãy tư vấn khách sạn, địa điểm du lịch và hỗ trợ người dùng đặt phòng.'
                    ],
                    [
                        'role' => 'user',
                        'content' => $message
                    ]
                ]
            ]);
        /** @var \Illuminate\Http\Client\Response $response */
        return $response->json();
    }
}