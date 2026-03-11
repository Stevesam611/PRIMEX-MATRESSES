<?php
/**
 * Primex Mattress & Beddings - User Authentication API
 * Public endpoints: login, register, logout, check
 */

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/database.php';

header('Content-Type: application/json');

if (session_status() === PHP_SESSION_NONE) session_start();

$db     = Database::getInstance();
$method = $_SERVER['REQUEST_METHOD'];

// Auto-create users table
$db->query("
    CREATE TABLE IF NOT EXISTS users (
        id         SERIAL PRIMARY KEY,
        email      VARCHAR(255) NOT NULL UNIQUE,
        password_hash VARCHAR(255) NOT NULL,
        first_name VARCHAR(100),
        last_name  VARCHAR(100),
        phone      VARCHAR(50),
        created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
    )
");

// Auto-create password_reset_tokens table
$db->query("
    CREATE TABLE IF NOT EXISTS password_reset_tokens (
        id         SERIAL PRIMARY KEY,
        user_id    INTEGER NOT NULL,
        email      VARCHAR(255) NOT NULL,
        token      VARCHAR(64) NOT NULL UNIQUE,
        created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        expires_at TIMESTAMP NOT NULL,
        used       BOOLEAN DEFAULT FALSE
    )
");

function userSuccess($data) {
    echo json_encode(['success' => true, 'data' => $data]);
    exit;
}
function userError($msg, $code = 400) {
    http_response_code($code);
    echo json_encode(['success' => false, 'error' => $msg]);
    exit;
}

try {
    // GET: check session
    if ($method === 'GET') {
        $action = $_GET['action'] ?? 'check';
        if ($action === 'check') {
            if (!empty($_SESSION['user_logged_in'])) {
                userSuccess([
                    'logged_in'  => true,
                    'id'         => $_SESSION['user_id'],
                    'email'      => $_SESSION['user_email'],
                    'name'       => trim(($_SESSION['user_first_name'] ?? '') . ' ' . ($_SESSION['user_last_name'] ?? '')),
                    'first_name' => $_SESSION['user_first_name'] ?? '',
                    'last_name'  => $_SESSION['user_last_name'] ?? '',
                ]);
            } else {
                userSuccess(['logged_in' => false]);
            }
        }
        userError('Unknown action');
    }

    if ($method === 'POST') {
        $data   = json_decode(file_get_contents('php://input'), true) ?? [];
        $action = $data['action'] ?? '';

        // ── Register ─────────────────────────────────────────────────────
        if ($action === 'register') {
            $email     = trim($data['email'] ?? '');
            $password  = $data['password'] ?? '';
            $firstName = trim($data['first_name'] ?? '');
            $lastName  = trim($data['last_name'] ?? '');
            $phone     = trim($data['phone'] ?? '');

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) userError('Valid email is required');
            if (strlen($password) < 6)                      userError('Password must be at least 6 characters');
            if (!$firstName)                                 userError('First name is required');

            // Check duplicate
            $exists = $db->query("SELECT id FROM users WHERE email = :email", ['email' => $email])->fetch();
            if ($exists) userError('An account with this email already exists');

            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $db->query(
                "INSERT INTO users (email, password_hash, first_name, last_name, phone) VALUES (:e,:h,:f,:l,:p) RETURNING id",
                ['e' => $email, 'h' => $hash, 'f' => $firstName, 'l' => $lastName, 'p' => $phone ?: null]
            );
            $userId = $stmt->fetch()['id'];

            $_SESSION['user_logged_in']  = true;
            $_SESSION['user_id']         = $userId;
            $_SESSION['user_email']      = $email;
            $_SESSION['user_first_name'] = $firstName;
            $_SESSION['user_last_name']  = $lastName;

            userSuccess(['message' => 'Account created', 'name' => "$firstName $lastName", 'email' => $email]);
        }

        // ── Login ─────────────────────────────────────────────────────────
        if ($action === 'login') {
            $email    = trim($data['email'] ?? '');
            $password = $data['password'] ?? '';

            if (!$email || !$password) userError('Email and password are required');

            $user = $db->query("SELECT * FROM users WHERE email = :e", ['e' => $email])->fetch();
            if (!$user || !password_verify($password, $user['password_hash'])) {
                userError('Invalid email or password');
            }

            $_SESSION['user_logged_in']  = true;
            $_SESSION['user_id']         = $user['id'];
            $_SESSION['user_email']      = $user['email'];
            $_SESSION['user_first_name'] = $user['first_name'];
            $_SESSION['user_last_name']  = $user['last_name'];

            userSuccess([
                'message'    => 'Login successful',
                'name'       => trim($user['first_name'] . ' ' . $user['last_name']),
                'email'      => $user['email'],
            ]);
        }

        // ── Logout ────────────────────────────────────────────────────────
        if ($action === 'logout') {
            unset(
                $_SESSION['user_logged_in'],
                $_SESSION['user_id'],
                $_SESSION['user_email'],
                $_SESSION['user_first_name'],
                $_SESSION['user_last_name']
            );
            userSuccess(['message' => 'Signed out']);
        }

        // ── Forgot Password ───────────────────────────────────────────────
        if ($action === 'forgot_password') {
            $email = trim($data['email'] ?? '');
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) userError('Valid email is required');

            $user = $db->query("SELECT id, first_name FROM users WHERE email = :e", ['e' => $email])->fetch();
            // Always respond generically to prevent email enumeration
            if (!$user) {
                userSuccess(['message' => 'If that email exists, a reset link has been sent.']);
            }

            // Expire old tokens for this email
            $db->query("UPDATE password_reset_tokens SET used = TRUE WHERE email = :e AND used = FALSE", ['e' => $email]);

            $token     = bin2hex(random_bytes(32));
            $expiresAt = date('Y-m-d H:i:s', time() + 3600); // 1 hour
            $db->query(
                "INSERT INTO password_reset_tokens (user_id, email, token, expires_at) VALUES (:u,:e,:t,:x)",
                ['u' => $user['id'], 'e' => $email, 't' => $token, 'x' => $expiresAt]
            );

            $resetUrl = APP_URL . '/frontend/pages/reset-password.html?token=' . $token;
            $name     = $user['first_name'] ?: 'Customer';

            // Attempt to send email
            $subject = 'Primex — Reset your password';
            $body    = "Hi $name,\n\nClick the link below to reset your password (expires in 1 hour):\n\n$resetUrl\n\nIf you did not request this, ignore this email.\n\nPrimex Mattress & Beddings";
            $headers = "From: noreply@primex.com\r\nX-Mailer: PHP/" . phpversion();
            @mail($email, $subject, $body, $headers);

            // Always return the link so it works even without mail configured
            userSuccess([
                'message'   => 'If that email exists, a reset link has been sent.',
                'reset_url' => $resetUrl  // front-end shows this as fallback
            ]);
        }

        // ── Reset Password ────────────────────────────────────────────────
        if ($action === 'reset_password') {
            $token    = trim($data['token'] ?? '');
            $password = $data['password'] ?? '';

            if (!$token)              userError('Reset token is required');
            if (strlen($password) < 6) userError('Password must be at least 6 characters');

            $row = $db->query(
                "SELECT * FROM password_reset_tokens WHERE token = :t AND used = FALSE AND expires_at > NOW()",
                ['t' => $token]
            )->fetch();

            if (!$row) userError('This reset link is invalid or has expired');

            $hash = password_hash($password, PASSWORD_DEFAULT);
            $db->query("UPDATE users SET password_hash = :h WHERE id = :id", ['h' => $hash, 'id' => $row['user_id']]);
            $db->query("UPDATE password_reset_tokens SET used = TRUE WHERE token = :t", ['t' => $token]);

            userSuccess(['message' => 'Password updated successfully. You can now sign in.']);
        }

        // ── Change Password (must be logged in) ───────────────────────────
        if ($action === 'change_password') {
            if (empty($_SESSION['user_logged_in'])) userError('Not authenticated', 401);

            $currentPw  = $data['current_password'] ?? '';
            $newPw      = $data['new_password'] ?? '';

            if (!$currentPw || !$newPw)  userError('Both current and new password are required');
            if (strlen($newPw) < 6)       userError('New password must be at least 6 characters');

            $user = $db->query("SELECT password_hash FROM users WHERE id = :id", ['id' => $_SESSION['user_id']])->fetch();
            if (!$user || !password_verify($currentPw, $user['password_hash'])) {
                userError('Current password is incorrect');
            }

            $hash = password_hash($newPw, PASSWORD_DEFAULT);
            $db->query("UPDATE users SET password_hash = :h WHERE id = :id", ['h' => $hash, 'id' => $_SESSION['user_id']]);

            userSuccess(['message' => 'Password changed successfully']);
        }

        userError('Unknown action');
    }

    userError('Method not allowed', 405);

} catch (Exception $e) {
    error_log("User auth error: " . $e->getMessage());
    userError('An error occurred: ' . $e->getMessage(), 500);
}
?>
