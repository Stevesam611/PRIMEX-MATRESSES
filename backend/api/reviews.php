<?php
/**
 * Primex Mattress & Beddings - Reviews API
 */

require_once __DIR__ . '/../includes/auth.php';

$db = Database::getInstance();
$auth->requireAdmin();

$method = $_SERVER['REQUEST_METHOD'];

try {
    switch ($method) {
        case 'POST':
            // Public: submit a review (no auth required)
            $data = json_decode(file_get_contents('php://input'), true);

            $productId    = (int)($data['product_id'] ?? 0);
            $name         = trim($data['customer_name'] ?? '');
            $email        = trim($data['customer_email'] ?? '');
            $rating       = (int)($data['rating'] ?? 0);
            $title        = trim($data['title'] ?? '');
            $reviewText   = trim($data['review'] ?? '');

            if (!$productId)                         errorResponse('Product ID is required');
            if (!$name)                              errorResponse('Name is required');
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) errorResponse('Valid email is required');
            if ($rating < 1 || $rating > 5)          errorResponse('Rating must be between 1 and 5');
            if (!$reviewText)                        errorResponse('Review text is required');

            // Check product exists
            $check = $db->query("SELECT id FROM products WHERE id = :id AND is_active = TRUE", ['id' => $productId])->fetch();
            if (!$check) errorResponse('Product not found', 404);

            $db->query(
                "INSERT INTO product_reviews (product_id, customer_name, customer_email, rating, title, review, is_approved)
                 VALUES (:product_id, :customer_name, :customer_email, :rating, :title, :review, FALSE)",
                [
                    'product_id'    => $productId,
                    'customer_name' => sanitize($name),
                    'customer_email'=> sanitize($email),
                    'rating'        => $rating,
                    'title'         => sanitize($title),
                    'review'        => sanitize($reviewText),
                ]
            );

            successResponse(null, 'Review submitted successfully and is pending approval');
            break;

        case 'GET':
            $status = $_GET['status'] ?? 'all'; // all | pending | approved
            $search = $_GET['search'] ?? '';
            $page   = max(1, (int)($_GET['page'] ?? 1));
            $limit  = 20;
            $offset = ($page - 1) * $limit;

            $where = ['1=1'];
            $params = [];

            if ($status === 'pending') {
                $where[] = 'r.is_approved = FALSE';
            } elseif ($status === 'approved') {
                $where[] = 'r.is_approved = TRUE';
            }

            if ($search !== '') {
                $where[] = "(r.customer_name ILIKE :search OR r.customer_email ILIKE :search OR r.title ILIKE :search OR p.name ILIKE :search)";
                $params['search'] = '%' . $search . '%';
            }

            $whereClause = implode(' AND ', $where);

            // Total count
            $countStmt = $db->query(
                "SELECT COUNT(*) as total
                 FROM product_reviews r
                 LEFT JOIN products p ON r.product_id = p.id
                 WHERE $whereClause",
                $params
            );
            $total = $countStmt->fetch()['total'];

            // Reviews
            $stmt = $db->query(
                "SELECT r.id, r.product_id, r.customer_name, r.customer_email,
                        r.rating, r.title, r.review, r.is_approved, r.created_at,
                        p.name as product_name
                 FROM product_reviews r
                 LEFT JOIN products p ON r.product_id = p.id
                 WHERE $whereClause
                 ORDER BY r.created_at DESC
                 LIMIT :limit OFFSET :offset",
                array_merge($params, ['limit' => $limit, 'offset' => $offset])
            );
            $reviews = $stmt->fetchAll();

            // Summary counts
            $summaryStmt = $db->query(
                "SELECT
                    COUNT(*) as total,
                    SUM(CASE WHEN is_approved = FALSE THEN 1 ELSE 0 END) as pending,
                    SUM(CASE WHEN is_approved = TRUE  THEN 1 ELSE 0 END) as approved
                 FROM product_reviews"
            );
            $summary = $summaryStmt->fetch();

            successResponse([
                'reviews'  => $reviews,
                'summary'  => $summary,
                'total'    => (int)$total,
                'page'     => $page,
                'pages'    => (int)ceil($total / $limit),
            ]);
            break;

        case 'PUT':
            $data = json_decode(file_get_contents('php://input'), true);
            $id = (int)($data['id'] ?? 0);
            if (!$id) errorResponse('Review ID required');

            $action = $data['action'] ?? '';

            if ($action === 'approve') {
                $db->query("UPDATE product_reviews SET is_approved = TRUE  WHERE id = :id", ['id' => $id]);
            } elseif ($action === 'unapprove') {
                $db->query("UPDATE product_reviews SET is_approved = FALSE WHERE id = :id", ['id' => $id]);
            } else {
                errorResponse('Invalid action');
            }

            successResponse(null, 'Review updated');
            break;

        case 'DELETE':
            $id = (int)($_GET['id'] ?? 0);
            if (!$id) errorResponse('Review ID required');

            $db->query("DELETE FROM product_reviews WHERE id = :id", ['id' => $id]);
            successResponse(null, 'Review deleted');
            break;

        default:
            errorResponse('Method not allowed', 405);
    }
} catch (Exception $e) {
    error_log("Reviews API error: " . $e->getMessage());
    errorResponse('An error occurred', 500);
}
?>
