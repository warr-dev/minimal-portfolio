<?php
/**
 * CORS Utility
 * Handles Cross-Origin Resource Sharing
 */

function handleCORS() {
    $origin = $_SERVER['HTTP_ORIGIN'] ?? '';

    // If origin is in allowed list, set it
    if (in_array($origin, ALLOWED_ORIGINS)) {
        header("Access-Control-Allow-Origin: $origin");
        header("Access-Control-Allow-Credentials: true");
    }
    // For development: allow localhost on any port
    elseif (preg_match('/^http:\/\/localhost(:\d+)?$/', $origin)) {
        header("Access-Control-Allow-Origin: $origin");
        header("Access-Control-Allow-Credentials: true");
    }

    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");
    header("Access-Control-Max-Age: 3600");

    // Handle preflight OPTIONS request
    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        http_response_code(204);
        exit();
    }
}

function sendJSON($data, $statusCode = 200) {
    http_response_code($statusCode);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit();
}

function sendError($message, $statusCode = 400) {
    sendJSON([
        'success' => false,
        'error' => $message
    ], $statusCode);
}

function sendSuccess($data, $message = 'Success') {
    sendJSON([
        'success' => true,
        'message' => $message,
        'data' => $data
    ]);
}
