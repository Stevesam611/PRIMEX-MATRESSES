<?php
/**
 * Primex Mattress & Beddings - Products API
 */

require_once __DIR__ . '/../includes/auth.php';

$method = $_SERVER['REQUEST_METHOD'];
$db = Database::getInstance();

try {
    switch ($method) {
        case 'GET':
            // Get single product or list
            if (isset($_GET['slug'])) {
                // Get product by slug
                $sql = "SELECT p.*, c.name as category_name, c.slug as category_slug 
                        FROM products p 
                        LEFT JOIN categories c ON p.category_id = c.id 
                        WHERE p.slug = :slug AND p.is_active = TRUE";
                $stmt = $db->query($sql, ['slug' => $_GET['slug']]);
                $product = $stmt->fetch();
                
                if ($product) {
                    // Get product images
                    $imgStmt = $db->query(
                        "SELECT * FROM product_images WHERE product_id = :id ORDER BY is_primary DESC, sort_order",
                        ['id' => $product['id']]
                    );
                    $product['images'] = $imgStmt->fetchAll();
                    
                    // Get product reviews
                    $revStmt = $db->query(
                        "SELECT * FROM product_reviews WHERE product_id = :id AND is_approved = TRUE ORDER BY created_at DESC",
                        ['id' => $product['id']]
                    );
                    $product['reviews'] = $revStmt->fetchAll();
                    
                    // Calculate average rating
                    $avgStmt = $db->query(
                        "SELECT AVG(rating) as avg_rating, COUNT(*) as review_count FROM product_reviews WHERE product_id = :id AND is_approved = TRUE",
                        ['id' => $product['id']]
                    );
                    $ratingData = $avgStmt->fetch();
                    $product['average_rating'] = round($ratingData['avg_rating'], 1);
                    $product['review_count'] = $ratingData['review_count'];
                    
                    successResponse($product);
                } else {
                    errorResponse('Product not found', 404);
                }
            } elseif (isset($_GET['id'])) {
                // Get product by ID
                $sql = "SELECT p.*, c.name as category_name 
                        FROM products p 
                        LEFT JOIN categories c ON p.category_id = c.id 
                        WHERE p.id = :id";
                $stmt = $db->query($sql, ['id' => $_GET['id']]);
                $product = $stmt->fetch();
                
                if ($product) {
                    // Get product images
                    $imgStmt = $db->query(
                        "SELECT * FROM product_images WHERE product_id = :id ORDER BY is_primary DESC, sort_order",
                        ['id' => $product['id']]
                    );
                    $product['images'] = $imgStmt->fetchAll();
                    
                    successResponse($product);
                } else {
                    errorResponse('Product not found', 404);
                }
            } else {
                // Get product list
                $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 12;
                $offset = ($page - 1) * $limit;
                
                $where = ["p.is_active = TRUE"];
                $params = [];
                
                // Filter by category
                if (isset($_GET['category']) && !empty($_GET['category'])) {
                    $where[] = "c.slug = :category";
                    $params['category'] = $_GET['category'];
                }
                
                // Filter by featured
                if (isset($_GET['featured']) && $_GET['featured'] == '1') {
                    $where[] = "p.is_featured = TRUE";
                }
                
                // Search
                if (isset($_GET['search']) && !empty($_GET['search'])) {
                    $where[] = "(p.name ILIKE :search OR p.description ILIKE :search)";
                    $params['search'] = '%' . $_GET['search'] . '%';
                }
                
                // Price range
                if (isset($_GET['min_price']) && is_numeric($_GET['min_price'])) {
                    $where[] = "COALESCE(p.discount_price, p.price) >= :min_price";
                    $params['min_price'] = $_GET['min_price'];
                }
                if (isset($_GET['max_price']) && is_numeric($_GET['max_price'])) {
                    $where[] = "COALESCE(p.discount_price, p.price) <= :max_price";
                    $params['max_price'] = $_GET['max_price'];
                }
                
                $whereClause = implode(' AND ', $where);
                
                // Get total count
                $countSql = "SELECT COUNT(*) as total FROM products p LEFT JOIN categories c ON p.category_id = c.id WHERE $whereClause";
                $countStmt = $db->query($countSql, $params);
                $total = $countStmt->fetch()['total'];
                
                // Get products
                $sql = "SELECT p.*, c.name as category_name, c.slug as category_slug 
                        FROM products p 
                        LEFT JOIN categories c ON p.category_id = c.id 
                        WHERE $whereClause 
                        ORDER BY p.is_featured DESC, p.created_at DESC 
                        LIMIT :limit OFFSET :offset";
                
                $params['limit'] = $limit;
                $params['offset'] = $offset;
                
                $stmt = $db->query($sql, $params);
                $products = $stmt->fetchAll();
                
                successResponse([
                    'products' => $products,
                    'pagination' => [
                        'page' => $page,
                        'limit' => $limit,
                        'total' => $total,
                        'pages' => ceil($total / $limit)
                    ]
                ]);
            }
            break;
            
        case 'POST':
            // Create new product (admin only)
            $auth->requireAdmin();
            
            $data = json_decode(file_get_contents('php://input'), true);

            // Generate unique slug
            $baseSlug = generateSlug($data['name']);
            $slug = $baseSlug;
            $slugCount = 1;
            while (true) {
                $existing = $db->query("SELECT id FROM products WHERE slug = :slug", ['slug' => $slug])->fetch();
                if (!$existing) break;
                $slug = $baseSlug . '-' . $slugCount++;
            }

            $sql = "INSERT INTO products (name, slug, description, short_description, price, discount_price, sku, stock_quantity, category_id, main_image, specifications, features, is_featured, weight, dimensions, warranty)
                    VALUES (:name, :slug, :description, :short_description, :price, :discount_price, :sku, :stock_quantity, :category_id, :main_image, :specifications, :features, :is_featured, :weight, :dimensions, :warranty)
                    RETURNING id";

            $params = [
                'name' => $data['name'],
                'slug' => $slug,
                'description' => $data['description'] ?? null,
                'short_description' => $data['short_description'] ?? null,
                'price' => $data['price'],
                'discount_price' => !empty($data['discount_price']) ? $data['discount_price'] : null,
                'sku' => !empty($data['sku']) ? $data['sku'] : null,
                'stock_quantity' => $data['stock_quantity'] ?? 0,
                'category_id' => $data['category_id'],
                'main_image' => $data['main_image'] ?? null,
                'specifications' => json_encode($data['specifications'] ?? []),
                'features' => json_encode($data['features'] ?? []),
                'is_featured' => !empty($data['is_featured']) ? 'true' : 'false',
                'weight' => $data['weight'] ?? null,
                'dimensions' => $data['dimensions'] ?? null,
                'warranty' => $data['warranty'] ?? null
            ];

            $stmt = $db->query($sql, $params);
            $row = $stmt->fetch();
            $productId = $row['id'];

            successResponse(['id' => $productId, 'message' => 'Product created successfully']);
            break;
            
        case 'PUT':
            // Update product (admin only)
            $auth->requireAdmin();
            
            $data = json_decode(file_get_contents('php://input'), true);
            $id = $data['id'];
            
            $sql = "UPDATE products SET
                    name = :name,
                    description = :description,
                    short_description = :short_description,
                    price = :price,
                    discount_price = :discount_price,
                    sku = :sku,
                    stock_quantity = :stock_quantity,
                    category_id = :category_id,
                    main_image = :main_image,
                    specifications = :specifications,
                    features = :features,
                    is_featured = :is_featured,
                    is_active = :is_active,
                    weight = :weight,
                    dimensions = :dimensions,
                    warranty = :warranty,
                    updated_at = NOW()
                    WHERE id = :id";
            
            $params = [
                'id' => $id,
                'name' => $data['name'],
                'description' => $data['description'],
                'short_description' => $data['short_description'],
                'price' => $data['price'],
                'discount_price' => !empty($data['discount_price']) ? $data['discount_price'] : null,
                'sku' => $data['sku'] ?? null,
                'stock_quantity' => $data['stock_quantity'],
                'category_id' => $data['category_id'],
                'main_image' => $data['main_image'] ?? null,
                'specifications' => json_encode($data['specifications'] ?? []),
                'features' => json_encode($data['features'] ?? []),
                'is_featured' => !empty($data['is_featured']) ? 'true' : 'false',
                'is_active' => !empty($data['is_active']) ? 'true' : 'false',
                'weight' => $data['weight'] ?? null,
                'dimensions' => $data['dimensions'] ?? null,
                'warranty' => $data['warranty'] ?? null
            ];
            
            $db->query($sql, $params);
            
            successResponse(['message' => 'Product updated successfully']);
            break;
            
        case 'DELETE':
            // Delete product (admin only)
            $auth->requireAdmin();
            
            $id = $_GET['id'] ?? null;
            if (!$id) {
                errorResponse('Product ID is required');
            }
            
            // Soft delete - just deactivate
            $db->query(
                "UPDATE products SET is_active = FALSE, updated_at = NOW() WHERE id = :id",
                ['id' => $id]
            );
            
            successResponse(['message' => 'Product deleted successfully']);
            break;
            
        default:
            errorResponse('Method not allowed', 405);
    }
} catch (PDOException $e) {
    error_log("Products API error: " . $e->getMessage());
    // Unique constraint violations
    if (strpos($e->getMessage(), 'products_sku_key') !== false) {
        errorResponse('A product with this SKU already exists. Please use a different SKU.');
    } elseif (strpos($e->getMessage(), 'products_slug_key') !== false) {
        errorResponse('A product with this name already exists.');
    }
    errorResponse('An error occurred: ' . $e->getMessage(), 500);
} catch (Exception $e) {
    error_log("Products API error: " . $e->getMessage());
    errorResponse('An error occurred', 500);
}
?>