<?php
/**
 * Primex Mattress & Beddings - File Upload API
 */

require_once __DIR__ . '/../includes/auth.php';

session_start();
$auth->requireAdmin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    errorResponse('Method not allowed', 405);
}

if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
    errorResponse('No file uploaded or upload error');
}

$file = $_FILES['image'];
$allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp', 'image/gif'];
$maxSize = 5 * 1024 * 1024; // 5MB

// Validate type
$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mimeType = finfo_file($finfo, $file['tmp_name']);
finfo_close($finfo);

if (!in_array($mimeType, $allowedTypes)) {
    errorResponse('Invalid file type. Only JPG, PNG, WEBP and GIF are allowed.');
}

// Validate size
if ($file['size'] > $maxSize) {
    errorResponse('File too large. Maximum size is 5MB.');
}

// Generate unique filename
$ext = pathinfo($file['name'], PATHINFO_EXTENSION);
$filename = uniqid('product_') . '.' . strtolower($ext);
$uploadDir = __DIR__ . '/../uploads/products/';
$uploadPath = $uploadDir . $filename;

if (!move_uploaded_file($file['tmp_name'], $uploadPath)) {
    errorResponse('Failed to save uploaded file.');
}

$url = '/PRIMEX-MATRESSES/backend/uploads/products/' . $filename;

successResponse(['url' => $url], 'File uploaded successfully');
?>
