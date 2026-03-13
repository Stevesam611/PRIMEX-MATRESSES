<?php
/**
 * Primex Mattress & Beddings - Admin Accounts API
 * Admin-only: list, create, activate/deactivate admin accounts
 */

require_once __DIR__ . '/../includes/auth.php';

$auth->requireRole(['admin', 'superadmin']);

$db     = Database::getInstance();
$method = $_SERVER['REQUEST_METHOD'];

try {
    switch ($method) {

        // ── List all admin accounts ───────────────────────────────────
        case 'GET':
            $accounts = $db->query(
                "SELECT id, username, email, full_name, role, is_active, last_login, created_at
                 FROM admins
                 ORDER BY role ASC, full_name ASC"
            )->fetchAll();

            successResponse(['accounts' => $accounts]);
            break;

        // ── Create or toggle ──────────────────────────────────────────
        case 'POST':
            $data   = json_decode(file_get_contents('php://input'), true);
            $action = trim($data['action'] ?? '');

            if ($action === 'create') {
                $fullName = trim($data['full_name'] ?? '');
                $username = trim($data['username']  ?? '');
                $email    = trim($data['email']     ?? '');
                $password = $data['password'] ?? '';
                $role     = in_array($data['role'] ?? '', ['admin','staff']) ? $data['role'] : 'staff';

                if (!$fullName) errorResponse('Full name is required');
                if (!$username) errorResponse('Username is required');
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) errorResponse('Valid email is required');
                if (strlen($password) < 6) errorResponse('Password must be at least 6 characters');

                // Check username/email uniqueness
                $existing = $db->query(
                    "SELECT id FROM admins WHERE username = :u OR email = :e",
                    ['u' => $username, 'e' => $email]
                )->fetch();
                if ($existing) errorResponse('Username or email already in use');

                $db->query(
                    "INSERT INTO admins (username, email, password_hash, full_name, role, is_active)
                     VALUES (:username, :email, :hash, :full_name, :role, TRUE)",
                    [
                        'username'  => $username,
                        'email'     => $email,
                        'hash'      => password_hash($password, PASSWORD_DEFAULT),
                        'full_name' => $fullName,
                        'role'      => $role,
                    ]
                );
                successResponse(['message' => 'Account created successfully']);

            } elseif ($action === 'toggle') {
                $id     = (int)($data['id']     ?? 0);
                $active = isset($data['active']) ? (bool)$data['active'] : true;

                if (!$id) errorResponse('Account ID is required');

                // Prevent deactivating yourself
                if ($id === (int)$_SESSION['admin_id']) errorResponse('You cannot deactivate your own account');

                $db->query(
                    "UPDATE admins SET is_active = :active WHERE id = :id",
                    ['active' => $active ? 'TRUE' : 'FALSE', 'id' => $id]
                );
                successResponse(['message' => $active ? 'Account activated' : 'Account deactivated']);

            } else {
                errorResponse('Invalid action');
            }
            break;

        default:
            errorResponse('Method not allowed', 405);
    }

} catch (PDOException $e) {
    error_log("Admin accounts API error: " . $e->getMessage());
    errorResponse('A database error occurred: ' . $e->getMessage(), 500);
} catch (Exception $e) {
    error_log("Admin accounts API error: " . $e->getMessage());
    errorResponse('An error occurred', 500);
}
?>
