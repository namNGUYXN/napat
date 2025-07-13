<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;


class KeywordAIService
{
    public static function extractKeywordsOptimized($htmlContent)
    {
        // 1. Làm sạch HTML
        $plainText = strip_tags($htmlContent);
        $plainText = mb_substr($plainText, 0, 10000); // Giới hạn an toàn

        // 2. Prompt
        $prompt = "Đọc kỹ nội dung sau, sau đó:\n"
            . "- Tóm tắt ngắn gọn nội dung (dưới 500 từ).\n"
            . "- Trích xuất 5 đến 10 từ khóa chính, cách nhau bằng dấu phẩy.\n\n"
            . "Nội dung:\n" . $plainText;

        // 3. Gọi API qua Pawan.krd
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('PAWAN_API_KEY'), // đặt key trong .env
            'Content-Type' => 'application/json',
        ])->post('https://api.pawan.krd/v1/chat/completions', [
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                ['role' => 'user', 'content' => $prompt],
            ],
            'temperature' => 0.3,
        ]);

        dd($plainText);

        // 4. Phân tích phản hồi
        $result = $response->json()['choices'][0]['message']['content'] ?? '';

        // 5. Trích từ khóa
        if (preg_match('/Từ khóa[:：](.*)/i', $result, $matches)) {
            $keywordsArray = array_map('trim', explode(',', $matches[1]));
            $keywords = implode(', ', array_unique(array_filter($keywordsArray)));
            return $keywords;
        }

        return '';
    }
}
