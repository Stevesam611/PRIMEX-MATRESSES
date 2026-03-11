<?php
/**
 * Primex Mattress & Beddings - User Messages API
 * Returns contact messages submitted by the currently logged-in user
 */

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/database.php';

header('Content-Type: application/json');

if (session_status() === PHP_SESSION_NONE) session_start();

function umSuccess($data) { echo json_encode(['success' => true, 'data' => $data]); exit; }
function umError($msg, $code = 400) {
    http_response_code($code);
    echo json_encode(['success' => false, 'error' => $msg]);
    exit;
}

if (empty($_SESSION['user_logged_in'])) {
    umError('Please sign in to view your messages', 401);
}

$email = $_SESSION['user_email'];
$db    = Database::getInstance();

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'GET') umError('Method not allowed', 405);

    $messages = $db->query(
        "SELECT id, first_name, last_name, subject, message, status, admin_reply, replied_at, created_at
         FROM contact_messages
         WHERE email = :email
         ORDER BY created_at DESC",
        ['email' => $email]
    )->fetchAll();

    umSuccess(['messages' => $messages]);

} catch (Exception $e) {
    error_log("User messages error: " . $e->getMessage());
    umError('An error occurred', 500);
}
?>
