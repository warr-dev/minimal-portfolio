<?php
/**
 * Portfolio API Entry Point
 * Simple router for API endpoints
 */

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/utils/cors.php';

// Handle CORS
handleCORS();

// Get the request URI and method
$requestUri = $_SERVER['REQUEST_URI'];
$requestMethod = $_SERVER['REQUEST_METHOD'];

// Parse the path
$path = parse_url($requestUri, PHP_URL_PATH);
$path = str_replace('/server', '', $path); // Remove /server prefix if present
$path = trim($path, '/');

// Simple routing
switch ($path) {
    case '':
    case 'api':
        sendJSON([
            'name' => 'Warren Dalawampu Portfolio API',
            'version' => API_VERSION,
            'endpoints' => [
                'GET /api/health' => 'Health check',
                'POST /api/contact' => 'Submit contact form'
            ]
        ]);
        break;

    case 'api/health':
        require __DIR__ . '/api/health.php';
        break;

    case 'api/contact':
        require __DIR__ . '/api/contact.php';
        break;

    default:
        sendError('Endpoint not found', 404);
}
