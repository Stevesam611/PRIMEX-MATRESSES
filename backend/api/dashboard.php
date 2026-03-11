<?php
/**
 * Primex Mattress & Beddings - Admin Dashboard API
 */

require_once __DIR__ . '/../includes/auth.php';

$db = Database::getInstance();
$auth->requireAdmin();

$method = $_SERVER['REQUEST_METHOD'];

try {
    switch ($method) {
        case 'GET':
            $stats = [];
            
            // Total orders
            $stmt = $db->query("SELECT COUNT(*) as total FROM orders");
            $stats['total_orders'] = $stmt->fetch()['total'];
            
            // Total products
            $stmt = $db->query("SELECT COUNT(*) as total FROM products WHERE is_active = TRUE");
            $stats['total_products'] = $stmt->fetch()['total'];
            
            // Total categories
            $stmt = $db->query("SELECT COUNT(*) as total FROM categories WHERE is_active = TRUE");
            $stats['total_categories'] = $stmt->fetch()['total'];
            
            // Total customers (derived from orders)
            $stmt = $db->query("SELECT COUNT(DISTINCT customer_email) as total FROM orders");
            $stats['total_customers'] = $stmt->fetch()['total'];
            
            // Sales summary
            $stmt = $db->query("SELECT 
                COALESCE(SUM(total_amount), 0) as total_sales,
                COALESCE(SUM(CASE WHEN created_at >= CURRENT_DATE THEN total_amount END), 0) as today_sales,
                COALESCE(SUM(CASE WHEN created_at >= CURRENT_DATE - INTERVAL '7 days' THEN total_amount END), 0) as week_sales,
                COALESCE(SUM(CASE WHEN created_at >= CURRENT_DATE - INTERVAL '30 days' THEN total_amount END), 0) as month_sales
                FROM orders WHERE status != 'cancelled'");
            $sales = $stmt->fetch();
            $stats['sales'] = $sales;
            
            // Orders by status
            $stmt = $db->query("SELECT status, COUNT(*) as count FROM orders GROUP BY status");
            $stats['orders_by_status'] = $stmt->fetchAll();

            // Notification counts
            $stmt = $db->query("SELECT COUNT(*) as count FROM orders WHERE status = 'pending'");
            $stats['pending_orders_count'] = (int)$stmt->fetch()['count'];

            $stmt = $db->query("SELECT COUNT(*) as count FROM product_reviews WHERE is_approved = FALSE");
            $stats['pending_reviews_count'] = (int)$stmt->fetch()['count'];

            $stmt = $db->query("SELECT COUNT(*) as count FROM products WHERE stock_quantity < 10 AND is_active = TRUE");
            $stats['low_stock_count'] = (int)$stmt->fetch()['count'];
            
            // Recent orders
            $stmt = $db->query("SELECT o.*, 
                (SELECT COUNT(*) FROM order_items WHERE order_id = o.id) as item_count
                FROM orders o 
                ORDER BY o.created_at DESC 
                LIMIT 10");
            $stats['recent_orders'] = $stmt->fetchAll();
            
            // Low stock products
            $stmt = $db->query("SELECT id, name, sku, stock_quantity FROM products WHERE stock_quantity < 10 AND is_active = TRUE ORDER BY stock_quantity ASC LIMIT 10");
            $stats['low_stock'] = $stmt->fetchAll();
            
            // Monthly sales data for chart (filtered by year)
            $year = isset($_GET['year']) ? (int)$_GET['year'] : (int)date('Y');
            $stmt = $db->query("SELECT
                DATE_TRUNC('month', created_at) as month,
                COUNT(*) as order_count,
                COALESCE(SUM(total_amount), 0) as revenue
                FROM orders
                WHERE EXTRACT(YEAR FROM created_at) = :year
                AND status != 'cancelled'
                GROUP BY DATE_TRUNC('month', created_at)
                ORDER BY month ASC",
                ['year' => $year]
            );
            $stats['monthly_sales'] = $stmt->fetchAll();
            $stats['sales_year'] = $year;
            
            // Top selling products
            $stmt = $db->query("SELECT 
                p.id, p.name, p.main_image,
                SUM(oi.quantity) as total_sold,
                SUM(oi.total_price) as total_revenue
                FROM order_items oi
                JOIN products p ON oi.product_id = p.id
                JOIN orders o ON oi.order_id = o.id
                WHERE o.status != 'cancelled'
                GROUP BY p.id, p.name, p.main_image
                ORDER BY total_sold DESC
                LIMIT 5");
            $stats['top_products'] = $stmt->fetchAll();
            
            successResponse($stats);
            break;
            
        default:
            errorResponse('Method not allowed', 405);
    }
} catch (Exception $e) {
    error_log("Dashboard API error: " . $e->getMessage());
    errorResponse('An error occurred', 500);
}
?>