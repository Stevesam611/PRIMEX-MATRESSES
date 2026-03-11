<?php
/**
 * Primex Mattress & Beddings - Admin Dashboard
 */

require_once __DIR__ . '/../backend/includes/auth.php';
$auth->requireAdmin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Primex Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // FOUC prevention — apply dark class before paint
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
        .stat-card { transition: all 0.3s ease; }
        .stat-card:hover { transform: translateY(-4px); box-shadow: 0 20px 40px rgba(0,0,0,0.1); }
        /* Dark Mode */
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
                <a href="index.php" class="sidebar-link active flex items-center space-x-3 px-4 py-3 text-primary-600 rounded-lg">
                    <i class="fas fa-tachometer-alt w-5"></i>
                    <span class="font-medium">Dashboard</span>
                </a>
                <a href="products.php" class="sidebar-link flex items-center space-x-3 px-4 py-3 text-gray-600 hover:text-primary-600 rounded-lg">
                    <i class="fas fa-box w-5"></i>
                    <span class="font-medium">Products</span>
                </a>
                <a href="orders.php" class="sidebar-link flex items-center space-x-3 px-4 py-3 text-gray-600 hover:text-primary-600 rounded-lg">
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
                <a href="messages.php" class="sidebar-link flex items-center space-x-3 px-4 py-3 text-gray-600 hover:text-primary-600 rounded-lg">
                    <i class="fas fa-envelope w-5"></i>
                    <span class="font-medium">Messages</span>
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
            <!-- Header -->
            <header class="bg-white shadow-sm sticky top-0 z-20">
                <div class="flex items-center justify-between px-8 py-4">
                    <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
                    <div class="flex items-center space-x-3">

                        <!-- Dark mode toggle -->
                        <button onclick="toggleDarkMode()" id="dark-toggle" title="Toggle dark mode" class="p-2 text-gray-400 hover:text-gray-600 focus:outline-none">
                            <i id="dark-icon" class="fas fa-moon text-xl"></i>
                        </button>

                        <!-- Bell / Notifications -->
                        <div class="relative" id="notif-wrapper">
                            <button onclick="toggleNotifications()" class="p-2 text-gray-400 hover:text-gray-600 relative focus:outline-none">
                                <i class="fas fa-bell text-xl"></i>
                                <span id="notif-badge" class="absolute -top-0.5 -right-0.5 w-5 h-5 bg-red-500 text-white text-xs rounded-full items-center justify-center hidden">0</span>
                            </button>
                            <!-- Notifications dropdown -->
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

                        <!-- Profile -->
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
                            <!-- Profile dropdown -->
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
                                        <i class="fas fa-key text-gray-400 w-4"></i>
                                        <span>Change Password</span>
                                    </button>
                                    <div class="border-t border-gray-100 my-1"></div>
                                    <button onclick="logout()" class="w-full flex items-center space-x-3 px-4 py-2.5 text-red-600 hover:bg-red-50 text-sm transition-colors">
                                        <i class="fas fa-sign-out-alt w-4"></i>
                                        <span>Logout</span>
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
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Current Password</label>
                            <input type="password" id="cp-current" placeholder="••••••••"
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                            <input type="password" id="cp-new" placeholder="Min. 6 characters"
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Confirm New Password</label>
                            <input type="password" id="cp-confirm" placeholder="Repeat new password"
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
                        </div>
                        <div id="cp-error" class="hidden p-3 bg-red-50 border border-red-200 rounded-lg text-red-600 text-sm">
                            <i class="fas fa-exclamation-circle mr-1"></i><span id="cp-error-text"></span>
                        </div>
                        <div id="cp-success" class="hidden p-3 bg-green-50 border border-green-200 rounded-lg text-green-700 text-sm">
                            <i class="fas fa-check-circle mr-1"></i>Password changed successfully!
                        </div>
                    </div>
                    <div class="flex space-x-3 px-6 pb-6">
                        <button onclick="closeChangePassword()" class="flex-1 py-2.5 border border-gray-200 rounded-lg text-gray-600 hover:bg-gray-50 text-sm font-medium transition-colors">Cancel</button>
                        <button onclick="submitChangePassword()" id="cp-btn" class="flex-1 py-2.5 bg-primary-600 text-white rounded-lg hover:bg-primary-700 text-sm font-medium transition-colors">Save</button>
                    </div>
                </div>
            </div>

            <!-- Dashboard Content -->
            <div class="p-8">
                <!-- Stats Cards -->
                <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div class="stat-card bg-white rounded-xl shadow-sm p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-sm">Total Orders</p>
                                <p class="text-3xl font-bold text-gray-900 mt-1" id="stat-orders">-</p>
                            </div>
                            <div class="w-12 h-12 bg-primary-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-shopping-bag text-primary-600 text-xl"></i>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center text-sm">
                            <span class="text-green-500 flex items-center">
                                <i class="fas fa-arrow-up mr-1"></i>12%
                            </span>
                            <span class="text-gray-400 ml-2">vs last month</span>
                        </div>
                    </div>

                    <div class="stat-card bg-white rounded-xl shadow-sm p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-sm">Total Revenue</p>
                                <p class="text-3xl font-bold text-gray-900 mt-1" id="stat-revenue">-</p>
                            </div>
                            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-dollar-sign text-green-600 text-xl"></i>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center text-sm">
                            <span class="text-green-500 flex items-center">
                                <i class="fas fa-arrow-up mr-1"></i>8%
                            </span>
                            <span class="text-gray-400 ml-2">vs last month</span>
                        </div>
                    </div>

                    <div class="stat-card bg-white rounded-xl shadow-sm p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-sm">Products</p>
                                <p class="text-3xl font-bold text-gray-900 mt-1" id="stat-products">-</p>
                            </div>
                            <div class="w-12 h-12 bg-secondary-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-box text-secondary-600 text-xl"></i>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center text-sm">
                            <span class="text-gray-400">Active products</span>
                        </div>
                    </div>

                    <div class="stat-card bg-white rounded-xl shadow-sm p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-sm">Customers</p>
                                <p class="text-3xl font-bold text-gray-900 mt-1" id="stat-customers">-</p>
                            </div>
                            <div class="w-12 h-12 bg-accent-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-users text-accent-600 text-xl"></i>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center text-sm">
                            <span class="text-green-500 flex items-center">
                                <i class="fas fa-arrow-up mr-1"></i>15%
                            </span>
                            <span class="text-gray-400 ml-2">vs last month</span>
                        </div>
                    </div>
                </div>

                <!-- Charts Row -->
                <div class="grid lg:grid-cols-2 gap-6 mb-8">
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold">Sales Overview</h3>
                            <div class="flex items-center space-x-2">
                                <button onclick="changeYear(-1)" class="w-7 h-7 flex items-center justify-center rounded-lg border border-gray-200 hover:bg-gray-50 text-gray-500 hover:text-gray-700 transition-colors">
                                    <i class="fas fa-chevron-left text-xs"></i>
                                </button>
                                <span id="sales-year-label" class="text-sm font-medium text-gray-700 w-12 text-center"></span>
                                <button onclick="changeYear(1)" id="sales-year-next" class="w-7 h-7 flex items-center justify-center rounded-lg border border-gray-200 hover:bg-gray-50 text-gray-500 hover:text-gray-700 transition-colors">
                                    <i class="fas fa-chevron-right text-xs"></i>
                                </button>
                            </div>
                        </div>
                        <canvas id="salesChart" height="250"></canvas>
                    </div>
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <h3 class="text-lg font-semibold mb-4">Orders by Status</h3>
                        <canvas id="ordersChart" height="250"></canvas>
                    </div>
                </div>

                <!-- Recent Orders & Low Stock -->
                <div class="grid lg:grid-cols-2 gap-6">
                    <!-- Recent Orders -->
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold">Recent Orders</h3>
                            <a href="orders.php" class="text-primary-600 hover:text-primary-700 text-sm">View All</a>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                    <tr class="text-left text-gray-500 text-sm border-b border-gray-100">
                                        <th class="pb-3">Order #</th>
                                        <th class="pb-3">Customer</th>
                                        <th class="pb-3">Amount</th>
                                        <th class="pb-3">Status</th>
                                    </tr>
                                </thead>
                                <tbody id="recent-orders">
                                    <!-- Loaded dynamically -->
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Low Stock -->
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold">Low Stock Alert</h3>
                            <a href="products.php" class="text-primary-600 hover:text-primary-700 text-sm">Manage Stock</a>
                        </div>
                        <div id="low-stock-list">
                            <!-- Loaded dynamically -->
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        let salesChart, ordersChart;
        let currentYear = new Date().getFullYear();
        const currentRealYear = new Date().getFullYear();
        const MONTH_LABELS = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

        function changeYear(delta) {
            const newYear = currentYear + delta;
            if (newYear > currentRealYear) return;
            currentYear = newYear;
            loadSalesChart();
        }

        async function loadSalesChart() {
            document.getElementById('sales-year-label').textContent = currentYear;
            document.getElementById('sales-year-next').disabled = currentYear >= currentRealYear;
            document.getElementById('sales-year-next').style.opacity = currentYear >= currentRealYear ? '0.3' : '1';

            try {
                const response = await fetch(`../backend/api/dashboard.php?year=${currentYear}`);
                const result = await response.json();
                if (!result.success) return;

                const monthlyData = result.data.monthly_sales || [];
                const revenueByMonth = {};
                monthlyData.forEach(m => {
                    const idx = new Date(m.month).getMonth();
                    revenueByMonth[idx] = parseFloat(m.revenue) || 0;
                });
                const salesValues = MONTH_LABELS.map((_, i) => revenueByMonth[i] || 0);

                if (salesChart) {
                    salesChart.data.datasets[0].data = salesValues;
                    salesChart.update();
                } else {
                    const salesCtx = document.getElementById('salesChart').getContext('2d');
                    salesChart = new Chart(salesCtx, {
                        type: 'bar',
                        data: {
                            labels: MONTH_LABELS,
                            datasets: [{
                                label: 'Revenue',
                                data: salesValues,
                                backgroundColor: 'rgba(37, 99, 235, 0.75)',
                                borderRadius: 6,
                                borderSkipped: false
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: { legend: { display: false } },
                            scales: {
                                y: { beginAtZero: true, ticks: { callback: v => 'KSh ' + v.toLocaleString() } },
                                x: { grid: { display: false } }
                            }
                        }
                    });
                }
            } catch (e) {
                console.error('Error loading sales chart:', e);
            }
        }

        // Load dashboard data
        async function loadDashboard() {
            try {
                const response = await fetch('../backend/api/dashboard.php');
                const result = await response.json();

                if (result.success) {
                    const data = result.data;
                    
                    // Update stats
                    document.getElementById('stat-orders').textContent = data.total_orders.toLocaleString();
                    document.getElementById('stat-revenue').textContent = 'KSh ' + (data.sales.total_sales || 0).toLocaleString();
                    document.getElementById('stat-products').textContent = data.total_products.toLocaleString();
                    document.getElementById('stat-customers').textContent = data.total_customers.toLocaleString();

                    // Update recent orders
                    const ordersHtml = data.recent_orders.slice(0, 5).map(order => `
                        <tr class="border-b border-gray-50 last:border-0">
                            <td class="py-3 font-medium">${order.order_number}</td>
                            <td class="py-3 text-gray-600">${order.shipping_first_name || 'Guest'} ${order.shipping_last_name || ''}</td>
                            <td class="py-3 font-medium">KSh ${parseFloat(order.total_amount).toFixed(2)}</td>
                            <td class="py-3">
                                <span class="px-2 py-1 rounded-full text-xs font-medium ${getStatusColor(order.status)}">
                                    ${order.status}
                                </span>
                            </td>
                        </tr>
                    `).join('');
                    document.getElementById('recent-orders').innerHTML = ordersHtml;

                    // Update low stock
                    const lowStockHtml = data.low_stock.slice(0, 5).map(product => `
                        <div class="flex items-center justify-between py-3 border-b border-gray-50 last:border-0">
                            <div>
                                <p class="font-medium text-sm">${product.name}</p>
                                <p class="text-gray-500 text-xs">SKU: ${product.sku || 'N/A'}</p>
                            </div>
                            <span class="px-3 py-1 bg-red-100 text-red-600 rounded-full text-xs font-medium">
                                ${product.stock_quantity} left
                            </span>
                        </div>
                    `).join('');
                    document.getElementById('low-stock-list').innerHTML = lowStockHtml || '<p class="text-gray-500 text-center py-4">No low stock items</p>';

                    // Update notifications
                    updateNotifications(data);

                    // Render charts
                    renderCharts(data);
                    loadSalesChart();
                }
            } catch (error) {
                console.error('Error loading dashboard:', error);
            }
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

        // Render charts
        function renderCharts(data) {
            // Sales chart is handled separately by loadSalesChart()

            // Orders Chart
            const ordersCtx = document.getElementById('ordersChart').getContext('2d');
            const statusData = data.orders_by_status || [];
            
            ordersChart = new Chart(ordersCtx, {
                type: 'bar',
                data: {
                    labels: statusData.map(s => s.status.charAt(0).toUpperCase() + s.status.slice(1)),
                    datasets: [{
                        label: 'Orders',
                        data: statusData.map(s => s.count),
                        backgroundColor: ['#fbbf24', '#3b82f6', '#a855f7', '#10b981', '#ef4444'],
                        borderRadius: 6,
                        borderSkipped: false
                    }]
                },
                options: {
                    responsive: true,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { beginAtZero: true, ticks: { stepSize: 1 } },
                        x: { grid: { display: false } }
                    }
                }
            });
        }

        // ── Dropdowns ───────────────────────────────────────
        function toggleNotifications() {
            const dd = document.getElementById('notif-dropdown');
            const pd = document.getElementById('profile-dropdown');
            pd.classList.add('hidden');
            dd.classList.toggle('hidden');
        }

        function toggleProfile() {
            const dd = document.getElementById('profile-dropdown');
            const nd = document.getElementById('notif-dropdown');
            nd.classList.add('hidden');
            dd.classList.toggle('hidden');
        }

        function closeDropdowns() {
            document.getElementById('notif-dropdown').classList.add('hidden');
            document.getElementById('profile-dropdown').classList.add('hidden');
        }

        // Close dropdowns when clicking outside
        document.addEventListener('click', e => {
            if (!document.getElementById('notif-wrapper').contains(e.target) &&
                !document.getElementById('profile-wrapper').contains(e.target)) {
                closeDropdowns();
            }
        });

        function updateNotifications(data) {
            const pending  = data.pending_orders_count  || 0;
            const lowStock = data.low_stock_count        || 0;
            const total    = pending + lowStock;

            // Badge on bell
            const badge = document.getElementById('notif-badge');
            if (total > 0) {
                badge.textContent = total > 99 ? '99+' : total;
                badge.classList.remove('hidden');
                badge.classList.add('flex');
            } else {
                badge.classList.add('hidden');
                badge.classList.remove('flex');
            }

            document.getElementById('notif-total-badge').textContent = total > 0 ? `${total} new` : 'All clear';

            const items = [];
            if (pending > 0) items.push(`
                <a href="orders.php" class="flex items-start space-x-3 px-4 py-3 hover:bg-gray-50 transition-colors">
                    <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                        <i class="fas fa-clock text-yellow-600 text-sm"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-900">${pending} Pending Order${pending > 1 ? 's' : ''}</p>
                        <p class="text-xs text-gray-500">Awaiting processing</p>
                    </div>
                </a>`);
            if (lowStock > 0) items.push(`
                <a href="products.php" class="flex items-start space-x-3 px-4 py-3 hover:bg-gray-50 transition-colors">
                    <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                        <i class="fas fa-exclamation-triangle text-red-600 text-sm"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-900">${lowStock} Low Stock Product${lowStock > 1 ? 's' : ''}</p>
                        <p class="text-xs text-gray-500">Stock below 10 units</p>
                    </div>
                </a>`);

            document.getElementById('notif-list').innerHTML = items.length
                ? items.join('')
                : '<div class="px-4 py-6 text-center text-gray-400 text-sm"><i class="fas fa-check-circle text-green-400 text-2xl mb-2 block"></i>No new notifications</div>';
        }

        // ── Change Password ──────────────────────────────────
        function openChangePassword() {
            document.getElementById('cp-current').value = '';
            document.getElementById('cp-new').value = '';
            document.getElementById('cp-confirm').value = '';
            document.getElementById('cp-error').classList.add('hidden');
            document.getElementById('cp-success').classList.add('hidden');
            document.getElementById('change-pwd-modal').classList.remove('hidden');
        }

        function closeChangePassword() {
            document.getElementById('change-pwd-modal').classList.add('hidden');
        }

        async function submitChangePassword() {
            const current = document.getElementById('cp-current').value;
            const newPwd  = document.getElementById('cp-new').value;
            const confirm = document.getElementById('cp-confirm').value;
            const errDiv  = document.getElementById('cp-error');
            const btn     = document.getElementById('cp-btn');
            errDiv.classList.add('hidden');
            document.getElementById('cp-success').classList.add('hidden');

            if (!current || !newPwd || !confirm) {
                document.getElementById('cp-error-text').textContent = 'All fields are required.';
                errDiv.classList.remove('hidden');
                return;
            }
            if (newPwd !== confirm) {
                document.getElementById('cp-error-text').textContent = 'New passwords do not match.';
                errDiv.classList.remove('hidden');
                return;
            }
            if (newPwd.length < 6) {
                document.getElementById('cp-error-text').textContent = 'Password must be at least 6 characters.';
                errDiv.classList.remove('hidden');
                return;
            }

            btn.disabled = true;
            btn.textContent = 'Saving...';
            try {
                const res = await fetch('../backend/api/admin-auth.php', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({action: 'change_password', current_password: current, new_password: newPwd})
                });
                const result = await res.json();
                if (result.success) {
                    document.getElementById('cp-success').classList.remove('hidden');
                    document.getElementById('cp-current').value = '';
                    document.getElementById('cp-new').value = '';
                    document.getElementById('cp-confirm').value = '';
                    setTimeout(closeChangePassword, 1800);
                } else {
                    document.getElementById('cp-error-text').textContent = result.error || 'Failed to change password.';
                    errDiv.classList.remove('hidden');
                }
            } catch(e) {
                document.getElementById('cp-error-text').textContent = 'An error occurred.';
                errDiv.classList.remove('hidden');
            } finally {
                btn.disabled = false;
                btn.textContent = 'Save';
            }
        }

        // Close change-pwd modal on backdrop click
        document.getElementById('change-pwd-modal').addEventListener('click', function(e) {
            if (e.target === this) closeChangePassword();
        });

        // Logout
        async function logout() {
            try {
                await fetch('../backend/api/admin-auth.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ action: 'logout' })
                });
                window.location.href = 'login.php';
            } catch (error) {
                console.error('Logout error:', error);
            }
        }

        // Dark mode
        function toggleDarkMode() {
            const isDark = document.documentElement.classList.toggle('dark');
            localStorage.setItem('adminDark', isDark ? '1' : '0');
            document.getElementById('dark-icon').className = isDark ? 'fas fa-sun text-xl' : 'fas fa-moon text-xl';
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            // Set correct icon on load
            const icon = document.getElementById('dark-icon');
            if (document.documentElement.classList.contains('dark')) {
                icon.className = 'fas fa-sun text-xl';
            }
            loadDashboard();
        });
    </script>
</body>
</html>