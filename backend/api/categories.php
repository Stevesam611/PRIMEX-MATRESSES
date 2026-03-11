<?php
/**
 * Primex Mattress & Beddings - Categories API
 */

require_once __DIR__ . '/../includes/database.php';

$method = $_SERVER['REQUEST_METHOD'];
$db = Database::getInstance();

try {
    switch ($method) {
        case 'GET':
            if (isset($_GET['slug'])) {
                // Get single category
                $sql = "SELECT c.*, COUNT(p.id) as product_count 
                        FROM categories c 
                        LEFT JOIN products p ON c.id = p.category_id AND p.is_active = TRUE 
                        WHERE c.slug = :slug 
                        GROUP BY c.id";
                $stmt = $db->query($sql, ['slug' => $_GET['slug']]);
                $category = $stmt->fetch();
                
                if ($category) {
                    successResponse($category);
                } else {
                    errorResponse('Category not found', 404);
                }
            } else {
                // Get all categories
                $sql = "SELECT c.*, COUNT(p.id) as product_count 
                        FROM categories c 
                        LEFT JOIN products p ON c.id = p.category_id AND p.is_active = TRUE 
                        WHERE c.is_active = TRUE 
                        GROUP BY c.id 
                        ORDER BY c.sort_order, c.name";
                $stmt = $db->query($sql);
                $categories = $stmt->fetchAll();
                
                successResponse($categories);
            }
            break;
            
        case 'POST':
            // Create category (admin only)
            $auth->requireAdmin();
            
            $data = json_decode(file_get_contents('php://input'), true);
            
            $sql = "INSERT INTO categories (name, slug, description, image_url, parent_id, sort_order) 
                    VALUES (:name, :slug, :description, :image_url, :parent_id, :sort_order)";
            
            $params = [
                'name' => $data['name'],
                'slug' => generateSlug($data['name']),
                'description' => $data['description'] ?? null,
                'image_url' => $data['image_url'] ?? null,
                'parent_id' => $data['parent_id'] ?? null,
                'sort_order' => $data['sort_order'] ?? 0
            ];
            
            $db->query($sql, $params);
            
            successResponse(['id' => $db->lastInsertId(), 'message' => 'Category created successfully']);
            break;
            
        case 'PUT':
            // Update category (admin only)
            $auth->requireAdmin();
            
            $data = json_decode(file_get_contents('php://input'), true);
            $id = $data['id'];
            
            $sql = "UPDATE categories SET 
                    name = :name, 
                    description = :description, 
                    image_url = :image_url, 
                    parent_id = :parent_id, 
                    sort_order = :sort_order,
                    is_active = :is_active
                    WHERE id = :id";
            
            $params = [
                'id' => $id,
                'name' => $data['name'],
                'description' => $data['description'] ?? null,
                'image_url' => $data['image_url'] ?? null,
                'parent_id' => $data['parent_id'] ?? null,
                'sort_order' => $data['sort_order'] ?? 0,
                'is_active' => $data['is_active'] ?? true
            ];
            
            $db->query($sql, $params);
            
            successResponse(['message' => 'Category updated successfully']);
            break;
            
        case 'DELETE':
            // Delete category (admin only)
            $auth->requireAdmin();
            
            $id = $_GET['id'] ?? null;
            if (!$id) {
                errorResponse('Category ID is required');
            }
            
            // Check if category has products
            $checkStmt = $db->query(
                "SELECT COUNT(*) as count FROM products WHERE category_id = :id",
                ['id' => $id]
            );
            $count = $checkStmt->fetch()['count'];
            
            if ($count > 0) {
                errorResponse('Cannot delete category with existing products');
            }
            
            $db->query("DELETE FROM categories WHERE id = :id", ['id' => $id]);
            
            successResponse(['message' => 'Category deleted successfully']);
            break;
            
        default:
            errorResponse('Method not allowed', 405);
    }
} catch (Exception $e) {
    error_log("Categories API error: " . $e->getMessage());
    errorResponse('An error occurred', 500);
}
?>