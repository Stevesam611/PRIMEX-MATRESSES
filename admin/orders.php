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
        (function(){if(localStorage.getItem('adminDark')==='1')document.documentElement.classList.add('dark');})();
    </script>
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
        html.dark .divide-gray-100>*+*,html.dark .divide-gray-50>*+*{border-color:#334155!important}
        html.dark input:not([type=checkbox]):not([type=radio]),html.dark textarea,html.dark select{background-color:#1e293b!important;border-color:#334155!important;color:#e2e8f0!important}
        html.dark input::placeholder,html.dark textarea::placeholder{color:#64748b!important}
        html.dark .hover\:bg-gray-50:hover{background-color:#162032!important}
        html.dark .hover\:bg-gray-100:hover{background-color:#334155!important}
        html.dark .hover\:bg-red-50:hover{background-color:#2d1a1a!important}
        html.dark .bg-red-50{background-color:#2d1a1a!important}
        html.dark .bg-green-50{background-color:#1a2d1a!important}
        html.dark .bg-yellow-50{background-color:#2d2a1a!important}
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
                    <button onclick="toggleDarkMode()" id="dark-toggle" title="Toggle dark mode" class="p-2 text-gray-400 hover:text-gray-600 focus:outline-none">
                        <i id="dark-icon" class="fas fa-moon text-xl"></i>
                    </button>
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
                <div class="flex items-center space-x-3">
                    <button onclick="printOrder()" class="flex items-center space-x-2 px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors text-sm font-medium">
                        <i class="fas fa-print"></i>
                        <span>Print / PDF</span>
                    </button>
                    <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>
            <div id="order-details" class="p-6">
                <!-- Loaded dynamically -->
            </div>
        </div>
    </div>

    <script>
        let currentPage = 1;
        let currentOrder = null;

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
                    <td class="px-6 py-4 font-medium">KSh ${parseFloat(o.total_amount).toFixed(2)}</td>
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
                        <div class="flex items-center space-x-1">
                            <button onclick="viewOrder(${o.id})" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg" title="View">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button onclick="viewAndPrint(${o.id})" class="p-2 text-gray-500 hover:bg-gray-100 rounded-lg" title="Print">
                                <i class="fas fa-print"></i>
                            </button>
                        </div>
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
                    currentOrder = o;
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
                                            <td class="px-4 py-2">KSh ${parseFloat(item.unit_price).toFixed(2)}</td>
                                            <td class="px-4 py-2">KSh ${parseFloat(item.total_price).toFixed(2)}</td>
                                        </tr>
                                    `).join('')}
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-6 pt-6 border-t border-gray-100">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Subtotal</span>
                                <span>KSh ${parseFloat(o.subtotal).toFixed(2)}</span>
                            </div>
                            <div class="flex justify-between mt-2">
                                <span class="text-gray-600">Shipping</span>
                                <span>KSh ${parseFloat(o.shipping_cost).toFixed(2)}</span>
                            </div>
                            <div class="flex justify-between mt-2">
                                <span class="text-gray-600">Tax</span>
                                <span>KSh ${parseFloat(o.tax_amount).toFixed(2)}</span>
                            </div>
                            <div class="flex justify-between mt-4 pt-4 border-t border-gray-100">
                                <span class="font-semibold">Total</span>
                                <span class="font-bold text-primary-600">KSh ${parseFloat(o.total_amount).toFixed(2)}</span>
                            </div>
                        </div>
                    `;
                    document.getElementById('order-modal').classList.remove('hidden');
                }
            } catch (error) {
                console.error('Error loading order:', error);
            }
        }

        // Load order then immediately print (from table row button)
        async function viewAndPrint(id) {
            try {
                const response = await fetch(`../backend/api/orders.php?id=${id}`);
                const result = await response.json();
                if (result.success) {
                    currentOrder = result.data;
                    printOrder();
                }
            } catch (error) {
                console.error('Error loading order for print:', error);
            }
        }

        function closeModal() {
            document.getElementById('order-modal').classList.add('hidden');
        }

        // Print order as PDF
        function printOrder() {
            if (!currentOrder) return;
            const o = currentOrder;

            const itemsRows = o.items.map(item => `
                <tr>
                    <td style="padding:10px 12px;border-bottom:1px solid #e5e7eb;">${item.product_name}</td>
                    <td style="padding:10px 12px;border-bottom:1px solid #e5e7eb;text-align:center;">${item.quantity}</td>
                    <td style="padding:10px 12px;border-bottom:1px solid #e5e7eb;text-align:right;">KSh ${parseFloat(item.unit_price).toFixed(2)}</td>
                    <td style="padding:10px 12px;border-bottom:1px solid #e5e7eb;text-align:right;">KSh ${parseFloat(item.total_price).toFixed(2)}</td>
                </tr>
            `).join('');

            const shippingFree = parseFloat(o.shipping_cost) === 0;

            const html = `<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order ${o.order_number} - Primex Mattress &amp; Beddings</title>
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family: Arial, sans-serif; font-size:13px; color:#1f2937; background:#fff; position:relative; }

        /* Watermark */
        body::before {
            content: 'PRIMEX';
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-35deg);
            font-size: 120px;
            font-weight: 900;
            color: rgba(30, 58, 138, 0.07);
            letter-spacing: 12px;
            pointer-events: none;
            z-index: 0;
            white-space: nowrap;
        }

        .page { max-width:750px; margin:0 auto; padding:40px; position:relative; z-index:1; }

        /* Letterhead */
        .letterhead { border-bottom:3px solid #2563eb; padding-bottom:18px; margin-bottom:24px; display:flex; justify-content:space-between; align-items:center; }
        .logo-wrap { display:flex; align-items:center; gap:14px; }
        .logo-icon { width:52px; height:52px; background:linear-gradient(135deg,#2563eb,#7c3aed); border-radius:12px; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
        .logo-text { }
        .company-name { font-size:20px; font-weight:900; color:#1e3a8a; letter-spacing:0.5px; text-transform:uppercase; line-height:1.1; }
        .slogan { font-size:10.5px; color:#2563eb; font-style:italic; margin-top:3px; }
        .contact-block { text-align:right; font-size:11.5px; color:#4b5563; line-height:1.8; }
        .contact-block strong { color:#1f2937; display:block; margin-bottom:2px; }

        /* Order meta */
        .order-meta { display:flex; justify-content:space-between; margin-bottom:24px; gap:12px; }
        .meta-box { background:#f3f4f6; border-radius:8px; padding:14px 18px; flex:1; }
        .meta-box h4 { font-size:10px; text-transform:uppercase; letter-spacing:0.5px; color:#6b7280; margin-bottom:8px; }
        .meta-box p { font-size:13px; color:#1f2937; line-height:1.6; }

        /* Status badge */
        .status-badge { display:inline-block; padding:3px 10px; border-radius:20px; font-size:11px; font-weight:600; text-transform:capitalize; }
        .status-pending   { background:#fef9c3; color:#854d0e; }
        .status-processing{ background:#dbeafe; color:#1e40af; }
        .status-shipped   { background:#ede9fe; color:#5b21b6; }
        .status-delivered { background:#dcfce7; color:#14532d; }
        .status-cancelled { background:#fee2e2; color:#7f1d1d; }

        /* Items table */
        .section-title { font-size:12px; font-weight:700; color:#1f2937; text-transform:uppercase; letter-spacing:0.5px; margin-bottom:10px; }
        table { width:100%; border-collapse:collapse; margin-bottom:24px; }
        thead tr { background:#1e3a8a; color:#fff; }
        thead th { padding:10px 12px; text-align:left; font-size:12px; font-weight:600; }
        thead th:nth-child(2) { text-align:center; }
        thead th:nth-child(3), thead th:nth-child(4) { text-align:right; }
        tbody tr:nth-child(even) { background:#f9fafb; }

        /* Totals */
        .totals { margin-left:auto; width:280px; }
        .totals-row { display:flex; justify-content:space-between; padding:6px 0; font-size:13px; border-bottom:1px solid #f3f4f6; }
        .totals-row.grand { border-top:2px solid #1e3a8a; border-bottom:none; padding-top:10px; margin-top:4px; font-size:15px; font-weight:700; color:#1e3a8a; }

        /* Footer */
        .footer { margin-top:36px; border-top:1px solid #e5e7eb; padding-top:14px; text-align:center; font-size:11px; color:#9ca3af; }
        .footer strong { color:#2563eb; }

        @media print {
            body { print-color-adjust:exact; -webkit-print-color-adjust:exact; }
            body::before { print-color-adjust:exact; -webkit-print-color-adjust:exact; }
        }
    </style>
</head>
<body>
<div class="page">

    <!-- Letterhead with Logo -->
    <div class="letterhead">
        <div class="logo-wrap">
            <div class="logo-icon">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect x="2" y="13" width="20" height="7" rx="2" fill="white" opacity="0.95"/>
                    <rect x="2" y="10" width="20" height="4" rx="1" fill="white" opacity="0.7"/>
                    <rect x="4" y="6" width="7" height="5" rx="1.5" fill="white" opacity="0.85"/>
                    <rect x="13" y="6" width="7" height="5" rx="1.5" fill="white" opacity="0.85"/>
                    <rect x="3" y="19" width="2" height="3" rx="1" fill="white" opacity="0.7"/>
                    <rect x="19" y="19" width="2" height="3" rx="1" fill="white" opacity="0.7"/>
                </svg>
            </div>
            <div class="logo-text">
                <div class="company-name">Primex Mattress &amp; Beddings</div>
                <div class="slogan">Quality, Durable and Affordable Products</div>
            </div>
        </div>
        <div class="contact-block">
            <strong>Langa Langa, Nakuru, Kenya</strong>
            Tel: 011589001 &nbsp;|&nbsp; 0768274937<br>
            mattressgoodmorning@gmail.com
        </div>
    </div>

    <!-- Order Title -->
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;">
        <div>
            <div style="font-size:18px;font-weight:700;color:#1e3a8a;">ORDER RECEIPT</div>
            <div style="font-size:13px;color:#6b7280;margin-top:2px;">${o.order_number}</div>
        </div>
        <div style="text-align:right;">
            <div style="font-size:12px;color:#6b7280;">Date Issued</div>
            <div style="font-size:13px;font-weight:600;">${new Date(o.created_at).toLocaleDateString('en-KE', {year:'numeric',month:'long',day:'numeric'})}</div>
            <span class="status-badge status-${o.status}">${o.status}</span>
        </div>
    </div>

    <!-- Meta Info -->
    <div class="order-meta">
        <div class="meta-box">
            <h4>Customer</h4>
            <p><strong>${o.shipping_first_name} ${o.shipping_last_name}</strong><br>
            ${o.customer_email}<br>
            ${o.customer_phone || ''}</p>
        </div>
        <div class="meta-box">
            <h4>Delivery Address</h4>
            <p>${o.shipping_address}<br>
            ${o.shipping_city}${o.shipping_state ? ', ' + o.shipping_state : ''}${o.shipping_zip ? ' ' + o.shipping_zip : ''}</p>
        </div>
        <div class="meta-box">
            <h4>Payment</h4>
            <p>Cash on Delivery</p>
        </div>
    </div>

    <!-- Items -->
    <div class="section-title">Order Items</div>
    <table>
        <thead>
            <tr>
                <th>Product</th>
                <th>Qty</th>
                <th>Unit Price</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            ${itemsRows}
        </tbody>
    </table>

    <!-- Totals -->
    <div class="totals">
        <div class="totals-row"><span>Subtotal</span><span>KSh ${parseFloat(o.subtotal).toFixed(2)}</span></div>
        <div class="totals-row"><span>Shipping</span><span>${shippingFree ? 'FREE' : 'KSh ' + parseFloat(o.shipping_cost).toFixed(2)}</span></div>
        <div class="totals-row"><span>Tax (8%)</span><span>KSh ${parseFloat(o.tax_amount).toFixed(2)}</span></div>
        <div class="totals-row grand"><span>TOTAL</span><span>KSh ${parseFloat(o.total_amount).toFixed(2)}</span></div>
    </div>

    <!-- Footer -->
    <div class="footer">
        Thank you for shopping with <strong>Primex Mattress &amp; Beddings</strong> &nbsp;·&nbsp;
        Quality, Durable and Affordable Products &nbsp;·&nbsp;
        mattressgoodmorning@gmail.com
    </div>

</div>
<script>window.onload = function(){ window.print(); }<\/script>
</body>
</html>`;

            const win = window.open('', '_blank');
            win.document.write(html);
            win.document.close();
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

        // Dark mode
        function toggleDarkMode() {
            const isDark = document.documentElement.classList.toggle('dark');
            localStorage.setItem('adminDark', isDark ? '1' : '0');
            document.getElementById('dark-icon').className = isDark ? 'fas fa-sun text-xl' : 'fas fa-moon text-xl';
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
        document.addEventListener('DOMContentLoaded', () => {
            if (document.documentElement.classList.contains('dark')) {
                document.getElementById('dark-icon').className = 'fas fa-sun text-xl';
            }
            loadOrders();
        });
    </script>
</body>
</html>