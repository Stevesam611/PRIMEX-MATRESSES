<?php
/**
 * Primex Mattress & Beddings - User Orders API
 * Returns orders for the currently logged-in user
 */

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/database.php';

header('Content-Type: application/json');

if (session_status() === PHP_SESSION_NONE) session_start();

function uoSuccess($data) { echo json_encode(['success' => true, 'data' => $data]); exit; }
function uoError($msg, $code = 400) {
    http_response_code($code);
    echo json_encode(['success' => false, 'error' => $msg]);
    exit;
}

// Must be logged in
if (empty($_SESSION['user_logged_in'])) {
    uoError('Please sign in to view your orders', 401);
}

$email = $_SESSION['user_email'];
$db    = Database::getInstance();

try {
    // Cancel order (PUT)
    if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
        $body = json_decode(file_get_contents('php://input'), true);
        $id   = isset($body['id']) ? (int)$body['id'] : 0;
        if (!$id) uoError('Invalid order ID');

        // Verify order belongs to this user and is still pending
        $order = $db->query(
            "SELECT id, status FROM orders WHERE id = :id AND customer_email = :email",
            ['id' => $id, 'email' => $email]
        )->fetch();

        if (!$order)                         uoError('Order not found', 404);
        if ($order['status'] !== 'pending')  uoError('Only pending orders can be cancelled');

        $db->query(
            "UPDATE orders SET status = 'cancelled', updated_at = NOW() WHERE id = :id",
            ['id' => $id]
        );
        uoSuccess(['message' => 'Order cancelled successfully']);
    }

    if ($_SERVER['REQUEST_METHOD'] !== 'GET') uoError('Method not allowed', 405);

    // Single order with items
    if (isset($_GET['id'])) {
        $order = $db->query(
            "SELECT * FROM orders WHERE id = :id AND customer_email = :email",
            ['id' => (int)$_GET['id'], 'email' => $email]
        )->fetch();

        if (!$order) uoError('Order not found', 404);

        $items = $db->query(
            "SELECT oi.*, p.main_image, p.slug FROM order_items oi
             LEFT JOIN products p ON oi.product_id = p.id
             WHERE oi.order_id = :order_id",
            ['order_id' => $order['id']]
        )->fetchAll();

        $order['items'] = $items;
        uoSuccess($order);
    }

    // All orders for user
    $orders = $db->query(
        "SELECT o.id, o.order_number, o.status, o.payment_status, o.total_amount,
                o.created_at, o.shipping_first_name, o.shipping_last_name, o.shipping_city,
                (SELECT COUNT(*) FROM order_items WHERE order_id = o.id) as item_count
         FROM orders o
         WHERE o.customer_email = :email
         ORDER BY o.created_at DESC",
        ['email' => $email]
    )->fetchAll();

    uoSuccess(['orders' => $orders, 'email' => $email]);

} catch (Exception $e) {
    error_log("User orders error: " . $e->getMessage());
    uoError('An error occurred: ' . $e->getMessage(), 500);
}
?>
