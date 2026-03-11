<?php
/**
 * Primex Mattress & Beddings - Admin Customers
 */

require_once __DIR__ . '/../backend/includes/auth.php';
$auth->requireAdmin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customers - Primex Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: { 50: '#eff6ff', 100: '#dbeafe', 500: '#3b82f6', 600: '#2563eb', 700: '#1d4ed8' },
                        secondary: { 50: '#faf5ff', 100: '#f3e8ff', 500: '#a855f7', 600: '#9333ea', 700: '#7c3aed' },
                    }
                }
            }
        }
    </script>
    <style>
        .sidebar-link { transition: all 0.3s ease; }
        .sidebar-link:hover, .sidebar-link.active { background: linear-gradient(90deg, rgba(37, 99, 235, 0.1) 0%, transparent 100%); border-right: 3px solid #2563eb; }
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
                <a href="index.php" class="sidebar-link flex items-center space-x-3 px-4 py-3 text-gray-600 hover:text-primary-600 rounded-lg">
                    <i class="fas fa-tachometer-alt w-5"></i><span class="font-medium">Dashboard</span>
                </a>
                <a href="products.php" class="sidebar-link flex items-center space-x-3 px-4 py-3 text-gray-600 hover:text-primary-600 rounded-lg">
                    <i class="fas fa-box w-5"></i><span class="font-medium">Products</span>
                </a>
                <a href="orders.php" class="sidebar-link flex items-center space-x-3 px-4 py-3 text-gray-600 hover:text-primary-600 rounded-lg">
                    <i class="fas fa-shopping-cart w-5"></i><span class="font-medium">Orders</span>
                </a>
                <a href="categories.php" class="sidebar-link flex items-center space-x-3 px-4 py-3 text-gray-600 hover:text-primary-600 rounded-lg">
                    <i class="fas fa-tags w-5"></i><span class="font-medium">Categories</span>
                </a>
                <a href="customers.php" class="sidebar-link active flex items-center space-x-3 px-4 py-3 text-primary-600 rounded-lg">
                    <i class="fas fa-users w-5"></i><span class="font-medium">Customers</span>
                </a>
            </nav>
            <div class="absolute bottom-0 w-64 p-4 border-t border-gray-100">
                <button onclick="logout()" class="flex items-center space-x-3 px-4 py-3 text-red-600 hover:bg-red-50 rounded-lg w-full transition-colors">
                    <i class="fas fa-sign-out-alt w-5"></i><span class="font-medium">Logout</span>
                </button>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 overflow-y-auto">
            <header class="bg-white shadow-sm sticky top-0 z-10">
                <div class="flex items-center justify-between px-8 py-4">
                    <h1 class="text-2xl font-bold text-gray-900">Customers</h1>
                    <div class="flex items-center space-x-3">
                        <span id="customer-count" class="text-sm text-gray-500"></span>
                    </div>
                </div>
            </header>

            <div class="p-8">
                <!-- Search & Stats -->
                <div class="grid md:grid-cols-3 gap-4 mb-6">
                    <div class="bg-white rounded-xl shadow-sm p-5 flex items-center space-x-4">
                        <div class="w-12 h-12 bg-primary-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-users text-primary-600 text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Total Customers</p>
                            <p class="text-2xl font-bold text-gray-900" id="stat-total">—</p>
                        </div>
                    </div>
                    <div class="bg-white rounded-xl shadow-sm p-5 flex items-center space-x-4">
                        <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-shopping-bag text-green-600 text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Total Orders</p>
                            <p class="text-2xl font-bold text-gray-900" id="stat-orders">—</p>
                        </div>
                    </div>
                    <div class="bg-white rounded-xl shadow-sm p-5 flex items-center space-x-4">
                        <div class="w-12 h-12 bg-accent-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-coins text-accent-600 text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Total Revenue</p>
                            <p class="text-2xl font-bold text-gray-900" id="stat-revenue">—</p>
                        </div>
                    </div>
                </div>

                <!-- Search -->
                <div class="bg-white rounded-xl shadow-sm p-4 mb-6">
                    <input type="text" id="search-input" placeholder="Search by name, email or phone..."
                        class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500"
                        oninput="filterCustomers()">
                </div>

                <!-- Customers Table -->
                <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Customer</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Contact</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Location</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Orders</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Total Spent</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Last Order</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="customers-table">
                            <tr><td colspan="7" class="px-6 py-12 text-center text-gray-400">Loading customers...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <!-- Customer Orders Modal -->
    <div id="customer-modal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-3xl w-full max-h-[90vh] overflow-y-auto">
            <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                <h2 class="text-xl font-semibold" id="modal-customer-name">Customer Orders</h2>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div id="modal-content" class="p-6"></div>
        </div>
    </div>

    <script>
        let allCustomers = [];

        async function loadCustomers() {
            try {
                const response = await fetch('../backend/api/customers.php');
                const result = await response.json();

                if (result.success) {
                    allCustomers = result.data.customers;
                    renderCustomers(allCustomers);
                    document.getElementById('stat-total').textContent = result.data.stats.total_customers;
                    document.getElementById('stat-orders').textContent = result.data.stats.total_orders;
                    document.getElementById('stat-revenue').textContent = 'KSh ' + parseFloat(result.data.stats.total_revenue || 0).toLocaleString();
                    document.getElementById('customer-count').textContent = result.data.stats.total_customers + ' customers';
                }
            } catch (error) {
                console.error('Error loading customers:', error);
                document.getElementById('customers-table').innerHTML =
                    '<tr><td colspan="7" class="px-6 py-12 text-center text-red-500">Failed to load customers.</td></tr>';
            }
        }

        function renderCustomers(customers) {
            const tbody = document.getElementById('customers-table');
            if (!customers.length) {
                tbody.innerHTML = '<tr><td colspan="7" class="px-6 py-12 text-center text-gray-400">No customers found.</td></tr>';
                return;
            }

            tbody.innerHTML = customers.map(c => {
                const initials = `${c.first_name.charAt(0)}${c.last_name.charAt(0)}`.toUpperCase();
                const colorClass = ['bg-primary-100 text-primary-700','bg-secondary-100 text-secondary-700','bg-green-100 text-green-700','bg-accent-100 text-accent-700'][Math.floor(Math.random() * 4)];
                return `
                <tr class="border-b border-gray-50 hover:bg-gray-50">
                    <td class="px-6 py-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 ${colorClass} rounded-full flex items-center justify-center font-semibold text-sm flex-shrink-0">
                                ${initials}
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">${c.first_name} ${c.last_name}</p>
                                <p class="text-xs text-gray-400">${c.checkout_mode === 'account' ? '<i class="fas fa-user-circle mr-1"></i>Account' : '<i class="fas fa-user mr-1"></i>Guest'}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <p class="text-sm text-gray-900">${c.email}</p>
                        <p class="text-sm text-gray-500">${c.phone || '—'}</p>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">
                        ${c.city ? c.city + (c.country ? ', ' + c.country : '') : '—'}
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 bg-primary-100 text-primary-700 rounded-full text-xs font-semibold">${c.order_count} order${c.order_count != 1 ? 's' : ''}</span>
                    </td>
                    <td class="px-6 py-4 font-medium text-gray-900">KSh ${parseFloat(c.total_spent).toFixed(2)}</td>
                    <td class="px-6 py-4 text-sm text-gray-500">${new Date(c.last_order_date).toLocaleDateString()}</td>
                    <td class="px-6 py-4">
                        <button onclick="viewCustomer('${encodeURIComponent(c.email)}')" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg" title="View Orders">
                            <i class="fas fa-eye"></i>
                        </button>
                    </td>
                </tr>`;
            }).join('');
        }

        function filterCustomers() {
            const q = document.getElementById('search-input').value.toLowerCase();
            if (!q) { renderCustomers(allCustomers); return; }
            const filtered = allCustomers.filter(c =>
                `${c.first_name} ${c.last_name}`.toLowerCase().includes(q) ||
                c.email.toLowerCase().includes(q) ||
                (c.phone || '').includes(q)
            );
            renderCustomers(filtered);
        }

        async function viewCustomer(emailEncoded) {
            const email = decodeURIComponent(emailEncoded);
            try {
                const response = await fetch(`../backend/api/customers.php?email=${encodeURIComponent(email)}`);
                const result = await response.json();

                if (result.success) {
                    const c = result.data;
                    document.getElementById('modal-customer-name').textContent = `${c.customer.first_name} ${c.customer.last_name}`;
                    document.getElementById('modal-content').innerHTML = `
                        <div class="grid md:grid-cols-2 gap-4 mb-6">
                            <div class="bg-gray-50 rounded-lg p-4">
                                <h3 class="font-semibold text-sm text-gray-500 uppercase tracking-wide mb-3">Contact Details</h3>
                                <p class="text-gray-900 font-medium">${c.customer.first_name} ${c.customer.last_name}</p>
                                <p class="text-gray-600 text-sm">${c.customer.email}</p>
                                <p class="text-gray-600 text-sm">${c.customer.phone || '—'}</p>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <h3 class="font-semibold text-sm text-gray-500 uppercase tracking-wide mb-3">Summary</h3>
                                <p class="text-sm text-gray-600">Total Orders: <strong class="text-gray-900">${c.orders.length}</strong></p>
                                <p class="text-sm text-gray-600">Total Spent: <strong class="text-gray-900">KSh ${c.orders.reduce((s,o)=>s+parseFloat(o.total_amount),0).toFixed(2)}</strong></p>
                                <p class="text-sm text-gray-600">Type: <strong class="text-gray-900 capitalize">${c.customer.checkout_mode || 'guest'}</strong></p>
                            </div>
                        </div>
                        <h3 class="font-semibold mb-3">Order History</h3>
                        <div class="space-y-3">
                            ${c.orders.map(o => `
                                <div class="border border-gray-100 rounded-lg p-4 flex items-center justify-between">
                                    <div>
                                        <p class="font-medium text-sm">${o.order_number}</p>
                                        <p class="text-xs text-gray-500">${new Date(o.created_at).toLocaleDateString()}</p>
                                    </div>
                                    <div class="text-center">
                                        <p class="text-sm text-gray-600">${o.item_count} item${o.item_count != 1 ? 's' : ''}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-semibold text-sm">KSh ${parseFloat(o.total_amount).toFixed(2)}</p>
                                        <span class="text-xs px-2 py-0.5 rounded-full capitalize font-medium
                                            ${o.status === 'delivered' ? 'bg-green-100 text-green-700' :
                                              o.status === 'pending' ? 'bg-yellow-100 text-yellow-700' :
                                              o.status === 'processing' ? 'bg-blue-100 text-blue-700' :
                                              o.status === 'shipped' ? 'bg-purple-100 text-purple-700' :
                                              'bg-red-100 text-red-700'}">${o.status}</span>
                                    </div>
                                </div>
                            `).join('')}
                        </div>
                    `;
                    document.getElementById('customer-modal').classList.remove('hidden');
                }
            } catch (error) {
                console.error('Error loading customer details:', error);
            }
        }

        function closeModal() {
            document.getElementById('customer-modal').classList.add('hidden');
        }

        async function logout() {
            await fetch('../backend/api/admin-auth.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ action: 'logout' })
            });
            window.location.href = 'login.php';
        }

        document.addEventListener('DOMContentLoaded', loadCustomers);
    </script>
</body>
</html>
