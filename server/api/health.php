<?php
/**
 * Health Check API Endpoint
 * Returns server status and information
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../utils/cors.php';

// Handle CORS
handleCORS();

// Only accept GET requests
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    sendError('Method not allowed', 405);
}

$status = [
    'status' => 'ok',
    'version' => API_VERSION,
    'timestamp' => date('c'),
    'php_version' => phpversion(),
    'server' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown'
];

sendSuccess($status, 'API is healthy');
