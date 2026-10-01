<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * MRAM SMS Gateway — sends order SMS notifications.
 * Never throws; SMS failure must never break the order flow.
 */
class SmsService
{
    /**
     * Send an SMS. Returns true on accepted request.
     *
     * - Auto-detects unicode (Bangla) messages → type=unicode
     * - Multiple recipients: pass array or "+" separated numbers
     * - Uses 'transactional' label per MRAM API spec
     */
    public static function send($phone, string $message): bool
    {
        $key    = config('services.sms.api_key');
        $sender = config('services.sms.sender_id');
        $url    = config('services.sms.api_url');

        if (!$key || !$sender || !$phone) {
            Log::warning('SMS skipped: missing config or recipient', [
                'has_key' => (bool) $key,
                'has_sender' => (bool) $sender,
                'phone' => $phone,
            ]);
            return false;
        }

        // Normalize one or many recipients (MRAM joins contacts with "+")
        $phones  = is_array($phone) ? $phone : explode('+', (string) $phone);
        $numbers = array_filter(array_map([self::class, 'normalize'], $phones));

        if (empty($numbers)) {
            Log::warning('SMS skipped: invalid phone', ['phone' => $phone]);
            return false;
        }

        // Bangla / non-ASCII needs type=unicode
        $type = preg_match('/[^\x00-\x7F]/', $message) ? 'unicode' : 'text';

        try {
            $response = Http::timeout(8)->get($url, [
                'api_key'  => $key,
                'type'     => $type,
                'contacts' => implode('+', $numbers),
                'senderid' => $sender,
                'msg'      => $message,
                'label'    => 'transactional',
            ]);

            // MRAM can return HTTP 200 with an error body — inspect the payload too
            $body = (string) $response->body();
            $json = $response->json();
            $apiFailed = is_array($json)
                && (isset($json['error_msg']) || (isset($json['status']) && strtolower((string) $json['status']) === 'error'));

            if (!$response->successful() || $apiFailed) {
                Log::warning('SMS gateway rejected request', [
                    'status' => $response->status(),
                    'body'   => substr($body, 0, 300),
                    'phone'  => $numbers,
                ]);
                return false;
            }

            return true;
        } catch (\Throwable $e) {
            Log::warning('SMS send failed', ['error' => $e->getMessage(), 'phone' => $numbers ?? $phone]);
            return false;
        }
    }

    /**
     * Normalize BD phone to 8801XXXXXXXXX.
     */
    private static function normalize(string $phone): ?string
    {
        $digits = preg_replace('/\D/', '', $phone);

        if (str_starts_with($digits, '880')) {
            $digits = substr($digits, 3);       // 8801xxxxxxxxx -> 1xxxxxxxxx
        }
        if (str_starts_with($digits, '0')) {
            $digits = substr($digits, 1);       // 01xxxxxxxxx -> 1xxxxxxxxx
        }

        // Must now be 1XXXXXXXXX (10 digits starting with 1)
        if (preg_match('/^1\d{9}$/', $digits)) {
            return '880' . $digits;
        }

        return null;
    }
}
