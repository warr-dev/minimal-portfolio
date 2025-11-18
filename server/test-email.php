<?php
/**
 * Email Testing Script
 * Test email functionality before deployment
 */

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/utils/mailer.php';

// Load Composer autoloader
$autoloadPath = __DIR__ . '/vendor/autoload.php';
if (file_exists($autoloadPath)) {
    require_once $autoloadPath;
    echo "✓ PHPMailer loaded\n";
} else {
    echo "✗ PHPMailer not found. Run: composer install\n";
    exit(1);
}

echo "\n";
echo "=================================\n";
echo " Email Configuration Test\n";
echo "=================================\n\n";

// Check SMTP configuration
echo "SMTP Configuration:\n";
echo "  Host: " . SMTP_HOST . "\n";
echo "  Port: " . SMTP_PORT . "\n";
echo "  User: " . SMTP_USER . "\n";
echo "  Pass: " . (empty(SMTP_PASS) ? '(not set)' : '***') . "\n";
echo "  Configured: " . (Mailer::isSMTPConfigured() ? 'YES' : 'NO') . "\n";
echo "\n";

echo "Email Settings:\n";
echo "  To: " . CONTACT_EMAIL . "\n";
echo "  From: " . FROM_EMAIL . "\n";
echo "\n";

// Ask for confirmation
echo "Send test email to " . CONTACT_EMAIL . "? (y/n): ";
$handle = fopen("php://stdin", "r");
$line = fgets($handle);
fclose($handle);

if (trim($line) !== 'y') {
    echo "Test cancelled.\n";
    exit(0);
}

echo "\nSending test email...\n";

// Send test email
$testData = [
    'name' => 'Test User',
    'email' => 'test@example.com',
    'message' => 'This is a test email sent from the test-email.php script. If you receive this, email sending is working correctly!'
];

$subject = "Portfolio Test: Email from {$testData['name']}";
$message = "New Contact Form Submission (TEST)

Name: {$testData['name']}
Email: {$testData['email']}

Message:
{$testData['message']}

---
This is a test email.
Time: " . date('Y-m-d H:i:s');

$result = Mailer::send(
    CONTACT_EMAIL,
    $subject,
    $message,
    $testData['email'],
    $testData['name']
);

echo "\n";
if ($result) {
    echo "✓ Email sent successfully!\n";
    echo "  Check your inbox: " . CONTACT_EMAIL . "\n";
    echo "  (If not in inbox, check spam folder)\n";
} else {
    echo "✗ Failed to send email.\n";
    echo "  Check the error log: " . __DIR__ . "/logs/error.log\n";
    echo "\n  Possible issues:\n";
    echo "  - SMTP credentials incorrect\n";
    echo "  - SMTP server not reachable\n";
    echo "  - mail() function not configured\n";
}

echo "\n";
