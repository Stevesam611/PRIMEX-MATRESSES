<?php
/**
 * Primex Mattress & Beddings - Configuration File
 */

// Database Configuration
define('DB_HOST', 'localhost');
define('DB_PORT', '5432');
define('DB_NAME', 'primex_mattress');
define('DB_USER', 'postgres');
define('DB_PASS', 'your_password_here');

// Application Configuration
define('APP_NAME', 'Primex Mattress & Beddings');
define('APP_URL', 'http://localhost/primex-mattress');
define('ADMIN_URL', 'http://localhost/primex-mattress/admin');
define('UPLOAD_PATH', __DIR__ . '/../uploads/');
define('UPLOAD_URL', APP_URL . '/backend/uploads/');

// Security Configuration
define('JWT_SECRET', 'your-secret-key-change-this-in-production');
define('SESSION_LIFETIME', 86400); // 24 hours

// Email Configuration
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USER', 'your-email@gmail.com');
define('SMTP_PASS', 'your-email-password');
define('ADMIN_EMAIL', 'admin@primex.com');

// Payment Configuration (Stripe example)
define('STRIPE_PUBLIC_KEY', 'pk_test_your_key');
define('STRIPE_SECRET_KEY', 'sk_test_your_key');

// Error Reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Timezone
date_default_timezone_set('America/New_York');

// CORS Headers for API
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
header('Content-Type: application/json; charset=UTF-8');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}
?>