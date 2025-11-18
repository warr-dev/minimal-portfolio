<?php
/**
 * Email Sender Utility
 * Handles email sending via SMTP using PHPMailer
 */

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Mailer {
    /**
     * Send email using PHPMailer with SMTP or fallback to mail()
     */
    public static function send($to, $subject, $body, $replyTo = null, $replyToName = null) {
        // Check if PHPMailer is available
        if (class_exists('PHPMailer\PHPMailer\PHPMailer')) {
            return self::sendViaSMTP($to, $subject, $body, $replyTo, $replyToName);
        } else {
            error_log("[Mailer] PHPMailer not found, falling back to mail()");
            return self::sendViaMail($to, $subject, $body, $replyTo);
        }
    }

    /**
     * Send email via SMTP using PHPMailer
     */
    private static function sendViaSMTP($to, $subject, $body, $replyTo = null, $replyToName = null) {
        try {
            $mail = new PHPMailer(true);

            // Check if SMTP is configured
            if (!self::isSMTPConfigured()) {
                error_log("[Mailer] SMTP not configured, falling back to mail()");
                return self::sendViaMail($to, $subject, $body, $replyTo);
            }

            // Server settings
            $mail->isSMTP();
            $mail->Host       = SMTP_HOST;
            $mail->SMTPAuth   = true;
            $mail->Username   = SMTP_USER;
            $mail->Password   = SMTP_PASS;

            // Set encryption based on port
            if (SMTP_PORT == 465) {
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            } elseif (SMTP_PORT == 587) {
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            }

            $mail->Port       = SMTP_PORT;

            // Timeout settings
            $mail->Timeout    = 30;
            $mail->SMTPKeepAlive = false;

            // For debugging (disable in production)
            // $mail->SMTPDebug = 2;
            // $mail->Debugoutput = function($str, $level) {
            //     error_log("[PHPMailer Debug] $str");
            // };

            // Recipients
            $mail->setFrom(FROM_EMAIL, 'Portfolio Contact Form');
            $mail->addAddress($to);

            if ($replyTo) {
                $mail->addReplyTo($replyTo, $replyToName ?: $replyTo);
            }

            // Content
            $mail->isHTML(false);
            $mail->CharSet = 'UTF-8';
            $mail->Subject = $subject;
            $mail->Body    = $body;

            $result = $mail->send();

            if ($result) {
                error_log("[Mailer] ✓ Email sent successfully via SMTP to: $to");
            }

            return $result;

        } catch (Exception $e) {
            error_log("[Mailer] ✗ SMTP Error: {$mail->ErrorInfo}");
            error_log("[Mailer] ✗ Exception: {$e->getMessage()}");

            // Fallback to mail() if SMTP fails
            error_log("[Mailer] Attempting fallback to mail()");
            return self::sendViaMail($to, $subject, $body, $replyTo);
        }
    }

    /**
     * Send email via PHP mail() function (fallback)
     */
    private static function sendViaMail($to, $subject, $body, $replyTo = null) {
        $headers = [
            'From: ' . FROM_EMAIL,
            'X-Mailer: PHP/' . phpversion(),
            'Content-Type: text/plain; charset=UTF-8'
        ];

        if ($replyTo) {
            $headers[] = 'Reply-To: ' . $replyTo;
        }

        $result = @mail($to, $subject, $body, implode("\r\n", $headers));

        if ($result) {
            error_log("[Mailer] ✓ Email sent via mail() to: $to");
        } else {
            error_log("[Mailer] ✗ Failed to send email via mail() to: $to");
        }

        return $result;
    }

    /**
     * Check if SMTP is configured
     */
    public static function isSMTPConfigured() {
        return !empty(SMTP_USER) &&
               !empty(SMTP_PASS) &&
               !empty(SMTP_HOST) &&
               SMTP_USER !== 'your_email@gmail.com' &&
               SMTP_USER !== 'your_email@yourdomain.com';
    }
}
