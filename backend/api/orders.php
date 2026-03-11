<?php
/**
 * Primex Mattress & Beddings - Orders API
 */

require_once __DIR__ . '/../includes/database.php';

session_start();
$method = $_SERVER['REQUEST_METHOD'];
$db = Database::getInstance();

try {
    switch ($method) {
        case 'GET':
            // Check if admin
            if (isset($_GET['admin']) && $_GET['admin'] == '1') {
                $auth->requireAdmin();
                
                $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 20;
                $offset = ($page - 1) * $limit;
                
                $where = ["1=1"];
                $params = [];
                
                if (isset($_GET['status']) && !empty($_GET['status'])) {
                    $where[] = "status = :status";
                    $params['status'] = $_GET['status'];
                }
                
                $whereClause = implode(' AND ', $where);
                
                // Get total
                $countStmt = $db->query("SELECT COUNT(*) as total FROM orders WHERE $whereClause", $params);
                $total = $countStmt->fetch()['total'];
                
                // Get orders
                $sql = "SELECT o.*, 
                        (SELECT COUNT(*) FROM order_items WHERE order_id = o.id) as item_count
                        FROM orders o 
                        WHERE $whereClause 
                        ORDER BY o.created_at DESC 
                        LIMIT :limit OFFSET :offset";
                
                $params['limit'] = $limit;
                $params['offset'] = $offset;
                
                $stmt = $db->query($sql, $params);
                $orders = $stmt->fetchAll();
                
                successResponse([
                    'orders' => $orders,
                    'pagination' => [
                        'page' => $page,
                        'limit' => $limit,
                        'total' => $total,
                        'pages' => ceil($total / $limit)
                    ]
                ]);
            } elseif (isset($_GET['id'])) {
                // Get single order
                $sql = "SELECT o.* FROM orders o WHERE o.id = :id";
                $stmt = $db->query($sql, ['id' => $_GET['id']]);
                $order = $stmt->fetch();
                
                if ($order) {
                    // Get order items
                    $itemsStmt = $db->query(
                        "SELECT oi.*, p.main_image FROM order_items oi LEFT JOIN products p ON oi.product_id = p.id WHERE oi.order_id = :order_id",
                        ['order_id' => $order['id']]
                    );
                    $order['items'] = $itemsStmt->fetchAll();
                    
                    successResponse($order);
                } else {
                    errorResponse('Order not found', 404);
                }
            }
            break;
            
        case 'POST':
            // Create new order
            $data = json_decode(file_get_contents('php://input'), true);
            
            $db->beginTransaction();
            
            try {
                // Create order
                $orderNumber = generateOrderNumber();
                
                $sql = "INSERT INTO orders (
                    order_number, customer_email, customer_phone,
                    shipping_first_name, shipping_last_name, shipping_address, shipping_city, 
                    shipping_state, shipping_zip, shipping_country,
                    billing_first_name, billing_last_name, billing_address, billing_city, 
                    billing_state, billing_zip, billing_country,
                    subtotal, shipping_cost, tax_amount, discount_amount, total_amount,
                    status, payment_status, payment_method, notes
                ) VALUES (
                    :order_number, :customer_email, :customer_phone,
                    :shipping_first_name, :shipping_last_name, :shipping_address, :shipping_city, 
                    :shipping_state, :shipping_zip, :shipping_country,
                    :billing_first_name, :billing_last_name, :billing_address, :billing_city, 
                    :billing_state, :billing_zip, :billing_country,
                    :subtotal, :shipping_cost, :tax_amount, :discount_amount, :total_amount,
                    :status, :payment_status, :payment_method, :notes
                )";
                
                $params = [
                    'order_number' => $orderNumber,
                    'customer_email' => $data['email'],
                    'customer_phone' => $data['phone'],
                    'shipping_first_name' => $data['shipping']['first_name'],
                    'shipping_last_name' => $data['shipping']['last_name'],
                    'shipping_address' => $data['shipping']['address'],
                    'shipping_city' => $data['shipping']['city'],
                    'shipping_state' => $data['shipping']['state'],
                    'shipping_zip' => $data['shipping']['zip'],
                    'shipping_country' => $data['shipping']['country'] ?? 'USA',
                    'billing_first_name' => $data['billing']['first_name'],
                    'billing_last_name' => $data['billing']['last_name'],
                    'billing_address' => $data['billing']['address'],
                    'billing_city' => $data['billing']['city'],
                    'billing_state' => $data['billing']['state'],
                    'billing_zip' => $data['billing']['zip'],
                    'billing_country' => $data['billing']['country'] ?? 'USA',
                    'subtotal' => $data['subtotal'],
                    'shipping_cost' => $data['shipping'],
                    'tax_amount' => $data['tax'],
                    'discount_amount' => $data['discount'] ?? 0,
                    'total_amount' => $data['total'],
                    'status' => 'pending',
                    'payment_status' => 'pending',
                    'payment_method' => $data['payment_method'],
                    'notes' => $data['notes'] ?? null
                ];
                
                $db->query($sql, $params);
                $orderId = $db->lastInsertId();
                
                // Insert order items
                foreach ($data['items'] as $item) {
                    $db->query(
                        "INSERT INTO order_items (order_id, product_id, product_name, product_sku, quantity, unit_price, total_price) 
                         VALUES (:order_id, :product_id, :product_name, :product_sku, :quantity, :unit_price, :total_price)",
                        [
                            'order_id' => $orderId,
                            'product_id' => $item['product_id'],
                            'product_name' => $item['name'],
                            'product_sku' => $item['sku'] ?? null,
                            'quantity' => $item['quantity'],
                            'unit_price' => $item['price'],
                            'total_price' => $item['price'] * $item['quantity']
                        ]
                    );
                    
                    // Update product stock
                    $db->query(
                        "UPDATE products SET stock_quantity = stock_quantity - :qty WHERE id = :id",
                        ['qty' => $item['quantity'], 'id' => $item['product_id']]
                    );
                }
                
                // Clear cart
                if (isset($_SESSION['cart_session_id'])) {
                    $cartStmt = $db->query(
                        "SELECT id FROM shopping_cart WHERE session_id = :session_id",
                        ['session_id' => $_SESSION['cart_session_id']]
                    );
                    $cart = $cartStmt->fetch();
                    if ($cart) {
                        $db->query("DELETE FROM cart_items WHERE cart_id = :cart_id", ['cart_id' => $cart['id']]);
                    }
                }
                
                $db->commit();
                
                successResponse([
                    'order_id' => $orderId,
                    'order_number' => $orderNumber,
                    'message' => 'Order placed successfully'
                ]);
                
            } catch (Exception $e) {
                $db->rollback();
                throw $e;
            }
            break;
            
        case 'PUT':
            // Update order status (admin only)
            $auth->requireAdmin();
            
            $data = json_decode(file_get_contents('php://input'), true);
            $orderId = $data['id'];
            
            $sql = "UPDATE orders SET 
                    status = :status, 
                    payment_status = :payment_status,
                    updated_at = NOW()
                    WHERE id = :id";
            
            $params = [
                'id' => $orderId,
                'status' => $data['status'],
                'payment_status' => $data['payment_status'] ?? 'pending'
            ];
            
            $db->query($sql, $params);
            
            successResponse(['message' => 'Order updated successfully']);
            break;
            
        default:
            errorResponse('Method not allowed', 405);
    }
} catch (Exception $e) {
    error_log("Orders API error: " . $e->getMessage());
    errorResponse('An error occurred: ' . $e->getMessage(), 500);
}
?>