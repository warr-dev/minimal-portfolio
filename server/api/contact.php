<?php
/**
 * Contact Form API Endpoint
 * Handles contact form submissions
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../utils/cors.php';
require_once __DIR__ . '/../utils/validator.php';
require_once __DIR__ . '/../utils/ratelimit.php';

// Handle CORS
handleCORS();

// Only accept POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendError('Method not allowed', 405);
}

// Rate limiting
$clientId = RateLimiter::getClientIdentifier();
$rateLimit = RateLimiter::check($clientId);

if (!$rateLimit['allowed']) {
    sendError('Too many requests. Please try again later.', 429);
}

// Get and decode JSON input
$input = json_decode(file_get_contents('php://input'), true);

if (!$input) {
    sendError('Invalid JSON input');
}

// Validate input
$validation = Validator::validateContactForm($input);

if (!$validation['valid']) {
    sendJSON([
        'success' => false,
        'errors' => $validation['errors']
    ], 422);
}

$data = $validation['data'];

// Send email
$success = sendContactEmail($data);

if ($success) {
    // Log the submission (optional)
    logContactSubmission($data);

    sendSuccess([], 'Message sent successfully! I will get back to you soon.');
} else {
    sendError('Failed to send message. Please try again later.', 500);
}

/**
 * Send contact email
 */
function sendContactEmail($data) {
    $to = CONTACT_EMAIL;
    $subject = "Portfolio Contact: Message from {$data['name']}";

    $message = "
    New Contact Form Submission

    Name: {$data['name']}
    Email: {$data['email']}

    Message:
    {$data['message']}

    ---
    Sent from: {$_SERVER['REMOTE_ADDR']}
    Time: " . date('Y-m-d H:i:s') . "
    ";

    $headers = [
        'From: ' . FROM_EMAIL,
        'Reply-To: ' . $data['email'],
        'X-Mailer: PHP/' . phpversion(),
        'Content-Type: text/plain; charset=UTF-8'
    ];

    return mail($to, $subject, $message, implode("\r\n", $headers));
}

/**
 * Log contact submission to file
 */
function logContactSubmission($data) {
    $logDir = __DIR__ . '/../logs/contacts/';

    if (!is_dir($logDir)) {
        mkdir($logDir, 0755, true);
    }

    $logFile = $logDir . date('Y-m') . '.log';
    $logEntry = date('[Y-m-d H:i:s]') . " {$data['name']} <{$data['email']}> - " .
                substr($data['message'], 0, 50) . "...\n";

    file_put_contents($logFile, $logEntry, FILE_APPEND);
}
