<?php
/**
 * Primex Mattress & Beddings - Admin Orders
 */

require_once __DIR__ . '/../backend/includes/auth.php';
$auth->requireAdmin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders - Primex Admin</title>
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
                    <i class="fas fa-tachometer-alt w-5"></i>
                    <span class="font-medium">Dashboard</span>
                </a>
                <a href="products.php" class="sidebar-link flex items-center space-x-3 px-4 py-3 text-gray-600 hover:text-primary-600 rounded-lg">
                    <i class="fas fa-box w-5"></i>
                    <span class="font-medium">Products</span>
                </a>
                <a href="orders.php" class="sidebar-link active flex items-center space-x-3 px-4 py-3 text-primary-600 rounded-lg">
                    <i class="fas fa-shopping-cart w-5"></i>
                    <span class="font-medium">Orders</span>
                </a>
                <a href="categories.php" class="sidebar-link flex items-center space-x-3 px-4 py-3 text-gray-600 hover:text-primary-600 rounded-lg">
                    <i class="fas fa-tags w-5"></i>
                    <span class="font-medium">Categories</span>
                </a>
                <a href="customers.php" class="sidebar-link flex items-center space-x-3 px-4 py-3 text-gray-600 hover:text-primary-600 rounded-lg">
                    <i class="fas fa-users w-5"></i>
                    <span class="font-medium">Customers</span>
                </a>
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
                    <h1 class="text-2xl font-bold text-gray-900">Orders</h1>
                </div>
            </header>

            <div class="p-8">
                <!-- Filters -->
                <div class="bg-white rounded-xl shadow-sm p-4 mb-6 flex flex-wrap items-center gap-4">
                    <div class="flex-1 min-w-[200px]">
                        <input type="text" id="search-input" placeholder="Search order number..." 
                            class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500"
                            onkeyup="debounceSearch()">
                    </div>
                    <select id="status-filter" onchange="loadOrders()" class="px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500">
                        <option value="">All Status</option>
                        <option value="pending">Pending</option>
                        <option value="processing">Processing</option>
                        <option value="shipped">Shipped</option>
                        <option value="delivered">Delivered</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>

                <!-- Orders Table -->
                <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Order #</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Customer</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Date</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Items</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Total</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Status</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="orders-table">
                            <!-- Loaded dynamically -->
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div id="pagination" class="mt-6 flex justify-center"></div>
            </div>
        </main>
    </div>

    <!-- Order Detail Modal -->
    <div id="order-modal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-3xl w-full max-h-[90vh] overflow-y-auto">
            <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                <h2 class="text-xl font-semibold" id="modal-title">Order Details</h2>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div id="order-details" class="p-6">
                <!-- Loaded dynamically -->
            </div>
        </div>
    </div>

    <script>
        let currentPage = 1;

        // Load orders
        async function loadOrders() {
            try {
                let url = `../backend/api/orders.php?admin=1&page=${currentPage}&limit=10`;
                const status = document.getElementById('status-filter').value;
                
                if (status) url += `&status=${encodeURIComponent(status)}`;

                const response = await fetch(url);
                const result = await response.json();

                if (result.success) {
                    displayOrders(result.data.orders);
                    displayPagination(result.data.pagination);
                }
            } catch (error) {
                console.error('Error loading orders:', error);
            }
        }

        // Display orders
        function displayOrders(orders) {
            const tbody = document.getElementById('orders-table');
            tbody.innerHTML = orders.map(o => `
                <tr class="border-b border-gray-50 hover:bg-gray-50">
                    <td class="px-6 py-4 font-medium">${o.order_number}</td>
                    <td class="px-6 py-4">${o.shipping_first_name || 'Guest'} ${o.shipping_last_name || ''}</td>
                    <td class="px-6 py-4">${new Date(o.created_at).toLocaleDateString()}</td>
                    <td class="px-6 py-4">${o.item_count}</td>
                    <td class="px-6 py-4 font-medium">$${parseFloat(o.total_amount).toFixed(2)}</td>
                    <td class="px-6 py-4">
                        <select onchange="updateStatus(${o.id}, this.value)" class="px-3 py-1 rounded-lg text-sm font-medium border-0 ${getStatusColor(o.status)}">
                            <option value="pending" ${o.status === 'pending' ? 'selected' : ''}>Pending</option>
                            <option value="processing" ${o.status === 'processing' ? 'selected' : ''}>Processing</option>
                            <option value="shipped" ${o.status === 'shipped' ? 'selected' : ''}>Shipped</option>
                            <option value="delivered" ${o.status === 'delivered' ? 'selected' : ''}>Delivered</option>
                            <option value="cancelled" ${o.status === 'cancelled' ? 'selected' : ''}>Cancelled</option>
                        </select>
                    </td>
                    <td class="px-6 py-4">
                        <button onclick="viewOrder(${o.id})" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg">
                            <i class="fas fa-eye"></i>
                        </button>
                    </td>
                </tr>
            `).join('');
        }

        // Get status color
        function getStatusColor(status) {
            const colors = {
                'pending': 'bg-yellow-100 text-yellow-700',
                'processing': 'bg-blue-100 text-blue-700',
                'shipped': 'bg-purple-100 text-purple-700',
                'delivered': 'bg-green-100 text-green-700',
                'cancelled': 'bg-red-100 text-red-700'
            };
            return colors[status] || 'bg-gray-100 text-gray-700';
        }

        // Display pagination
        function displayPagination(pagination) {
            const container = document.getElementById('pagination');
            if (pagination.pages <= 1) {
                container.innerHTML = '';
                return;
            }

            let html = '<div class="flex items-center space-x-2">';
            for (let i = 1; i <= pagination.pages; i++) {
                html += `
                    <button onclick="goToPage(${i})" class="px-4 py-2 rounded-lg ${i === currentPage ? 'bg-primary-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-50'} border border-gray-200">
                        ${i}
                    </button>
                `;
            }
            html += '</div>';
            container.innerHTML = html;
        }

        function goToPage(page) {
            currentPage = page;
            loadOrders();
        }

        // Update order status
        async function updateStatus(id, status) {
            try {
                const response = await fetch('../backend/api/orders.php', {
                    method: 'PUT',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id: id, status: status })
                });

                const result = await response.json();
                if (result.success) {
                    loadOrders();
                } else {
                    alert(result.error || 'Failed to update status');
                }
            } catch (error) {
                console.error('Error updating status:', error);
            }
        }

        // View order details
        async function viewOrder(id) {
            try {
                const response = await fetch(`../backend/api/orders.php?id=${id}`);
                const result = await response.json();

                if (result.success) {
                    const o = result.data;
                    document.getElementById('order-details').innerHTML = `
                        <div class="grid md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <h3 class="font-semibold mb-2">Order Information</h3>
                                <p class="text-gray-600">Order #: ${o.order_number}</p>
                                <p class="text-gray-600">Date: ${new Date(o.created_at).toLocaleString()}</p>
                                <p class="text-gray-600">Status: <span class="px-2 py-1 rounded-full text-xs font-medium ${getStatusColor(o.status)}">${o.status}</span></p>
                            </div>
                            <div>
                                <h3 class="font-semibold mb-2">Customer Information</h3>
                                <p class="text-gray-600">${o.shipping_first_name} ${o.shipping_last_name}</p>
                                <p class="text-gray-600">${o.customer_email}</p>
                                <p class="text-gray-600">${o.customer_phone}</p>
                            </div>
                        </div>
                        <div class="mb-6">
                            <h3 class="font-semibold mb-2">Shipping Address</h3>
                            <p class="text-gray-600">${o.shipping_address}</p>
                            <p class="text-gray-600">${o.shipping_city}, ${o.shipping_state} ${o.shipping_zip}</p>
                        </div>
                        <div>
                            <h3 class="font-semibold mb-2">Order Items</h3>
                            <table class="w-full">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-2 text-left text-sm">Product</th>
                                        <th class="px-4 py-2 text-left text-sm">Qty</th>
                                        <th class="px-4 py-2 text-left text-sm">Price</th>
                                        <th class="px-4 py-2 text-left text-sm">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${o.items.map(item => `
                                        <tr class="border-b border-gray-100">
                                            <td class="px-4 py-2">${item.product_name}</td>
                                            <td class="px-4 py-2">${item.quantity}</td>
                                            <td class="px-4 py-2">$${parseFloat(item.unit_price).toFixed(2)}</td>
                                            <td class="px-4 py-2">$${parseFloat(item.total_price).toFixed(2)}</td>
                                        </tr>
                                    `).join('')}
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-6 pt-6 border-t border-gray-100">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Subtotal</span>
                                <span>$${parseFloat(o.subtotal).toFixed(2)}</span>
                            </div>
                            <div class="flex justify-between mt-2">
                                <span class="text-gray-600">Shipping</span>
                                <span>$${parseFloat(o.shipping_cost).toFixed(2)}</span>
                            </div>
                            <div class="flex justify-between mt-2">
                                <span class="text-gray-600">Tax</span>
                                <span>$${parseFloat(o.tax_amount).toFixed(2)}</span>
                            </div>
                            <div class="flex justify-between mt-4 pt-4 border-t border-gray-100">
                                <span class="font-semibold">Total</span>
                                <span class="font-bold text-primary-600">$${parseFloat(o.total_amount).toFixed(2)}</span>
                            </div>
                        </div>
                    `;
                    document.getElementById('order-modal').classList.remove('hidden');
                }
            } catch (error) {
                console.error('Error loading order:', error);
            }
        }

        function closeModal() {
            document.getElementById('order-modal').classList.add('hidden');
        }

        // Debounce search
        let searchTimeout;
        function debounceSearch() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                currentPage = 1;
                loadOrders();
            }, 300);
        }

        // Logout
        async function logout() {
            await fetch('../backend/api/admin-auth.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ action: 'logout' })
            });
            window.location.href = 'login.php';
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', loadOrders);
    </script>
</body>
</html>