<?php
/**
 * Primex Mattress & Beddings - Admin Authentication API
 */

require_once __DIR__ . '/../includes/auth.php';

session_start();
$method = $_SERVER['REQUEST_METHOD'];

try {
    switch ($method) {
        case 'POST':
            $data = json_decode(file_get_contents('php://input'), true);
            $action = $data['action'] ?? '';
            
            switch ($action) {
                case 'login':
                    $result = $auth->adminLogin($data['username'], $data['password']);
                    if ($result['success']) {
                        successResponse($result['admin'], 'Login successful');
                    } else {
                        errorResponse($result['message'], 401);
                    }
                    break;
                    
                case 'logout':
                    $result = $auth->logout();
                    successResponse(null, 'Logged out successfully');
                    break;
                    
                case 'check':
                    if ($auth->isAdminLoggedIn()) {
                        successResponse([
                            'logged_in' => true,
                            'admin' => [
                                'id' => $_SESSION['admin_id'],
                                'username' => $_SESSION['admin_username'],
                                'email' => $_SESSION['admin_email'],
                                'full_name' => $_SESSION['admin_name'],
                                'role' => $_SESSION['admin_role']
                            ]
                        ]);
                    } else {
                        errorResponse('Not logged in', 401);
                    }
                    break;
                    
                case 'change_password':
                    $auth->requireAdmin();
                    $result = $auth->changePassword(
                        $_SESSION['admin_id'],
                        $data['current_password'],
                        $data['new_password']
                    );
                    if ($result['success']) {
                        successResponse(null, $result['message']);
                    } else {
                        errorResponse($result['message']);
                    }
                    break;

                case 'forgot_password_verify':
                    $email = trim($data['email'] ?? '');
                    if (!$email) { errorResponse('Email is required'); }

                    $db = Database::getInstance();
                    $stmt = $db->query(
                        "SELECT id FROM admins WHERE email = :email AND is_active = TRUE",
                        ['email' => $email]
                    );
                    $admin = $stmt->fetch();

                    if (!$admin) { errorResponse('No admin account found with that email'); }

                    // Store verified email + expiry in session (5 min window)
                    $_SESSION['pwd_reset_email']   = $email;
                    $_SESSION['pwd_reset_expires'] = time() + 300;

                    successResponse(null, 'Email verified');
                    break;

                case 'reset_password':
                    $newPassword = $data['new_password'] ?? '';
                    $confirm     = $data['confirm_password'] ?? '';

                    if (empty($_SESSION['pwd_reset_email']) || time() > ($_SESSION['pwd_reset_expires'] ?? 0)) {
                        errorResponse('Reset session expired. Please start over.');
                    }
                    if (strlen($newPassword) < 6) { errorResponse('Password must be at least 6 characters'); }
                    if ($newPassword !== $confirm)  { errorResponse('Passwords do not match'); }

                    $db    = Database::getInstance();
                    $hash  = password_hash($newPassword, PASSWORD_DEFAULT);
                    $db->query(
                        "UPDATE admins SET password_hash = :hash WHERE email = :email",
                        ['hash' => $hash, 'email' => $_SESSION['pwd_reset_email']]
                    );

                    unset($_SESSION['pwd_reset_email'], $_SESSION['pwd_reset_expires']);
                    successResponse(null, 'Password reset successfully');
                    break;

                default:
                    errorResponse('Invalid action');
            }
            break;
            
        default:
            errorResponse('Method not allowed', 405);
    }
} catch (Exception $e) {
    error_log("Admin auth API error: " . $e->getMessage());
    errorResponse('An error occurred', 500);
}
?>