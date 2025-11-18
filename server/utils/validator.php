<?php
/**
 * Input Validation Utility
 */

class Validator {
    public static function validateEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    public static function validateRequired($value) {
        return !empty(trim($value));
    }

    public static function sanitizeString($string) {
        return htmlspecialchars(strip_tags(trim($string)), ENT_QUOTES, 'UTF-8');
    }

    public static function validateLength($string, $min = 1, $max = null) {
        $length = strlen(trim($string));
        if ($length < $min) {
            return false;
        }
        if ($max !== null && $length > $max) {
            return false;
        }
        return true;
    }

    public static function validateContactForm($data) {
        $errors = [];

        // Name validation
        if (!isset($data['name']) || !self::validateRequired($data['name'])) {
            $errors['name'] = 'Name is required';
        } elseif (!self::validateLength($data['name'], 2, 50)) {
            $errors['name'] = 'Name must be between 2 and 50 characters';
        }

        // Email validation
        if (!isset($data['email']) || !self::validateRequired($data['email'])) {
            $errors['email'] = 'Email is required';
        } elseif (!self::validateEmail($data['email'])) {
            $errors['email'] = 'Invalid email format';
        }

        // Message validation
        if (!isset($data['message']) || !self::validateRequired($data['message'])) {
            $errors['message'] = 'Message is required';
        } elseif (!self::validateLength($data['message'], 10, MAX_MESSAGE_LENGTH)) {
            $errors['message'] = 'Message must be between 10 and ' . MAX_MESSAGE_LENGTH . ' characters';
        }

        return [
            'valid' => empty($errors),
            'errors' => $errors,
            'data' => [
                'name' => self::sanitizeString($data['name'] ?? ''),
                'email' => self::sanitizeString($data['email'] ?? ''),
                'message' => self::sanitizeString($data['message'] ?? '')
            ]
        ];
    }
}
