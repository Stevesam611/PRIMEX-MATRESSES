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