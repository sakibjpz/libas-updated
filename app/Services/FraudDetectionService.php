<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class FraudDetectionService
{
    protected $suspiciousScore = 0;
    protected $flags = [];
    protected $thresholds = [
        'max_orders_per_hour' => 5,
        'max_orders_per_day' => 10,
        'max_order_amount' => 500000,
        'suspicious_score_threshold' => 50,
    ];

    /**
     * Analyze order for fraud indicators
     */
    public function analyzeOrder(array $orderData, Request $request): array
    {
        $this->suspiciousScore = 0;
        $this->flags = [];

        // Reset counters
        $this->checkVelocity($request);
        $this->checkIPHistory($request);
        $this->checkEmailPhone($orderData);
        $this->checkOrderAmount($orderData);
        $this->checkAddressPatterns($orderData);
        $this->checkBlacklist($orderData, $request);

        $isSuspicious = $this->suspiciousScore >= $this->thresholds['suspicious_score_threshold'];

        return [
            'is_suspicious' => $isSuspicious,
            'suspicious_score' => $this->suspiciousScore,
            'flags' => $this->flags,
            'should_block' => $isSuspicious && $this->suspiciousScore >= 75,
        ];
    }

    /**
     * Check velocity - rapid multiple orders
     */
    private function checkVelocity(Request $request): void
    {
        $ip = $request->ip();
        $email = $orderData['customer_email'] ?? null;
        $phone = $orderData['customer_phone'] ?? null;

        // Check IP velocity
        $ipOrdersLastHour = Cache::get("orders_ip_{$ip}_hour", 0);
        $ipOrdersLastDay = Cache::get("orders_ip_{$ip}_day", 0);

        if ($ipOrdersLastHour >= $this->thresholds['max_orders_per_hour']) {
            $this->suspiciousScore += 30;
            $this->flags[] = 'High velocity orders from IP';
        }

        if ($ipOrdersLastDay >= $this->thresholds['max_orders_per_day']) {
            $this->suspiciousScore += 20;
            $this->flags[] = 'Daily order limit exceeded for IP';
        }

        // Check email velocity if provided
        if ($email) {
            $emailOrdersLastHour = Cache::get("orders_email_{$email}_hour", 0);
            if ($emailOrdersLastHour >= 3) {
                $this->suspiciousScore += 25;
                $this->flags[] = 'High velocity orders from email';
            }
        }

        // Check phone velocity if provided
        if ($phone) {
            $phoneOrdersLastHour = Cache::get("orders_phone_{$phone}_hour", 0);
            if ($phoneOrdersLastHour >= 3) {
                $this->suspiciousScore += 25;
                $this->flags[] = 'High velocity orders from phone';
            }
        }
    }

    /**
     * Check IP history for suspicious activity
     */
    private function checkIPHistory(Request $request): void
    {
        $ip = $request->ip();

        // Check if IP has recent failed orders
        $failedOrders = Cache::get("failed_orders_ip_{$ip}", 0);
        if ($failedOrders >= 3) {
            $this->suspiciousScore += 40;
            $this->flags[] = 'Multiple failed orders from IP';
        }

        // Check if IP is in suspicious list
        $suspiciousIPs = Cache::get('suspicious_ips', []);
        if (in_array($ip, $suspiciousIPs)) {
            $this->suspiciousScore += 50;
            $this->flags[] = 'IP in suspicious list';
        }
    }

    /**
     * Validate email and phone
     */
    private function checkEmailPhone(array $orderData): void
    {
        $email = $orderData['customer_email'] ?? null;
        $phone = $orderData['customer_phone'] ?? null;

        // Check email format and domain
        if ($email) {
            $domain = substr(strrchr($email, '@'), 1);
            $suspiciousDomains = ['tempmail.com', 'guerrillamail.com', '10minutemail.com'];
            if (in_array($domain, $suspiciousDomains)) {
                $this->suspiciousScore += 35;
                $this->flags[] = 'Suspicious email domain';
            }

            // Check for disposable email patterns
            $disposablePatterns = ['temp', 'throwaway', 'disposable', 'fake', 'anon'];
            foreach ($disposablePatterns as $pattern) {
                if (stripos($email, $pattern) !== false) {
                    $this->suspiciousScore += 20;
                    $this->flags[] = 'Disposable email pattern detected';
                    break;
                }
            }
        }

        // Check phone number format for Bangladesh
        if ($phone) {
            $phone = preg_replace('/[^0-9]/', '', $phone);
            if (strlen($phone) !== 11 || !preg_match('/^01[3-9]\d{8}$/', $phone)) {
                $this->suspiciousScore += 15;
                $this->flags[] = 'Invalid phone number format';
            }
        }
    }

    /**
     * Check order amount thresholds
     */
    private function checkOrderAmount(array $orderData): void
    {
        $total = $orderData['total'] ?? 0;

        if ($total > $this->thresholds['max_order_amount']) {
            $this->suspiciousScore += 25;
            $this->flags[] = 'Order amount exceeds threshold';
        }

        // Check for unusually high amount for new customer
        if ($total > 50000 && !auth()->check()) {
            $this->suspiciousScore += 15;
            $this->flags[] = 'High amount from unauthenticated user';
        }
    }

    /**
     * Check address patterns
     */
    private function checkAddressPatterns(array $orderData): void
    {
        $address = $orderData['shipping_address'] ?? '';

        // Check for suspicious address patterns
        $suspiciousPatterns = ['test', 'fake', 'dummy', 'sample', 'demo', '12345'];
        foreach ($suspiciousPatterns as $pattern) {
            if (stripos($address, $pattern) !== false) {
                $this->suspiciousScore += 20;
                $this->flags[] = 'Suspicious address pattern';
                break;
            }
        }

        // Check for very short addresses
        if (strlen(trim($address)) < 10) {
            $this->suspiciousScore += 15;
            $this->flags[] = 'Address too short';
        }
    }

    /**
     * Check blacklist
     */
    private function checkBlacklist(array $orderData, Request $request): void
    {
        $ip = $request->ip();
        $email = $orderData['customer_email'] ?? null;
        $phone = $orderData['customer_phone'] ?? null;

        $blacklistedIPs = Cache::get('blacklisted_ips', []);
        $blacklistedEmails = Cache::get('blacklisted_emails', []);
        $blacklistedPhones = Cache::get('blacklisted_phones', []);

        if (in_array($ip, $blacklistedIPs)) {
            $this->suspiciousScore += 100;
            $this->flags[] = 'IP is blacklisted';
        }

        if ($email && in_array($email, $blacklistedEmails)) {
            $this->suspiciousScore += 100;
            $this->flags[] = 'Email is blacklisted';
        }

        if ($phone && in_array($phone, $blacklistedPhones)) {
            $this->suspiciousScore += 100;
            $this->flags[] = 'Phone is blacklisted';
        }
    }

    /**
     * Record successful order
     */
    public function recordSuccessfulOrder(array $orderData, Request $request): void
    {
        $ip = $request->ip();
        $email = $orderData['customer_email'] ?? null;
        $phone = $orderData['customer_phone'] ?? null;

        // Increment IP counters
        Cache::increment("orders_ip_{$ip}_hour");
        Cache::increment("orders_ip_{$ip}_day");

        // Increment email counter
        if ($email) {
            Cache::increment("orders_email_{$email}_hour");
        }

        // Increment phone counter
        if ($phone) {
            Cache::increment("orders_phone_{$phone}_hour");
        }

        // Reset failed order counter for this IP
        Cache::forget("failed_orders_ip_{$ip}");
    }

    /**
     * Record failed order
     */
    public function recordFailedOrder(Request $request): void
    {
        $ip = $request->ip();
        Cache::increment("failed_orders_ip_{$ip}");
    }

    /**
     * Add to blacklist
     */
    public function addToBlacklist(string $type, string $value): void
    {
        $cacheKey = "blacklisted_{$type}s";
        $blacklist = Cache::get($cacheKey, []);
        $blacklist[] = $value;
        Cache::put($cacheKey, $blacklist, now()->addDays(30));
    }

    /**
     * Add to suspicious list
     */
    public function addToSuspiciousList(string $ip): void
    {
        $suspiciousIPs = Cache::get('suspicious_ips', []);
        if (!in_array($ip, $suspiciousIPs)) {
            $suspiciousIPs[] = $ip;
            Cache::put('suspicious_ips', $suspiciousIPs, now()->addDays(7));
        }
    }

    /**
     * Get fraud report
     */
    public function getFraudReport(): array
    {
        return [
            'suspicious_ips' => Cache::get('suspicious_ips', []),
            'blacklisted_ips' => Cache::get('blacklisted_ips', []),
            'blacklisted_emails' => Cache::get('blacklisted_emails', []),
            'blacklisted_phones' => Cache::get('blacklisted_phones', []),
        ];
    }
}
