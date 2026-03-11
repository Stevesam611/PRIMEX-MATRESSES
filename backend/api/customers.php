<?php
/**
 * Primex Mattress & Beddings - Customers API
 * Derives customer data from the orders table
 */

require_once __DIR__ . '/../includes/auth.php';

$auth->requireAdmin();

$method = $_SERVER['REQUEST_METHOD'];
$db = Database::getInstance();

try {
    if ($method !== 'GET') {
        errorResponse('Method not allowed', 405);
    }

    if (isset($_GET['email']) && !empty($_GET['email'])) {
        // Get single customer with full order history
        $email = $_GET['email'];

        $customerStmt = $db->query(
            "SELECT customer_email as email, customer_phone as phone,
                    shipping_first_name as first_name, shipping_last_name as last_name,
                    shipping_city as city, shipping_country as country,
                    COALESCE(notes, '') as checkout_mode
             FROM orders
             WHERE customer_email = :email
             ORDER BY created_at DESC
             LIMIT 1",
            ['email' => $email]
        );
        $customer = $customerStmt->fetch();

        if (!$customer) {
            errorResponse('Customer not found', 404);
        }

        $ordersStmt = $db->query(
            "SELECT id, order_number, status, total_amount, created_at,
                    (SELECT COUNT(*) FROM order_items WHERE order_id = orders.id) as item_count
             FROM orders
             WHERE customer_email = :email
             ORDER BY created_at DESC",
            ['email' => $email]
        );
        $orders = $ordersStmt->fetchAll();

        successResponse([
            'customer' => $customer,
            'orders' => $orders
        ]);

    } else {
        // Get all customers (grouped by email)
        $customersStmt = $db->query(
            "SELECT
                customer_email as email,
                customer_phone as phone,
                MAX(shipping_first_name) as first_name,
                MAX(shipping_last_name) as last_name,
                MAX(shipping_city) as city,
                MAX(shipping_country) as country,
                COUNT(*) as order_count,
                SUM(total_amount) as total_spent,
                MAX(created_at) as last_order_date,
                COALESCE(MAX(CASE WHEN notes IS NOT NULL THEN notes END), 'guest') as checkout_mode
             FROM orders
             GROUP BY customer_email, customer_phone
             ORDER BY last_order_date DESC"
        );
        $customers = $customersStmt->fetchAll();

        $statsStmt = $db->query(
            "SELECT
                COUNT(DISTINCT customer_email) as total_customers,
                COUNT(*) as total_orders,
                SUM(total_amount) as total_revenue
             FROM orders"
        );
        $stats = $statsStmt->fetch();

        successResponse([
            'customers' => $customers,
            'stats' => $stats
        ]);
    }

} catch (PDOException $e) {
    error_log("Customers API error: " . $e->getMessage());
    errorResponse('An error occurred: ' . $e->getMessage(), 500);
} catch (Exception $e) {
    error_log("Customers API error: " . $e->getMessage());
    errorResponse('An error occurred', 500);
}
?>
