<?php
/**
 * Server Configuration
 * Environment-specific settings for the portfolio backend
 */

// Load environment variables from .env file
$envFile = __DIR__ . '/../.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        // Skip comments
        if (strpos(trim($line), '#') === 0) {
            continue;
        }
        // Parse key=value
        if (strpos($line, '=') !== false) {
            list($key, $value) = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);
            if (!empty($key) && !getenv($key)) {
                putenv("$key=$value");
                $_ENV[$key] = $value;
                $_SERVER[$key] = $value;
            }
        }
    }
}

// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', 0); // Set to 1 for development
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/../logs/error.log');

// CORS Configuration
define('ALLOWED_ORIGINS', [
    'http://localhost:5173',
    'http://localhost:3000',
    'https://warrdev.tech',
    'https://www.warrdev.tech'
]);

// Database Configuration (if needed)
define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_NAME', getenv('DB_NAME') ?: 'portfolio_db');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASS', getenv('DB_PASS') ?: '');

// Email Configuration
define('CONTACT_EMAIL', 'warrdev08@gmail.com');
// Use SMTP_USER as FROM_EMAIL to avoid sender address rejection
define('FROM_EMAIL', getenv('SMTP_USER') ?: 'noreply@warrdev.tech');
define('SMTP_HOST', getenv('SMTP_HOST') ?: 'smtp.gmail.com');
define('SMTP_PORT', getenv('SMTP_PORT') ?: 587);
define('SMTP_USER', getenv('SMTP_USER') ?: '');
define('SMTP_PASS', getenv('SMTP_PASS') ?: '');

// API Settings
define('API_VERSION', 'v1');
define('RATE_LIMIT', 10); // requests per minute
define('MAX_MESSAGE_LENGTH', 1000);

// Timezone
date_default_timezone_set('Asia/Manila');
