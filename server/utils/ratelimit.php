<?php
/**
 * Rate Limiting Utility
 * Simple file-based rate limiting
 */

class RateLimiter {
    private static $logDir = __DIR__ . '/../logs/ratelimit/';

    public static function check($identifier, $limit = RATE_LIMIT, $window = 60) {
        // Create log directory if it doesn't exist
        if (!is_dir(self::$logDir)) {
            mkdir(self::$logDir, 0755, true);
        }

        $filename = self::$logDir . md5($identifier) . '.json';
        $now = time();

        // Load existing attempts
        $attempts = [];
        if (file_exists($filename)) {
            $data = json_decode(file_get_contents($filename), true);
            if ($data) {
                $attempts = $data;
            }
        }

        // Remove old attempts outside the time window
        $attempts = array_filter($attempts, function($timestamp) use ($now, $window) {
            return ($now - $timestamp) < $window;
        });

        // Check if limit exceeded
        if (count($attempts) >= $limit) {
            return [
                'allowed' => false,
                'remaining' => 0,
                'reset' => min($attempts) + $window
            ];
        }

        // Add current attempt
        $attempts[] = $now;
        file_put_contents($filename, json_encode($attempts));

        return [
            'allowed' => true,
            'remaining' => $limit - count($attempts),
            'reset' => $now + $window
        ];
    }

    public static function getClientIdentifier() {
        // Use IP address as identifier
        return $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    }
}
