<?php
/**
 * Verify Email Configuration
 */

require_once __DIR__ . '/config/config.php';

echo "=================================\n";
echo " Email Configuration Check\n";
echo "=================================\n\n";

echo "Recipient (CONTACT_EMAIL): " . CONTACT_EMAIL . "\n";
echo "Sender (FROM_EMAIL): " . FROM_EMAIL . "\n";
echo "SMTP Host: " . SMTP_HOST . "\n";
echo "SMTP Port: " . SMTP_PORT . "\n";
echo "SMTP User: " . SMTP_USER . "\n";
echo "SMTP Pass: " . (empty(SMTP_PASS) ? '(not set)' : '***') . "\n";

echo "\n";
echo "Checking if emails match:\n";
echo "  - Recipient is 'warrdev08@gmail.com': " . (CONTACT_EMAIL === 'warrdev08@gmail.com' ? 'YES' : 'NO') . "\n";
echo "  - FROM_EMAIL equals SMTP_USER: " . (FROM_EMAIL === SMTP_USER ? 'YES' : 'NO') . "\n";

echo "\n";
echo "Tips:\n";
echo "1. Check your spam/junk folder in Gmail\n";
echo "2. Search for 'admin@warrdev.tech' in Gmail\n";
echo "3. Search for 'Portfolio Contact' in Gmail\n";
echo "4. Wait a few minutes - email delivery can be delayed\n";
echo "5. Check Gmail filters/rules that might auto-archive\n";
