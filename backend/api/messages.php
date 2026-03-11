<?php
/**
 * Primex Mattress & Beddings - Contact Messages API
 * POST (public) : submit a message from the contact form
 * GET / PUT / DELETE (admin only)
 */

require_once __DIR__ . '/../includes/auth.php';

$db     = Database::getInstance();
$method = $_SERVER['REQUEST_METHOD'];

// Create table if it doesn't exist (runs once)
$db->query("
    CREATE TABLE IF NOT EXISTS contact_messages (
        id         SERIAL PRIMARY KEY,
        first_name VARCHAR(100) NOT NULL,
        last_name  VARCHAR(100) NOT NULL,
        email      VARCHAR(255) NOT NULL,
        phone      VARCHAR(50),
        subject    VARCHAR(150) NOT NULL,
        message    TEXT         NOT NULL,
        status     VARCHAR(20)  NOT NULL DEFAULT 'unread',
        created_at TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP
    )
");

try {
    switch ($method) {

        // ── Public: submit a message ─────────────────────────────────────
        case 'POST':
            $data = json_decode(file_get_contents('php://input'), true);

            $firstName = trim($data['first_name'] ?? '');
            $lastName  = trim($data['last_name']  ?? '');
            $email     = trim($data['email']      ?? '');
            $phone     = trim($data['phone']      ?? '');
            $subject   = trim($data['subject']    ?? '');
            $message   = trim($data['message']    ?? '');

            if (!$firstName)                                  errorResponse('First name is required');
            if (!$lastName)                                   errorResponse('Last name is required');
            if (!filter_var($email, FILTER_VALIDATE_EMAIL))   errorResponse('Valid email is required');
            if (!$subject)                                    errorResponse('Subject is required');
            if (!$message)                                    errorResponse('Message is required');

            $db->query(
                "INSERT INTO contact_messages (first_name, last_name, email, phone, subject, message)
                 VALUES (:first_name, :last_name, :email, :phone, :subject, :message)",
                [
                    'first_name' => $firstName,
                    'last_name'  => $lastName,
                    'email'      => $email,
                    'phone'      => $phone ?: null,
                    'subject'    => $subject,
                    'message'    => $message,
                ]
            );

            successResponse(['message' => 'Message sent successfully']);
            break;

        // ── Admin: list messages ─────────────────────────────────────────
        case 'GET':
            $auth->requireAdmin();

            $status = $_GET['status'] ?? 'all';
            $search = trim($_GET['search'] ?? '');
            $page   = max(1, (int)($_GET['page'] ?? 1));
            $limit  = 20;
            $offset = ($page - 1) * $limit;

            $where  = [];
            $params = [];

            if ($status === 'unread') {
                $where[] = "status = 'unread'";
            } elseif ($status === 'read') {
                $where[] = "status = 'read'";
            }

            if ($search) {
                $where[]           = "(first_name ILIKE :s OR last_name ILIKE :s OR email ILIKE :s OR subject ILIKE :s OR message ILIKE :s)";
                $params['s']       = '%' . $search . '%';
            }

            $whereClause = $where ? 'WHERE ' . implode(' AND ', $where) : '';

            $totalStmt = $db->query(
                "SELECT COUNT(*) as total FROM contact_messages $whereClause",
                $params
            );
            $total = (int)$totalStmt->fetch()['total'];

            $messagesStmt = $db->query(
                "SELECT * FROM contact_messages $whereClause ORDER BY created_at DESC LIMIT :limit OFFSET :offset",
                array_merge($params, ['limit' => $limit, 'offset' => $offset])
            );
            $messages = $messagesStmt->fetchAll();

            // Summary counts
            $summaryStmt = $db->query(
                "SELECT
                    COUNT(*) as total,
                    SUM(CASE WHEN status = 'unread' THEN 1 ELSE 0 END) as unread,
                    SUM(CASE WHEN status = 'read'   THEN 1 ELSE 0 END) as read
                 FROM contact_messages"
            );
            $summary = $summaryStmt->fetch();

            successResponse([
                'messages' => $messages,
                'summary'  => $summary,
                'total'    => $total,
                'page'     => $page,
                'pages'    => ceil($total / $limit),
            ]);
            break;

        // ── Admin: mark read / unread ────────────────────────────────────
        case 'PUT':
            $auth->requireAdmin();

            $data   = json_decode(file_get_contents('php://input'), true);
            $id     = (int)($data['id']     ?? 0);
            $action = trim($data['action']  ?? '');

            if (!$id)     errorResponse('Message ID is required');
            if (!$action) errorResponse('Action is required');

            $newStatus = $action === 'read' ? 'read' : 'unread';

            $db->query(
                "UPDATE contact_messages SET status = :status WHERE id = :id",
                ['status' => $newStatus, 'id' => $id]
            );

            successResponse(['message' => 'Message updated']);
            break;

        // ── Admin: delete ────────────────────────────────────────────────
        case 'DELETE':
            $auth->requireAdmin();

            $id = (int)($_GET['id'] ?? 0);
            if (!$id) errorResponse('Message ID is required');

            $db->query("DELETE FROM contact_messages WHERE id = :id", ['id' => $id]);
            successResponse(['message' => 'Message deleted']);
            break;

        default:
            errorResponse('Method not allowed', 405);
    }

} catch (PDOException $e) {
    error_log("Messages API error: " . $e->getMessage());
    errorResponse('A database error occurred: ' . $e->getMessage(), 500);
} catch (Exception $e) {
    error_log("Messages API error: " . $e->getMessage());
    errorResponse('An error occurred', 500);
}
?>
