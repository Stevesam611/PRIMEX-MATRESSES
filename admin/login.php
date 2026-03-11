<?php
/**
 * Primex Mattress & Beddings - Admin Login
 */

require_once __DIR__ . '/../backend/includes/auth.php';

// Redirect if already logged in
if ($auth->isAdminLoggedIn()) {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Primex Mattress & Beddings</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: { 500: '#3b82f6', 600: '#2563eb', 700: '#1d4ed8' },
                        secondary: { 500: '#a855f7', 600: '#9333ea', 700: '#7c3aed' },
                    }
                }
            }
        }
    </script>
    <style>
        .login-gradient {
            background: linear-gradient(135deg, #1e3a8a 0%, #7c3aed 100%);
        }
    </style>
</head>
<body class="font-sans bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="w-full max-w-md">
        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
            <div class="login-gradient p-8 text-center">
                <div class="w-16 h-16 bg-white/20 rounded-xl flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-bed text-white text-2xl"></i>
                </div>
                <h1 class="text-2xl font-bold text-white">Primex Admin</h1>
                <p class="text-white/70 mt-2">Sign in to manage your store</p>
            </div>
            
            <div class="p-8">
                <form id="login-form" onsubmit="handleLogin(event)">
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Username or Email</label>
                        <div class="relative">
                            <i class="fas fa-user absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                            <input type="text" id="username" required 
                                class="w-full pl-12 pr-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all"
                                placeholder="Enter your username">
                        </div>
                    </div>
                    
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                        <div class="relative">
                            <i class="fas fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                            <input type="password" id="password" required 
                                class="w-full pl-12 pr-12 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all"
                                placeholder="Enter your password">
                            <button type="button" onclick="togglePassword()" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                <i class="fas fa-eye" id="eye-icon"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between mb-6">
                        <label class="flex items-center">
                            <input type="checkbox" class="w-4 h-4 text-primary-600 rounded focus:ring-primary-500">
                            <span class="ml-2 text-sm text-gray-600">Remember me</span>
                        </label>
                        <button type="button" onclick="openForgotModal()" class="text-sm text-primary-600 hover:text-primary-700">Forgot password?</button>
                    </div>
                    
                    <button type="submit" id="login-btn" 
                        class="w-full login-gradient text-white py-3 rounded-lg font-semibold hover:opacity-90 transition-opacity flex items-center justify-center">
                        <span>Sign In</span>
                        <i class="fas fa-arrow-right ml-2"></i>
                    </button>
                </form>
                
                <div id="error-message" class="hidden mt-4 p-4 bg-red-50 border border-red-200 rounded-lg text-red-600 text-sm">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    <span id="error-text"></span>
                </div>
            </div>
        </div>
        
        <p class="text-center text-gray-500 text-sm mt-6">
            &copy; 2024 Primex Mattress & Beddings. All rights reserved.
        </p>
    </div>

    <!-- Forgot Password Modal -->
    <div id="forgot-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden">
            <div class="login-gradient p-6 text-center">
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-key text-white text-lg"></i>
                </div>
                <h2 class="text-xl font-bold text-white">Reset Password</h2>
                <p class="text-white/70 text-sm mt-1">Enter your admin email to continue</p>
            </div>

            <div class="p-6">
                <!-- Step 1: Email -->
                <div id="step-email">
                    <div class="mb-5">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Admin Email</label>
                        <div class="relative">
                            <i class="fas fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                            <input type="email" id="reset-email" placeholder="Enter your admin email"
                                class="w-full pl-12 pr-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 text-sm">
                        </div>
                    </div>
                    <div id="step1-error" class="hidden mb-4 p-3 bg-red-50 border border-red-200 rounded-lg text-red-600 text-sm">
                        <i class="fas fa-exclamation-circle mr-2"></i><span id="step1-error-text"></span>
                    </div>
                    <div class="flex space-x-3">
                        <button type="button" onclick="closeForgotModal()" class="flex-1 py-3 border border-gray-200 rounded-lg text-gray-600 hover:bg-gray-50 text-sm font-medium transition-colors">Cancel</button>
                        <button type="button" onclick="verifyEmail()" id="verify-btn" class="flex-1 py-3 login-gradient text-white rounded-lg text-sm font-medium hover:opacity-90 transition-opacity">Continue</button>
                    </div>
                </div>

                <!-- Step 2: New Password -->
                <div id="step-password" class="hidden">
                    <div class="flex items-center space-x-2 mb-5 p-3 bg-green-50 border border-green-200 rounded-lg">
                        <i class="fas fa-check-circle text-green-600"></i>
                        <span class="text-sm text-green-700">Email verified. Set your new password.</span>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
                        <div class="relative">
                            <i class="fas fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                            <input type="password" id="new-password" placeholder="Min. 6 characters"
                                class="w-full pl-12 pr-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 text-sm">
                        </div>
                    </div>
                    <div class="mb-5">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Confirm Password</label>
                        <div class="relative">
                            <i class="fas fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                            <input type="password" id="confirm-password" placeholder="Repeat new password"
                                class="w-full pl-12 pr-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 text-sm">
                        </div>
                    </div>
                    <div id="step2-error" class="hidden mb-4 p-3 bg-red-50 border border-red-200 rounded-lg text-red-600 text-sm">
                        <i class="fas fa-exclamation-circle mr-2"></i><span id="step2-error-text"></span>
                    </div>
                    <div class="flex space-x-3">
                        <button type="button" onclick="backToEmail()" class="flex-1 py-3 border border-gray-200 rounded-lg text-gray-600 hover:bg-gray-50 text-sm font-medium transition-colors">Back</button>
                        <button type="button" onclick="resetPassword()" id="reset-btn" class="flex-1 py-3 login-gradient text-white rounded-lg text-sm font-medium hover:opacity-90 transition-opacity">Reset Password</button>
                    </div>
                </div>

                <!-- Step 3: Success -->
                <div id="step-success" class="hidden text-center py-4">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-check text-green-600 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Password Reset!</h3>
                    <p class="text-gray-500 text-sm mb-6">Your password has been updated. You can now sign in.</p>
                    <button type="button" onclick="closeForgotModal()" class="w-full py-3 login-gradient text-white rounded-lg text-sm font-medium hover:opacity-90 transition-opacity">Back to Login</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const password = document.getElementById('password');
            const eyeIcon = document.getElementById('eye-icon');
            
            if (password.type === 'password') {
                password.type = 'text';
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            } else {
                password.type = 'password';
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            }
        }
        
        function openForgotModal() {
            document.getElementById('forgot-modal').classList.remove('hidden');
            document.getElementById('reset-email').focus();
        }

        function closeForgotModal() {
            document.getElementById('forgot-modal').classList.add('hidden');
            // Reset all steps
            document.getElementById('step-email').classList.remove('hidden');
            document.getElementById('step-password').classList.add('hidden');
            document.getElementById('step-success').classList.add('hidden');
            document.getElementById('step1-error').classList.add('hidden');
            document.getElementById('step2-error').classList.add('hidden');
            document.getElementById('reset-email').value = '';
            document.getElementById('new-password').value = '';
            document.getElementById('confirm-password').value = '';
        }

        function backToEmail() {
            document.getElementById('step-password').classList.add('hidden');
            document.getElementById('step-email').classList.remove('hidden');
            document.getElementById('step2-error').classList.add('hidden');
        }

        async function verifyEmail() {
            const email   = document.getElementById('reset-email').value.trim();
            const errDiv  = document.getElementById('step1-error');
            const btn     = document.getElementById('verify-btn');
            errDiv.classList.add('hidden');

            if (!email) {
                document.getElementById('step1-error-text').textContent = 'Please enter your email address.';
                errDiv.classList.remove('hidden');
                return;
            }

            btn.disabled = true;
            btn.textContent = 'Verifying...';
            try {
                const res    = await fetch('../backend/api/admin-auth.php', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({action: 'forgot_password_verify', email})
                });
                const result = await res.json();
                if (result.success) {
                    document.getElementById('step-email').classList.add('hidden');
                    document.getElementById('step-password').classList.remove('hidden');
                    document.getElementById('new-password').focus();
                } else {
                    document.getElementById('step1-error-text').textContent = result.error || 'Email not found.';
                    errDiv.classList.remove('hidden');
                }
            } catch(e) {
                document.getElementById('step1-error-text').textContent = 'An error occurred. Please try again.';
                errDiv.classList.remove('hidden');
            } finally {
                btn.disabled = false;
                btn.textContent = 'Continue';
            }
        }

        async function resetPassword() {
            const newPwd  = document.getElementById('new-password').value;
            const confirm = document.getElementById('confirm-password').value;
            const errDiv  = document.getElementById('step2-error');
            const btn     = document.getElementById('reset-btn');
            errDiv.classList.add('hidden');

            if (!newPwd || !confirm) {
                document.getElementById('step2-error-text').textContent = 'Please fill in both password fields.';
                errDiv.classList.remove('hidden');
                return;
            }

            btn.disabled = true;
            btn.textContent = 'Resetting...';
            try {
                const res    = await fetch('../backend/api/admin-auth.php', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({action: 'reset_password', new_password: newPwd, confirm_password: confirm})
                });
                const result = await res.json();
                if (result.success) {
                    document.getElementById('step-password').classList.add('hidden');
                    document.getElementById('step-success').classList.remove('hidden');
                } else {
                    document.getElementById('step2-error-text').textContent = result.error || 'Failed to reset password.';
                    errDiv.classList.remove('hidden');
                }
            } catch(e) {
                document.getElementById('step2-error-text').textContent = 'An error occurred. Please try again.';
                errDiv.classList.remove('hidden');
            } finally {
                btn.disabled = false;
                btn.textContent = 'Reset Password';
            }
        }

        // Close modal on backdrop click
        document.addEventListener('DOMContentLoaded', () => {
            document.getElementById('forgot-modal').addEventListener('click', function(e) {
                if (e.target === this) closeForgotModal();
            });
            // Allow Enter key on email field
            document.getElementById('reset-email').addEventListener('keypress', e => {
                if (e.key === 'Enter') verifyEmail();
            });
        });

        async function handleLogin(event) {
            event.preventDefault();
            
            const username = document.getElementById('username').value;
            const password = document.getElementById('password').value;
            const loginBtn = document.getElementById('login-btn');
            const errorDiv = document.getElementById('error-message');
            
            loginBtn.disabled = true;
            loginBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Signing in...';
            errorDiv.classList.add('hidden');
            
            try {
                const response = await fetch('../backend/api/admin-auth.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        action: 'login',
                        username: username,
                        password: password
                    })
                });
                
                const result = await response.json();
                
                if (result.success) {
                    window.location.href = 'index.php';
                } else {
                    errorDiv.classList.remove('hidden');
                    document.getElementById('error-text').textContent = result.error || 'Invalid credentials';
                }
            } catch (error) {
                errorDiv.classList.remove('hidden');
                document.getElementById('error-text').textContent = 'An error occurred. Please try again.';
            } finally {
                loginBtn.disabled = false;
                loginBtn.innerHTML = '<span>Sign In</span><i class="fas fa-arrow-right ml-2"></i>';
            }
        }
    </script>
</body>
</html>