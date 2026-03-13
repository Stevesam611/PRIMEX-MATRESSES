<?php
/**
 * Primex Mattress & Beddings - Admin Accounts Management
 * Only accessible to admins
 */

require_once __DIR__ . '/../backend/includes/auth.php';
$auth->requireRole(['admin', 'superadmin']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accounts - Primex Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        (function(){if(localStorage.getItem('adminDark')==='1')document.documentElement.classList.add('dark');})();
    </script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary:   { 50:'#eff6ff',100:'#dbeafe',500:'#3b82f6',600:'#2563eb',700:'#1d4ed8' },
                        secondary: { 50:'#faf5ff',100:'#f3e8ff',500:'#a855f7',600:'#9333ea',700:'#7c3aed' },
                    }
                }
            }
        }
    </script>
    <style>
        .sidebar-link { transition: all 0.3s ease; }
        .sidebar-link:hover, .sidebar-link.active { background: linear-gradient(90deg, rgba(37,99,235,0.1) 0%, transparent 100%); border-right: 3px solid #2563eb; }
        html.dark body{background-color:#0f172a}
        html.dark .bg-white{background-color:#1e293b!important}
        html.dark .bg-gray-100{background-color:#0f172a!important}
        html.dark .bg-gray-50{background-color:#162032!important}
        html.dark aside{background-color:#1e293b!important}
        html.dark .text-gray-900{color:#f8fafc!important}
        html.dark .text-gray-800{color:#f1f5f9!important}
        html.dark .text-gray-700{color:#e2e8f0!important}
        html.dark .text-gray-600{color:#cbd5e1!important}
        html.dark .text-gray-500{color:#94a3b8!important}
        html.dark .text-gray-400{color:#64748b!important}
        html.dark .border-gray-100,html.dark .border-gray-200{border-color:#334155!important}
        html.dark .border-b,html.dark .border-t{border-color:#334155!important}
        html.dark input:not([type=checkbox]):not([type=radio]),html.dark textarea,html.dark select{background-color:#1e293b!important;border-color:#334155!important;color:#e2e8f0!important}
        html.dark .hover\:bg-gray-50:hover{background-color:#162032!important}
        html.dark .hover\:bg-gray-100:hover{background-color:#334155!important}
        html.dark .shadow-sm{box-shadow:0 1px 3px rgba(0,0,0,.5)!important}
        html.dark .shadow-lg,html.dark .shadow-2xl{box-shadow:0 4px 24px rgba(0,0,0,.6)!important}
    </style>
</head>
<body class="font-sans bg-gray-100">
<div class="flex h-screen">

    <!-- Sidebar -->
    <aside class="w-64 bg-white shadow-lg flex-shrink-0">
        <div class="p-6 border-b border-gray-100">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-gradient-to-br from-primary-600 to-secondary-600 rounded-lg flex items-center justify-center">
                    <i class="fas fa-bed text-white"></i>
                </div>
                <span class="text-xl font-bold bg-gradient-to-r from-primary-700 to-secondary-700 bg-clip-text text-transparent">Primex</span>
            </div>
        </div>
        <nav class="p-4 space-y-1">
            <a href="index.php"      class="sidebar-link flex items-center space-x-3 px-4 py-3 text-gray-600 hover:text-primary-600 rounded-lg"><i class="fas fa-tachometer-alt w-5"></i><span class="font-medium">Dashboard</span></a>
            <a href="products.php"   class="sidebar-link flex items-center space-x-3 px-4 py-3 text-gray-600 hover:text-primary-600 rounded-lg"><i class="fas fa-box w-5"></i><span class="font-medium">Products</span></a>
            <a href="orders.php"     class="sidebar-link flex items-center space-x-3 px-4 py-3 text-gray-600 hover:text-primary-600 rounded-lg"><i class="fas fa-shopping-cart w-5"></i><span class="font-medium">Orders</span></a>
            <a href="categories.php" class="sidebar-link flex items-center space-x-3 px-4 py-3 text-gray-600 hover:text-primary-600 rounded-lg"><i class="fas fa-tags w-5"></i><span class="font-medium">Categories</span></a>
            <a href="customers.php"  class="sidebar-link flex items-center space-x-3 px-4 py-3 text-gray-600 hover:text-primary-600 rounded-lg"><i class="fas fa-users w-5"></i><span class="font-medium">Customers</span></a>
            <a href="messages.php"   class="sidebar-link flex items-center space-x-3 px-4 py-3 text-gray-600 hover:text-primary-600 rounded-lg"><i class="fas fa-envelope w-5"></i><span class="font-medium">Messages</span></a>
            <a href="accounts.php"   class="sidebar-link active flex items-center space-x-3 px-4 py-3 text-primary-600 rounded-lg"><i class="fas fa-user-shield w-5"></i><span class="font-medium">Accounts</span></a>
        </nav>
        <div class="absolute bottom-0 w-64 p-4 border-t border-gray-100">
            <button onclick="logout()" class="flex items-center space-x-3 px-4 py-3 text-red-600 hover:bg-red-50 rounded-lg w-full transition-colors">
                <i class="fas fa-sign-out-alt w-5"></i>
                <span class="font-medium">Logout</span>
            </button>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 overflow-y-auto">
        <header class="bg-white shadow-sm sticky top-0 z-10">
            <div class="flex items-center justify-between px-8 py-4">
                <h1 class="text-2xl font-bold text-gray-900">Accounts</h1>
                <div class="flex items-center space-x-3">
                    <button onclick="toggleDarkMode()" id="dark-toggle" title="Toggle dark mode" class="p-2 text-gray-400 hover:text-gray-600 focus:outline-none">
                        <i id="dark-icon" class="fas fa-moon text-xl"></i>
                    </button>
                    <div class="relative" id="notif-wrapper">
                        <button onclick="toggleNotifications()" class="p-2 text-gray-400 hover:text-gray-600 relative focus:outline-none">
                            <i class="fas fa-bell text-xl"></i>
                            <span id="notif-badge" class="absolute -top-0.5 -right-0.5 w-5 h-5 bg-red-500 text-white text-xs rounded-full items-center justify-center hidden">0</span>
                        </button>
                        <div id="notif-dropdown" class="hidden absolute right-0 mt-2 w-80 bg-white rounded-xl shadow-2xl border border-gray-100 z-50 overflow-hidden">
                            <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100">
                                <span class="font-semibold text-gray-900 text-sm">Notifications</span>
                                <span id="notif-total-badge" class="text-xs bg-red-100 text-red-600 px-2 py-0.5 rounded-full font-medium"></span>
                            </div>
                            <div id="notif-list" class="divide-y divide-gray-50 max-h-72 overflow-y-auto">
                                <div class="px-4 py-6 text-center text-gray-400 text-sm">Loading...</div>
                            </div>
                            <div class="px-4 py-3 border-t border-gray-100 bg-gray-50 text-center">
                                <a href="orders.php" class="text-xs text-primary-600 hover:text-primary-700 font-medium">View all orders →</a>
                            </div>
                        </div>
                    </div>
                    <div class="relative" id="profile-wrapper">
                        <button onclick="toggleProfile()" class="flex items-center space-x-3 hover:bg-gray-50 rounded-lg px-2 py-1.5 transition-colors focus:outline-none">
                            <div class="w-9 h-9 bg-gradient-to-br from-primary-500 to-secondary-500 rounded-full flex items-center justify-center">
                                <span class="text-white font-semibold text-sm"><?php echo strtoupper(substr($_SESSION['admin_name'] ?? 'A', 0, 1)); ?></span>
                            </div>
                            <div class="hidden md:block text-left">
                                <div class="font-medium text-gray-900 text-sm leading-tight"><?php echo htmlspecialchars($_SESSION['admin_name'] ?? 'Admin'); ?></div>
                                <div class="text-xs text-gray-500 capitalize"><?php echo htmlspecialchars($_SESSION['admin_role'] ?? 'admin'); ?></div>
                            </div>
                            <i class="fas fa-chevron-down text-gray-400 text-xs hidden md:block"></i>
                        </button>
                        <div id="profile-dropdown" class="hidden absolute right-0 mt-2 w-64 bg-white rounded-xl shadow-2xl border border-gray-100 z-50 overflow-hidden">
                            <div class="px-4 py-4 bg-gradient-to-br from-primary-50 to-secondary-50 border-b border-gray-100">
                                <div class="flex items-center space-x-3">
                                    <div class="w-11 h-11 bg-gradient-to-br from-primary-500 to-secondary-500 rounded-full flex items-center justify-center flex-shrink-0">
                                        <span class="text-white font-bold"><?php echo strtoupper(substr($_SESSION['admin_name'] ?? 'A', 0, 1)); ?></span>
                                    </div>
                                    <div>
                                        <div class="font-semibold text-gray-900 text-sm"><?php echo htmlspecialchars($_SESSION['admin_name'] ?? 'Admin'); ?></div>
                                        <div class="text-xs text-gray-500"><?php echo htmlspecialchars($_SESSION['admin_email'] ?? ''); ?></div>
                                        <span class="inline-block mt-1 text-xs bg-primary-100 text-primary-700 px-2 py-0.5 rounded-full capitalize font-medium"><?php echo htmlspecialchars($_SESSION['admin_role'] ?? 'admin'); ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="py-1">
                                <button onclick="openChangePassword(); closeDropdowns();" class="w-full flex items-center space-x-3 px-4 py-2.5 text-gray-700 hover:bg-gray-50 text-sm transition-colors">
                                    <i class="fas fa-key text-gray-400 w-4"></i><span>Change Password</span>
                                </button>
                                <div class="border-t border-gray-100 my-1"></div>
                                <button onclick="logout()" class="w-full flex items-center space-x-3 px-4 py-2.5 text-red-600 hover:bg-red-50 text-sm transition-colors">
                                    <i class="fas fa-sign-out-alt w-4"></i><span>Logout</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Change Password Modal -->
        <div id="change-pwd-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm">
                <div class="flex items-center justify-between p-6 border-b border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900">Change Password</h3>
                    <button onclick="closeChangePassword()" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times"></i></button>
                </div>
                <div class="p-6 space-y-4">
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Current Password</label>
                        <input type="password" id="cp-current" placeholder="••••••••" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500"></div>
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                        <input type="password" id="cp-new" placeholder="Min. 6 characters" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500"></div>
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Confirm New Password</label>
                        <input type="password" id="cp-confirm" placeholder="Repeat new password" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500"></div>
                    <div id="cp-error" class="hidden p-3 bg-red-50 border border-red-200 rounded-lg text-red-600 text-sm"><i class="fas fa-exclamation-circle mr-1"></i><span id="cp-error-text"></span></div>
                    <div id="cp-success" class="hidden p-3 bg-green-50 border border-green-200 rounded-lg text-green-700 text-sm"><i class="fas fa-check-circle mr-1"></i>Password changed successfully!</div>
                </div>
                <div class="flex space-x-3 px-6 pb-6">
                    <button onclick="closeChangePassword()" class="flex-1 py-2.5 border border-gray-200 rounded-lg text-gray-600 hover:bg-gray-50 text-sm font-medium transition-colors">Cancel</button>
                    <button onclick="submitChangePassword()" id="cp-btn" class="flex-1 py-2.5 bg-primary-600 text-white rounded-lg hover:bg-primary-700 text-sm font-medium transition-colors">Save</button>
                </div>
            </div>
        </div>

        <!-- Create Account Modal -->
        <div id="create-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md">
                <div class="flex items-center justify-between p-6 border-b border-gray-100">
                    <div class="flex items-center space-x-3">
                        <div class="w-9 h-9 bg-primary-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-user-plus text-primary-600"></i>
                        </div>
                        <h3 class="text-base font-semibold text-gray-900">Create Account</h3>
                    </div>
                    <button onclick="closeCreateModal()" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times"></i></button>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                        <input type="text" id="new-name" placeholder="e.g. Jane Muthoni" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                        <input type="text" id="new-username" placeholder="e.g. jane.muthoni" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" id="new-email" placeholder="jane@example.com" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                        <input type="password" id="new-password" placeholder="Min. 6 characters" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                        <select id="new-role" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 bg-white">
                            <option value="staff">Staff — Orders &amp; Messages only</option>
                            <option value="admin">Admin — Full access</option>
                        </select>
                    </div>
                    <div id="create-error" class="hidden p-3 bg-red-50 border border-red-200 rounded-lg text-red-600 text-sm">
                        <i class="fas fa-exclamation-circle mr-1"></i><span id="create-error-text"></span>
                    </div>
                </div>
                <div class="flex space-x-3 px-6 pb-6">
                    <button onclick="closeCreateModal()" class="flex-1 py-2.5 border border-gray-200 rounded-lg text-gray-600 hover:bg-gray-50 text-sm font-medium transition-colors">Cancel</button>
                    <button onclick="createAccount()" id="create-btn" class="flex-1 py-2.5 bg-gradient-to-r from-primary-600 to-secondary-600 text-white rounded-lg hover:opacity-90 text-sm font-semibold transition-opacity">
                        Create Account
                    </button>
                </div>
            </div>
        </div>

        <!-- Toggle Status Confirm Modal -->
        <div id="toggle-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm p-6">
                <div class="flex items-center space-x-4 mb-4">
                    <div id="toggle-icon-wrap" class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-power-off text-yellow-600 text-lg"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-semibold text-gray-900" id="toggle-title">Deactivate Account</h3>
                        <p class="text-sm text-gray-500" id="toggle-desc">This will prevent the user from logging in.</p>
                    </div>
                </div>
                <div class="flex space-x-3 mt-4">
                    <button onclick="closeToggleModal()" class="flex-1 px-4 py-2 border border-gray-200 rounded-lg text-gray-600 hover:bg-gray-50 transition-colors text-sm font-medium">Cancel</button>
                    <button onclick="confirmToggle()" id="toggle-confirm-btn" class="flex-1 px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition-colors text-sm font-medium">Confirm</button>
                </div>
            </div>
        </div>

        <div class="p-8">
            <!-- Page header -->
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-lg font-bold text-gray-900">Admin Accounts</h2>
                    <p class="text-sm text-gray-500 mt-0.5">Manage who has access to the admin panel</p>
                </div>
                <button onclick="openCreateModal()"
                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-primary-600 to-secondary-600 text-white rounded-xl text-sm font-semibold shadow-sm hover:opacity-90 transition-opacity">
                    <i class="fas fa-plus text-xs"></i> Add Account
                </button>
            </div>

            <!-- Accounts Table -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div id="accounts-container">
                    <div class="text-center py-12 text-gray-400">
                        <i class="fas fa-spinner fa-spin text-2xl mb-3"></i>
                        <p>Loading accounts...</p>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<!-- Toast -->
<div id="toast" class="fixed bottom-6 right-6 bg-gray-900 text-white px-5 py-3 rounded-xl shadow-xl transition-all duration-300 translate-y-20 opacity-0 z-50">
    <span id="toast-message"></span>
</div>

<script>
    const API = '../backend/api/admin-accounts.php';
    let toggleTarget = null;

    // ── Load accounts ──────────────────────────────────────────────────
    async function loadAccounts() {
        try {
            const res    = await fetch(API);
            const result = await res.json();
            if (!result.success) { showToast(result.error || 'Failed to load'); return; }
            renderAccounts(result.data.accounts);
        } catch(e) {
            document.getElementById('accounts-container').innerHTML =
                '<div class="p-10 text-center text-gray-400">Failed to load accounts.</div>';
        }
    }

    function renderAccounts(accounts) {
        const container = document.getElementById('accounts-container');
        if (!accounts.length) {
            container.innerHTML = '<div class="p-10 text-center text-gray-400"><i class="fas fa-users text-3xl mb-3 block"></i>No accounts found.</div>';
            return;
        }

        const roleColor = r => r === 'admin'
            ? 'bg-purple-100 text-purple-700'
            : 'bg-blue-100 text-blue-700';

        container.innerHTML = `
        <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">User</th>
                    <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Username</th>
                    <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Role</th>
                    <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Last Login</th>
                    <th class="px-6 py-4"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                ${accounts.map(a => {
                    const initials  = a.full_name.split(' ').map(w => w[0]).join('').toUpperCase().slice(0,2);
                    const lastLogin = a.last_login ? new Date(a.last_login).toLocaleDateString('en-GB', {day:'2-digit',month:'short',year:'numeric'}) : 'Never';
                    const active    = a.is_active === true || a.is_active == 1;
                    return `
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center space-x-3">
                                <div class="w-9 h-9 bg-gradient-to-br from-primary-500 to-secondary-500 rounded-full flex items-center justify-center flex-shrink-0 text-white font-semibold text-xs">
                                    ${escHtml(initials)}
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900">${escHtml(a.full_name)}</p>
                                    <p class="text-xs text-gray-400">${escHtml(a.email)}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-gray-600">${escHtml(a.username)}</td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 rounded-full text-xs font-semibold capitalize ${roleColor(a.role)}">${escHtml(a.role)}</span>
                        </td>
                        <td class="px-6 py-4">
                            ${active
                                ? '<span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700"><i class="fas fa-check-circle mr-1"></i>Active</span>'
                                : '<span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-600"><i class="fas fa-ban mr-1"></i>Inactive</span>'}
                        </td>
                        <td class="px-6 py-4 text-gray-500 text-xs">${lastLogin}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2 justify-end">
                                <button onclick="openToggleModal(${a.id}, ${active}, '${escHtml(a.full_name).replace(/'/g,"\\'")}' )"
                                    class="px-3 py-1.5 text-xs font-medium border ${active ? 'border-yellow-300 text-yellow-700 hover:bg-yellow-50' : 'border-green-300 text-green-700 hover:bg-green-50'} rounded-lg transition-colors">
                                    ${active ? '<i class="fas fa-ban mr-1"></i>Deactivate' : '<i class="fas fa-check mr-1"></i>Activate'}
                                </button>
                            </div>
                        </td>
                    </tr>`;
                }).join('')}
            </tbody>
        </table>
        </div>`;
    }

    // ── Create modal ───────────────────────────────────────────────────
    function openCreateModal() {
        ['new-name','new-username','new-email','new-password'].forEach(id => document.getElementById(id).value = '');
        document.getElementById('new-role').value = 'staff';
        document.getElementById('create-error').classList.add('hidden');
        const btn = document.getElementById('create-btn');
        btn.disabled = false; btn.textContent = 'Create Account';
        document.getElementById('create-modal').classList.remove('hidden');
    }
    function closeCreateModal() { document.getElementById('create-modal').classList.add('hidden'); }

    async function createAccount() {
        const name     = document.getElementById('new-name').value.trim();
        const username = document.getElementById('new-username').value.trim();
        const email    = document.getElementById('new-email').value.trim();
        const password = document.getElementById('new-password').value;
        const role     = document.getElementById('new-role').value;
        const errDiv   = document.getElementById('create-error');
        errDiv.classList.add('hidden');

        if (!name)     { document.getElementById('create-error-text').textContent = 'Full name is required.'; errDiv.classList.remove('hidden'); return; }
        if (!username) { document.getElementById('create-error-text').textContent = 'Username is required.'; errDiv.classList.remove('hidden'); return; }
        if (!email)    { document.getElementById('create-error-text').textContent = 'Email is required.'; errDiv.classList.remove('hidden'); return; }
        if (password.length < 6) { document.getElementById('create-error-text').textContent = 'Password must be at least 6 characters.'; errDiv.classList.remove('hidden'); return; }

        const btn = document.getElementById('create-btn');
        btn.disabled = true; btn.textContent = 'Creating...';

        try {
            const res    = await fetch(API, {
                method:  'POST',
                headers: { 'Content-Type': 'application/json' },
                body:    JSON.stringify({ action: 'create', full_name: name, username, email, password, role })
            });
            const result = await res.json();
            if (result.success) {
                closeCreateModal();
                showToast('Account created successfully');
                loadAccounts();
            } else {
                document.getElementById('create-error-text').textContent = result.error || 'Failed to create account.';
                errDiv.classList.remove('hidden');
                btn.disabled = false; btn.textContent = 'Create Account';
            }
        } catch(e) {
            document.getElementById('create-error-text').textContent = 'Network error. Please try again.';
            errDiv.classList.remove('hidden');
            btn.disabled = false; btn.textContent = 'Create Account';
        }
    }

    // ── Toggle active ──────────────────────────────────────────────────
    function openToggleModal(id, isActive, name) {
        toggleTarget = { id, isActive };
        const deactivating = isActive;
        document.getElementById('toggle-title').textContent = deactivating ? 'Deactivate Account' : 'Activate Account';
        document.getElementById('toggle-desc').textContent  = deactivating
            ? `${name} will no longer be able to log in.`
            : `${name} will regain access to the admin panel.`;
        const wrap = document.getElementById('toggle-icon-wrap');
        wrap.className = deactivating
            ? 'w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center flex-shrink-0'
            : 'w-12 h-12 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0';
        document.getElementById('toggle-confirm-btn').className = deactivating
            ? 'flex-1 px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition-colors text-sm font-medium'
            : 'flex-1 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors text-sm font-medium';
        document.getElementById('toggle-modal').classList.remove('hidden');
    }
    function closeToggleModal() { toggleTarget = null; document.getElementById('toggle-modal').classList.add('hidden'); }

    async function confirmToggle() {
        if (!toggleTarget) return;
        closeToggleModal();
        try {
            const res    = await fetch(API, {
                method:  'POST',
                headers: { 'Content-Type': 'application/json' },
                body:    JSON.stringify({ action: 'toggle', id: toggleTarget.id, active: !toggleTarget.isActive })
            });
            const result = await res.json();
            if (result.success) {
                showToast(toggleTarget.isActive ? 'Account deactivated' : 'Account activated');
                loadAccounts();
            } else {
                showToast(result.error || 'Failed to update', 'error');
            }
        } catch(e) {
            showToast('An error occurred', 'error');
        }
    }

    function showToast(message) {
        const toast = document.getElementById('toast');
        document.getElementById('toast-message').textContent = message;
        toast.classList.remove('translate-y-20','opacity-0');
        setTimeout(() => toast.classList.add('translate-y-20','opacity-0'), 3000);
    }
    function escHtml(str) {
        return String(str||'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }
    function toggleDarkMode() {
        const isDark = document.documentElement.classList.toggle('dark');
        localStorage.setItem('adminDark', isDark ? '1' : '0');
        document.getElementById('dark-icon').className = isDark ? 'fas fa-sun text-xl' : 'fas fa-moon text-xl';
    }
    async function logout() {
        await fetch('../backend/api/admin-auth.php', { method:'POST', headers:{'Content-Type':'application/json'}, body: JSON.stringify({action:'logout'}) });
        window.location.href = 'login.php';
    }

    // ── Bell & Profile ─────────────────────────────────────────────────
    function toggleNotifications() { document.getElementById('profile-dropdown').classList.add('hidden'); document.getElementById('notif-dropdown').classList.toggle('hidden'); }
    function toggleProfile()       { document.getElementById('notif-dropdown').classList.add('hidden'); document.getElementById('profile-dropdown').classList.toggle('hidden'); }
    function closeDropdowns()      { document.getElementById('notif-dropdown').classList.add('hidden'); document.getElementById('profile-dropdown').classList.add('hidden'); }
    document.addEventListener('click', e => {
        if (!document.getElementById('notif-wrapper').contains(e.target) &&
            !document.getElementById('profile-wrapper').contains(e.target)) closeDropdowns();
    });
    function updateNotifications(data) {
        const pending=data.pending_orders_count||0, lowStock=data.low_stock_count||0, total=pending+lowStock;
        const badge=document.getElementById('notif-badge');
        if(total>0){badge.textContent=total>99?'99+':total;badge.classList.remove('hidden');badge.classList.add('flex');}
        else{badge.classList.add('hidden');badge.classList.remove('flex');}
        document.getElementById('notif-total-badge').textContent=total>0?`${total} new`:'All clear';
        const items=[];
        if(pending>0) items.push(`<a href="orders.php" class="flex items-start space-x-3 px-4 py-3 hover:bg-gray-50 transition-colors"><div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5"><i class="fas fa-clock text-yellow-600 text-sm"></i></div><div><p class="text-sm font-medium text-gray-900">${pending} Pending Order${pending>1?'s':''}</p><p class="text-xs text-gray-500">Awaiting processing</p></div></a>`);
        if(lowStock>0) items.push(`<a href="products.php" class="flex items-start space-x-3 px-4 py-3 hover:bg-gray-50 transition-colors"><div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5"><i class="fas fa-exclamation-triangle text-red-600 text-sm"></i></div><div><p class="text-sm font-medium text-gray-900">${lowStock} Low Stock Product${lowStock>1?'s':''}</p><p class="text-xs text-gray-500">Stock below 10 units</p></div></a>`);
        document.getElementById('notif-list').innerHTML=items.length?items.join(''):'<div class="px-4 py-6 text-center text-gray-400 text-sm"><i class="fas fa-check-circle text-green-400 text-2xl mb-2 block"></i>No new notifications</div>';
    }
    async function loadNotifications() { try{const r=await fetch('../backend/api/dashboard.php');const d=await r.json();if(d.success)updateNotifications(d.data);}catch(e){} }
    function openChangePassword() { ['cp-current','cp-new','cp-confirm'].forEach(id=>document.getElementById(id).value=''); document.getElementById('cp-error').classList.add('hidden'); document.getElementById('cp-success').classList.add('hidden'); document.getElementById('change-pwd-modal').classList.remove('hidden'); }
    function closeChangePassword() { document.getElementById('change-pwd-modal').classList.add('hidden'); }
    async function submitChangePassword() {
        const current=document.getElementById('cp-current').value, newPwd=document.getElementById('cp-new').value, confirm=document.getElementById('cp-confirm').value;
        const errDiv=document.getElementById('cp-error'), btn=document.getElementById('cp-btn');
        errDiv.classList.add('hidden'); document.getElementById('cp-success').classList.add('hidden');
        if(!current||!newPwd||!confirm){document.getElementById('cp-error-text').textContent='All fields are required.';errDiv.classList.remove('hidden');return;}
        if(newPwd!==confirm){document.getElementById('cp-error-text').textContent='New passwords do not match.';errDiv.classList.remove('hidden');return;}
        if(newPwd.length<6){document.getElementById('cp-error-text').textContent='Password must be at least 6 characters.';errDiv.classList.remove('hidden');return;}
        btn.disabled=true;btn.textContent='Saving...';
        try{const res=await fetch('../backend/api/admin-auth.php',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({action:'change_password',current_password:current,new_password:newPwd})});const result=await res.json();
        if(result.success){document.getElementById('cp-success').classList.remove('hidden');['cp-current','cp-new','cp-confirm'].forEach(id=>document.getElementById(id).value='');setTimeout(closeChangePassword,1800);}
        else{document.getElementById('cp-error-text').textContent=result.error||'Failed to change password.';errDiv.classList.remove('hidden');}
        }catch(e){document.getElementById('cp-error-text').textContent='An error occurred.';errDiv.classList.remove('hidden');}
        finally{btn.disabled=false;btn.textContent='Save';}
    }

    document.addEventListener('DOMContentLoaded', () => {
        if (document.documentElement.classList.contains('dark')) document.getElementById('dark-icon').className = 'fas fa-sun text-xl';
        document.getElementById('change-pwd-modal').addEventListener('click', function(e){ if(e.target===this) closeChangePassword(); });
        document.getElementById('create-modal').addEventListener('click', function(e){ if(e.target===this) closeCreateModal(); });
        document.getElementById('toggle-modal').addEventListener('click', function(e){ if(e.target===this) closeToggleModal(); });
        loadAccounts();
        loadNotifications();
    });
</script>
</body>
</html>
