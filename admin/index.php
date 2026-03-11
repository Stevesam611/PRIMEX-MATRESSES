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
                <a href="reviews.php" class="sidebar-link flex items-center space-x-3 px-4 py-3 text-gray-600 hover:text-primary-600 rounded-lg">
                    <i class="fas fa-star w-5"></i>
                    <span class="font-medium">Reviews</span>
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
            <header class="bg-white shadow-sm sticky top-0 z-10">
                <div class="flex items-center justify-between px-8 py-4">
                    <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
                    <div class="flex items-center space-x-4">
                        <button class="p-2 text-gray-400 hover:text-gray-600 relative">
                            <i class="fas fa-bell text-xl"></i>
                            <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                        </button>
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-primary-100 rounded-full flex items-center justify-center">
                                <span class="text-primary-600 font-semibold"><?php echo substr($_SESSION['admin_name'] ?? 'A', 0, 1); ?></span>
                            </div>
                            <div class="hidden md:block">
                                <div class="font-medium text-gray-900"><?php echo htmlspecialchars($_SESSION['admin_name'] ?? 'Admin'); ?></div>
                                <div class="text-sm text-gray-500"><?php echo htmlspecialchars($_SESSION['admin_role'] ?? 'Admin'); ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

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

        // Initialize
        document.addEventListener('DOMContentLoaded', loadDashboard);
    </script>
</body>
</html>