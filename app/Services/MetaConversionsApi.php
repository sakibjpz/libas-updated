<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Meta Conversions API (server-side tracking).
 *
 * Sends events directly to Facebook's Graph API so AddToCart/InitiateCheckout/
 * Purchase are captured even when the browser pixel is blocked. Pass the same
 * $eventId to both the browser fbq() call and this method for deduplication.
 */
class MetaConversionsApi
{
    /**
     * Generate a unique event ID for browser/server deduplication.
     */
    public static function eventId(string $prefix = 'ev'): string
    {
        return $prefix . '_' . bin2hex(random_bytes(8));
    }

    /**
     * Send a server-side event to the Meta Conversions API.
     *
     * @param  string  $eventName      e.g. ViewContent, AddToCart, InitiateCheckout, Purchase
     * @param  array   $customData     value, currency, content_ids, content_name, num_items, ...
     * @param  array   $userDataPlain  email, phone, first_name, last_name, city, zip, country (hashed before send)
     * @param  string|null $eventId    shared with the browser pixel event for dedup
     * @param  string|null $sourceUrl  page URL where the event happened
     */
    public static function send(
        string $eventName,
        array $customData = [],
        array $userDataPlain = [],
        ?string $eventId = null,
        ?string $sourceUrl = null
    ): void {
        $pixelId = config('services.facebook.pixel_id');
        $token   = config('services.facebook.access_token');

        if (!$pixelId || !$token) {
            return;
        }

        $request = request();

        // Attach logged-in user's identity to every event — improves Meta match quality
        if (($user = $request->user()) && empty($userDataPlain['email'])) {
            $userDataPlain['email'] = $user->email;
            if (!empty($user->phone)) {
                $userDataPlain['phone'] = $user->phone;
            }
            if (empty($userDataPlain['first_name'])) {
                $userDataPlain['first_name'] = $user->name;
            }
        }

        $userData = [
            'client_ip_address' => $request->ip(),
            'client_user_agent' => substr((string) $request->userAgent(), 0, 512),
        ];

        // Facebook click/browser cookies improve match quality
        if ($fbc = $request->cookie('_fbc')) {
            $userData['fbc'] = $fbc;
        }
        if ($fbp = $request->cookie('_fbp')) {
            $userData['fbp'] = $fbp;
        }

        // Hash PII fields per Meta requirements
        $map = [
            'em'      => 'email',
            'ph'      => 'phone',
            'fn'      => 'first_name',
            'ln'      => 'last_name',
            'ct'      => 'city',
            'zp'      => 'zip',
            'country' => 'country',
        ];
        foreach ($map as $field => $source) {
            if (!empty($userDataPlain[$source])) {
                $value = strtolower(trim((string) $userDataPlain[$source]));
                // Meta expects phone as digits only, international format (8801...)
                if ($field === 'ph') {
                    $value = preg_replace('/\D/', '', $value);
                    if (str_starts_with($value, '0')) {
                        $value = '88' . $value;          // 01xxxxxxxxx -> 8801xxxxxxxxx
                    }
                }
                $userData[$field] = hash('sha256', $value);
            }
        }
        if (!isset($userData['country'])) {
            $userData['country'] = hash('sha256', 'bd');
        }

        try {
            $response = Http::timeout(8)->post(
                "https://graph.facebook.com/v21.0/{$pixelId}/events?access_token={$token}",
                [
                    'data' => [
                        [
                            'event_name'       => $eventName,
                            'event_time'       => time(),
                            'event_id'         => $eventId ?: self::eventId(),
                            'action_source'    => 'website',
                            'event_source_url' => $sourceUrl ?: url()->current(),
                            'user_data'        => $userData,
                            'custom_data'      => $customData,
                        ],
                    ],
                ]
            );

            if (!$response->successful() || (int) ($response->json('events_received') ?? 0) < 1) {
                Log::warning('Meta CAPI rejected event', [
                    'event'    => $eventName,
                    'status'   => $response->status(),
                    'response' => substr((string) $response->body(), 0, 500),
                ]);
            }
        } catch (\Throwable $e) {
            // Tracking must never break the storefront
            Log::warning('Meta CAPI send failed', [
                'event' => $eventName,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
