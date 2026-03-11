<?php
/**
 * Primex Mattress & Beddings - Authentication Handler
 */

require_once __DIR__ . '/database.php';

class Auth {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    // Admin login
    public function adminLogin($username, $password) {
        try {
            $sql = "SELECT id, username, email, password_hash, full_name, role, is_active 
                    FROM admins 
                    WHERE username = :username OR email = :username";
            $stmt = $this->db->query($sql, ['username' => $username]);
            $admin = $stmt->fetch();
            
            if (!$admin) {
                return ['success' => false, 'message' => 'Invalid credentials'];
            }
            
            if (!$admin['is_active']) {
                return ['success' => false, 'message' => 'Account is deactivated'];
            }
            
            if (!password_verify($password, $admin['password_hash'])) {
                return ['success' => false, 'message' => 'Invalid credentials'];
            }
            
            // Update last login
            $this->db->query(
                "UPDATE admins SET last_login = NOW() WHERE id = :id",
                ['id' => $admin['id']]
            );
            
            // Create session
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_username'] = $admin['username'];
            $_SESSION['admin_email'] = $admin['email'];
            $_SESSION['admin_name'] = $admin['full_name'];
            $_SESSION['admin_role'] = $admin['role'];
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_login_time'] = time();
            
            return [
                'success' => true,
                'message' => 'Login successful',
                'admin' => [
                    'id' => $admin['id'],
                    'username' => $admin['username'],
                    'email' => $admin['email'],
                    'full_name' => $admin['full_name'],
                    'role' => $admin['role']
                ]
            ];
        } catch (Exception $e) {
            error_log("Admin login error: " . $e->getMessage());
            return ['success' => false, 'message' => 'Login failed'];
        }
    }
    
    // Check if admin is logged in
    public function isAdminLoggedIn() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
            return false;
        }
        
        // Check session expiration
        if (time() - $_SESSION['admin_login_time'] > SESSION_LIFETIME) {
            $this->logout();
            return false;
        }
        
        return true;
    }
    
    // Require admin login
    public function requireAdmin() {
        if (!$this->isAdminLoggedIn()) {
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
                jsonResponse(['success' => false, 'message' => 'Unauthorized'], 401);
            } else {
                header('Location: ' . ADMIN_URL . '/login.php');
                exit;
            }
        }
    }
    
    // Logout
    public function logout() {
        session_unset();
        session_destroy();
        return ['success' => true, 'message' => 'Logged out successfully'];
    }
    
    // Change password
    public function changePassword($adminId, $currentPassword, $newPassword) {
        try {
            $stmt = $this->db->query(
                "SELECT password_hash FROM admins WHERE id = :id",
                ['id' => $adminId]
            );
            $admin = $stmt->fetch();
            
            if (!password_verify($currentPassword, $admin['password_hash'])) {
                return ['success' => false, 'message' => 'Current password is incorrect'];
            }
            
            $newHash = password_hash($newPassword, PASSWORD_DEFAULT);
            $this->db->query(
                "UPDATE admins SET password_hash = :hash WHERE id = :id",
                ['hash' => $newHash, 'id' => $adminId]
            );
            
            return ['success' => true, 'message' => 'Password changed successfully'];
        } catch (Exception $e) {
            error_log("Password change error: " . $e->getMessage());
            return ['success' => false, 'message' => 'Failed to change password'];
        }
    }
    
    // Create new admin (superadmin only)
    public function createAdmin($data) {
        try {
            $sql = "INSERT INTO admins (username, email, password_hash, full_name, role) 
                    VALUES (:username, :email, :password_hash, :full_name, :role)";
            
            $params = [
                'username' => $data['username'],
                'email' => $data['email'],
                'password_hash' => password_hash($data['password'], PASSWORD_DEFAULT),
                'full_name' => $data['full_name'],
                'role' => $data['role'] ?? 'admin'
            ];
            
            $this->db->query($sql, $params);
            
            return ['success' => true, 'message' => 'Admin created successfully'];
        } catch (Exception $e) {
            error_log("Create admin error: " . $e->getMessage());
            return ['success' => false, 'message' => 'Failed to create admin'];
        }
    }
}

// Initialize auth
$auth = new Auth();
?>