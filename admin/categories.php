<?php
/**
 * Primex Mattress & Beddings - Admin Categories
 */

require_once __DIR__ . '/../backend/includes/auth.php';
$auth->requireAdmin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categories - Primex Admin</title>
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
                <a href="orders.php" class="sidebar-link flex items-center space-x-3 px-4 py-3 text-gray-600 hover:text-primary-600 rounded-lg">
                    <i class="fas fa-shopping-cart w-5"></i>
                    <span class="font-medium">Orders</span>
                </a>
                <a href="categories.php" class="sidebar-link active flex items-center space-x-3 px-4 py-3 text-primary-600 rounded-lg">
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
                    <h1 class="text-2xl font-bold text-gray-900">Categories</h1>
                    <button onclick="openModal()" class="bg-primary-600 text-white px-4 py-2 rounded-lg hover:bg-primary-700 transition-colors flex items-center">
                        <i class="fas fa-plus mr-2"></i>Add Category
                    </button>
                </div>
            </header>

            <div class="p-8">
                <!-- Categories Grid -->
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6" id="categories-grid">
                    <!-- Loaded dynamically -->
                </div>
            </div>
        </main>
    </div>

    <!-- Add/Edit Category Modal -->
    <div id="category-modal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-md w-full">
            <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                <h2 class="text-xl font-semibold" id="modal-title">Add Category</h2>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <form id="category-form" onsubmit="saveCategory(event)" class="p-6">
                <input type="hidden" id="category-id">
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Category Name *</label>
                    <input type="text" id="category-name" required class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500">
                </div>
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea id="category-description" rows="3" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500"></textarea>
                </div>
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Sort Order</label>
                    <input type="number" id="category-sort" min="0" value="0" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500">
                </div>
                <div class="flex items-center mb-6">
                    <input type="checkbox" id="category-active" checked class="w-4 h-4 text-primary-600 rounded">
                    <label for="category-active" class="ml-2 text-gray-700">Active</label>
                </div>
                <div class="flex justify-end space-x-4">
                    <button type="button" onclick="closeModal()" class="px-6 py-2 border border-gray-200 rounded-lg hover:bg-gray-50">Cancel</button>
                    <button type="submit" class="px-6 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700">Save Category</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Load categories
        async function loadCategories() {
            try {
                const response = await fetch('../backend/api/categories.php?admin=1');
                const result = await response.json();

                if (result.success) {
                    displayCategories(result.data);
                }
            } catch (error) {
                console.error('Error loading categories:', error);
            }
        }

        // Display categories
        function displayCategories(categories) {
            const container = document.getElementById('categories-grid');
            container.innerHTML = categories.map(c => `
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <div class="flex items-start justify-between mb-4">
                        <div class="w-16 h-16 bg-gradient-to-br from-primary-100 to-secondary-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-folder text-primary-600 text-2xl"></i>
                        </div>
                        <div class="flex items-center space-x-2">
                            <button onclick="editCategory(${c.id})" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button onclick="deleteCategory(${c.id})" class="p-2 text-red-600 hover:bg-red-50 rounded-lg">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                    <h3 class="font-semibold text-lg mb-1">${c.name}</h3>
                    <p class="text-gray-500 text-sm mb-4">${c.description || 'No description'}</p>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">
                            <i class="fas fa-box mr-1"></i>${c.product_count} products
                        </span>
                        <span class="px-2 py-1 rounded-full text-xs font-medium ${c.is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700'}">
                            ${c.is_active ? 'Active' : 'Inactive'}
                        </span>
                    </div>
                </div>
            `).join('');
        }

        // Modal functions
        function openModal() {
            document.getElementById('category-modal').classList.remove('hidden');
            document.getElementById('modal-title').textContent = 'Add Category';
            document.getElementById('category-form').reset();
            document.getElementById('category-id').value = '';
        }

        function closeModal() {
            document.getElementById('category-modal').classList.add('hidden');
        }

        // Edit category
        async function editCategory(id) {
            try {
                const response = await fetch(`../backend/api/categories.php?id=${id}`);
                const result = await response.json();
                
                if (result.success) {
                    const c = result.data;
                    document.getElementById('category-id').value = c.id;
                    document.getElementById('category-name').value = c.name;
                    document.getElementById('category-description').value = c.description || '';
                    document.getElementById('category-sort').value = c.sort_order;
                    document.getElementById('category-active').checked = c.is_active;
                    
                    document.getElementById('modal-title').textContent = 'Edit Category';
                    document.getElementById('category-modal').classList.remove('hidden');
                }
            } catch (error) {
                console.error('Error loading category:', error);
            }
        }

        // Save category
        async function saveCategory(event) {
            event.preventDefault();
            
            const id = document.getElementById('category-id').value;
            const data = {
                name: document.getElementById('category-name').value,
                description: document.getElementById('category-description').value,
                sort_order: parseInt(document.getElementById('category-sort').value),
                is_active: document.getElementById('category-active').checked
            };

            try {
                const method = id ? 'PUT' : 'POST';
                if (id) data.id = parseInt(id);
                
                const response = await fetch('../backend/api/categories.php', {
                    method: method,
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(data)
                });

                const result = await response.json();
                if (result.success) {
                    closeModal();
                    loadCategories();
                    alert(id ? 'Category updated successfully' : 'Category created successfully');
                } else {
                    alert(result.error || 'Failed to save category');
                }
            } catch (error) {
                console.error('Error saving category:', error);
                alert('Failed to save category');
            }
        }

        // Delete category
        async function deleteCategory(id) {
            if (!confirm('Are you sure you want to delete this category?')) return;
            
            try {
                const response = await fetch(`../backend/api/categories.php?id=${id}`, {
                    method: 'DELETE'
                });

                const result = await response.json();
                if (result.success) {
                    loadCategories();
                    alert('Category deleted successfully');
                } else {
                    alert(result.error || 'Failed to delete category');
                }
            } catch (error) {
                console.error('Error deleting category:', error);
                alert('Failed to delete category');
            }
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
        document.addEventListener('DOMContentLoaded', loadCategories);
    </script>
</body>
</html>