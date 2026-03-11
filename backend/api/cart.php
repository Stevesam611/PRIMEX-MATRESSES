<?php
/**
 * Primex Mattress & Beddings - Shopping Cart API
 */

require_once __DIR__ . '/../includes/database.php';

session_start();
$method = $_SERVER['REQUEST_METHOD'];
$db = Database::getInstance();

// Get or create cart session ID
function getCartSessionId() {
    if (!isset($_SESSION['cart_session_id'])) {
        $_SESSION['cart_session_id'] = session_id() . '_' . uniqid();
    }
    return $_SESSION['cart_session_id'];
}

// Get cart by session
function getCart($db) {
    $sessionId = getCartSessionId();
    
    $stmt = $db->query(
        "SELECT * FROM shopping_cart WHERE session_id = :session_id",
        ['session_id' => $sessionId]
    );
    $cart = $stmt->fetch();
    
    if (!$cart) {
        $db->query(
            "INSERT INTO shopping_cart (session_id) VALUES (:session_id)",
            ['session_id' => $sessionId]
        );
        $cartId = $db->lastInsertId();
        return ['id' => $cartId, 'session_id' => $sessionId];
    }
    
    return $cart;
}

// Get cart items with product details
function getCartItems($db, $cartId) {
    $sql = "SELECT ci.*, p.name, p.slug, p.main_image, p.stock_quantity,
            COALESCE(p.discount_price, p.price) as current_price
            FROM cart_items ci
            JOIN products p ON ci.product_id = p.id
            WHERE ci.cart_id = :cart_id";
    
    $stmt = $db->query($sql, ['cart_id' => $cartId]);
    return $stmt->fetchAll();
}

// Calculate cart totals
function calculateCartTotals($items) {
    $subtotal = 0;
    $itemCount = 0;
    
    foreach ($items as $item) {
        $subtotal += $item['current_price'] * $item['quantity'];
        $itemCount += $item['quantity'];
    }
    
    // Calculate shipping (free over $500)
    $shipping = $subtotal >= 500 ? 0 : 49.99;
    
    // Calculate tax (8%)
    $tax = $subtotal * 0.08;
    
    return [
        'subtotal' => round($subtotal, 2),
        'shipping' => round($shipping, 2),
        'tax' => round($tax, 2),
        'total' => round($subtotal + $shipping + $tax, 2),
        'item_count' => $itemCount
    ];
}

try {
    switch ($method) {
        case 'GET':
            // Get cart contents
            $cart = getCart($db);
            $items = getCartItems($db, $cart['id']);
            $totals = calculateCartTotals($items);
            
            successResponse([
                'cart_id' => $cart['id'],
                'items' => $items,
                'totals' => $totals
            ]);
            break;
            
        case 'POST':
            // Add item to cart
            $data = json_decode(file_get_contents('php://input'), true);
            
            $productId = $data['product_id'];
            $quantity = max(1, intval($data['quantity'] ?? 1));
            
            // Get product details
            $prodStmt = $db->query(
                "SELECT id, stock_quantity, price, discount_price FROM products WHERE id = :id AND is_active = TRUE",
                ['id' => $productId]
            );
            $product = $prodStmt->fetch();
            
            if (!$product) {
                errorResponse('Product not found');
            }
            
            if ($product['stock_quantity'] < $quantity) {
                errorResponse('Not enough stock available');
            }
            
            $cart = getCart($db);
            $price = $product['discount_price'] ?? $product['price'];
            
            // Check if item already in cart
            $checkStmt = $db->query(
                "SELECT id, quantity FROM cart_items WHERE cart_id = :cart_id AND product_id = :product_id",
                ['cart_id' => $cart['id'], 'product_id' => $productId]
            );
            $existingItem = $checkStmt->fetch();
            
            if ($existingItem) {
                // Update quantity
                $newQuantity = $existingItem['quantity'] + $quantity;
                if ($newQuantity > $product['stock_quantity']) {
                    errorResponse('Not enough stock available');
                }
                
                $db->query(
                    "UPDATE cart_items SET quantity = :quantity, updated_at = NOW() WHERE id = :id",
                    ['quantity' => $newQuantity, 'id' => $existingItem['id']]
                );
            } else {
                // Add new item
                $db->query(
                    "INSERT INTO cart_items (cart_id, product_id, quantity, price_at_time) VALUES (:cart_id, :product_id, :quantity, :price)",
                    ['cart_id' => $cart['id'], 'product_id' => $productId, 'quantity' => $quantity, 'price' => $price]
                );
            }
            
            // Return updated cart
            $items = getCartItems($db, $cart['id']);
            $totals = calculateCartTotals($items);
            
            successResponse([
                'message' => 'Item added to cart',
                'items' => $items,
                'totals' => $totals
            ]);
            break;
            
        case 'PUT':
            // Update cart item quantity
            $data = json_decode(file_get_contents('php://input'), true);
            
            $itemId = $data['item_id'];
            $quantity = intval($data['quantity']);
            
            if ($quantity < 1) {
                // Remove item if quantity is 0 or less
                $db->query("DELETE FROM cart_items WHERE id = :id", ['id' => $itemId]);
            } else {
                // Check stock
                $checkStmt = $db->query(
                    "SELECT ci.*, p.stock_quantity FROM cart_items ci JOIN products p ON ci.product_id = p.id WHERE ci.id = :id",
                    ['id' => $itemId]
                );
                $item = $checkStmt->fetch();
                
                if (!$item) {
                    errorResponse('Cart item not found');
                }
                
                if ($quantity > $item['stock_quantity']) {
                    errorResponse('Not enough stock available');
                }
                
                $db->query(
                    "UPDATE cart_items SET quantity = :quantity, updated_at = NOW() WHERE id = :id",
                    ['quantity' => $quantity, 'id' => $itemId]
                );
            }
            
            // Return updated cart
            $cart = getCart($db);
            $items = getCartItems($db, $cart['id']);
            $totals = calculateCartTotals($items);
            
            successResponse([
                'message' => 'Cart updated',
                'items' => $items,
                'totals' => $totals
            ]);
            break;
            
        case 'DELETE':
            // Remove item from cart
            $itemId = $_GET['item_id'] ?? null;
            
            if (!$itemId) {
                errorResponse('Item ID is required');
            }
            
            $db->query("DELETE FROM cart_items WHERE id = :id", ['id' => $itemId]);
            
            // Return updated cart
            $cart = getCart($db);
            $items = getCartItems($db, $cart['id']);
            $totals = calculateCartTotals($items);
            
            successResponse([
                'message' => 'Item removed from cart',
                'items' => $items,
                'totals' => $totals
            ]);
            break;
            
        default:
            errorResponse('Method not allowed', 405);
    }
} catch (Exception $e) {
    error_log("Cart API error: " . $e->getMessage());
    errorResponse('An error occurred', 500);
}
?>