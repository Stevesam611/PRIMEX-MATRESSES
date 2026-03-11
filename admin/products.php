<?php
/**
 * Primex Mattress & Beddings - Admin Products
 */

require_once __DIR__ . '/../backend/includes/auth.php';
$auth->requireAdmin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products - Primex Admin</title>
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
                <a href="products.php" class="sidebar-link active flex items-center space-x-3 px-4 py-3 text-primary-600 rounded-lg">
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
            <header class="bg-white shadow-sm sticky top-0 z-10">
                <div class="flex items-center justify-between px-8 py-4">
                    <h1 class="text-2xl font-bold text-gray-900">Products</h1>
                    <div class="flex items-center space-x-3">
                        <button onclick="toggleDarkMode()" id="dark-toggle" title="Toggle dark mode" class="p-2 text-gray-400 hover:text-gray-600 focus:outline-none">
                            <i id="dark-icon" class="fas fa-moon text-xl"></i>
                        </button>
                        <button onclick="openModal()" class="bg-primary-600 text-white px-4 py-2 rounded-lg hover:bg-primary-700 transition-colors flex items-center">
                            <i class="fas fa-plus mr-2"></i>Add Product
                        </button>
                    </div>
                </div>
            </header>

            <div class="p-8">
                <!-- Filters -->
                <div class="bg-white rounded-xl shadow-sm p-4 mb-6 flex flex-wrap items-center gap-4">
                    <div class="flex-1 min-w-[200px]">
                        <input type="text" id="search-input" placeholder="Search products..." 
                            class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500"
                            onkeyup="debounceSearch()">
                    </div>
                    <select id="category-filter" onchange="loadProducts()" class="px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500">
                        <option value="">All Categories</option>
                    </select>
                    <select id="status-filter" onchange="loadProducts()" class="px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500">
                        <option value="">All Status</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>

                <!-- Products Table -->
                <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Product</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Category</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Price</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Stock</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Status</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="products-table">
                            <!-- Loaded dynamically -->
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div id="pagination" class="mt-6 flex justify-center">
                    <!-- Loaded dynamically -->
                </div>
            </div>
        </main>
    </div>

    <!-- Add/Edit Product Modal -->
    <div id="product-modal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                <h2 class="text-xl font-semibold" id="modal-title">Add Product</h2>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <form id="product-form" onsubmit="saveProduct(event)" class="p-6">
                <input type="hidden" id="product-id">
                <div class="grid md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Product Name *</label>
                        <input type="text" id="product-name" required class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">SKU</label>
                        <input type="text" id="product-sku" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500">
                    </div>
                </div>
                <div class="grid md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Category *</label>
                        <select id="product-category" required class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500">
                            <!-- Loaded dynamically -->
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Stock Quantity *</label>
                        <input type="number" id="product-stock" required min="0" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500">
                    </div>
                </div>
                <div class="grid md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Price *</label>
                        <input type="number" id="product-price" required min="0" step="0.01" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Discount Price</label>
                        <input type="number" id="product-discount" min="0" step="0.01" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500">
                    </div>
                </div>
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Product Images</label>
                    <div id="images-grid" class="flex flex-wrap gap-3 mb-3 min-h-[40px]">
                        <p class="text-gray-400 text-sm italic">No images uploaded yet</p>
                    </div>
                    <input type="file" id="image-file-input" accept="image/*" class="hidden" onchange="uploadImage(this)">
                    <button type="button" onclick="document.getElementById('image-file-input').click()"
                        class="px-4 py-2 bg-gray-100 border border-gray-200 rounded-lg hover:bg-gray-200 text-sm font-medium flex items-center">
                        <i class="fas fa-upload mr-2"></i>Add Image
                    </button>
                    <p id="upload-status" class="text-xs text-gray-500 mt-2">Max 5MB · JPG, PNG, WEBP, GIF · First image is main by default</p>
                </div>
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Short Description</label>
                    <input type="text" id="product-short-desc" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500">
                </div>
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea id="product-description" rows="4" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500"></textarea>
                </div>
                <div class="flex items-center space-x-4 mb-6">
                    <label class="flex items-center">
                        <input type="checkbox" id="product-featured" class="w-4 h-4 text-primary-600 rounded">
                        <span class="ml-2 text-gray-700">Featured Product</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" id="product-active" checked class="w-4 h-4 text-primary-600 rounded">
                        <span class="ml-2 text-gray-700">Active</span>
                    </label>
                </div>
                <div class="flex justify-end space-x-4">
                    <button type="button" onclick="closeModal()" class="px-6 py-2 border border-gray-200 rounded-lg hover:bg-gray-50">Cancel</button>
                    <button type="submit" class="px-6 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700">Save Product</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        let currentPage = 1;
        let categories = [];
        let uploadedImages = []; // [{url, isMain}]

        function renderImagesGrid() {
            const grid = document.getElementById('images-grid');
            if (uploadedImages.length === 0) {
                grid.innerHTML = '<p class="text-gray-400 text-sm italic">No images uploaded yet</p>';
                return;
            }
            grid.innerHTML = uploadedImages.map((img, i) => `
                <div class="relative w-24 h-24 rounded-lg overflow-hidden border-2 ${img.isMain ? 'border-blue-500' : 'border-gray-200'} group flex-shrink-0">
                    <img src="${img.url}" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col items-center justify-center gap-1 p-1">
                        ${!img.isMain
                            ? `<button type="button" onclick="setMainImage(${i})" class="text-xs bg-white text-blue-600 px-2 py-0.5 rounded font-medium w-full text-center">Set Main</button>`
                            : `<span class="text-xs bg-blue-600 text-white px-2 py-0.5 rounded font-medium w-full text-center">Main</span>`
                        }
                        <button type="button" onclick="removeImage(${i})" class="text-xs bg-red-500 text-white px-2 py-0.5 rounded font-medium w-full text-center">Remove</button>
                    </div>
                    ${img.isMain ? '<span class="absolute top-1 left-1 bg-blue-600 text-white text-xs px-1 rounded leading-4">✓</span>' : ''}
                </div>
            `).join('');
        }

        function setMainImage(index) {
            uploadedImages = uploadedImages.map((img, i) => ({ ...img, isMain: i === index }));
            renderImagesGrid();
        }

        function removeImage(index) {
            uploadedImages.splice(index, 1);
            if (uploadedImages.length > 0 && !uploadedImages.some(img => img.isMain)) {
                uploadedImages[0].isMain = true;
            }
            renderImagesGrid();
        }

        // Load categories
        async function loadCategories() {
            try {
                const response = await fetch('../backend/api/categories.php');
                const result = await response.json();
                if (result.success) {
                    categories = result.data;
                    const filterOptions = categories.map(c => `<option value="${c.slug}">${c.name}</option>`).join('');
                    const formOptions = categories.map(c => `<option value="${c.id}">${c.name}</option>`).join('');
                    document.getElementById('category-filter').innerHTML = '<option value="">All Categories</option>' + filterOptions;
                    document.getElementById('product-category').innerHTML = formOptions;
                }
            } catch (error) {
                console.error('Error loading categories:', error);
            }
        }

        // Load products
        async function loadProducts() {
            try {
                let url = `../backend/api/products.php?page=${currentPage}&limit=10`;
                const search = document.getElementById('search-input').value;
                const category = document.getElementById('category-filter').value;
                
                if (search) url += `&search=${encodeURIComponent(search)}`;
                if (category) url += `&category=${encodeURIComponent(category)}`;

                const response = await fetch(url);
                const result = await response.json();

                if (result.success) {
                    displayProducts(result.data.products);
                    displayPagination(result.data.pagination);
                }
            } catch (error) {
                console.error('Error loading products:', error);
            }
        }

        // Display products
        function displayProducts(products) {
            const tbody = document.getElementById('products-table');
            tbody.innerHTML = products.map(p => `
                <tr class="border-b border-gray-50 hover:bg-gray-50">
                    <td class="px-6 py-4">
                        <div class="flex items-center space-x-3">
                            <img src="${p.main_image || 'https://via.placeholder.com/50'}" alt="" class="w-12 h-12 object-cover rounded-lg">
                            <div>
                                <p class="font-medium">${p.name}</p>
                                <p class="text-sm text-gray-500">${p.sku || 'No SKU'}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">${p.category_name || '-'}</td>
                    <td class="px-6 py-4">
                        ${p.discount_price ? `
                            <span class="font-medium">KSh ${parseFloat(p.discount_price).toFixed(2)}</span>
                            <span class="text-sm text-gray-400 line-through">KSh ${parseFloat(p.price).toFixed(2)}</span>
                        ` : `<span class="font-medium">KSh ${parseFloat(p.price).toFixed(2)}</span>`}
                    </td>
                    <td class="px-6 py-4">
                        <span class="${p.stock_quantity < 10 ? 'text-red-600' : 'text-gray-900'}">${p.stock_quantity}</span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 rounded-full text-xs font-medium ${p.is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700'}">
                            ${p.is_active ? 'Active' : 'Inactive'}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center space-x-2">
                            <button onclick="editProduct(${p.id})" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button onclick="deleteProduct(${p.id})" class="p-2 text-red-600 hover:bg-red-50 rounded-lg">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `).join('');
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
            loadProducts();
        }

        // Upload image
        async function uploadImage(input) {
            const file = input.files[0];
            if (!file) return;
            input.value = ''; // reset so same file can be re-selected

            const statusEl = document.getElementById('upload-status');
            statusEl.textContent = 'Uploading...';
            statusEl.className = 'text-xs text-gray-500 mt-2';

            const formData = new FormData();
            formData.append('image', file);

            try {
                const response = await fetch('../backend/api/upload.php', {
                    method: 'POST',
                    body: formData
                });
                const result = await response.json();

                if (result.success) {
                    const isFirst = uploadedImages.length === 0;
                    uploadedImages.push({ url: result.data.url, isMain: isFirst });
                    renderImagesGrid();
                    statusEl.textContent = 'Image uploaded successfully';
                } else {
                    statusEl.textContent = result.error || 'Upload failed';
                    statusEl.className = 'text-xs text-red-500 mt-2';
                }
            } catch (e) {
                statusEl.textContent = 'Upload failed';
                statusEl.className = 'text-xs text-red-500 mt-2';
            }
        }

        // Modal functions
        function openModal() {
            document.getElementById('product-modal').classList.remove('hidden');
            document.getElementById('modal-title').textContent = 'Add Product';
            document.getElementById('product-form').reset();
            document.getElementById('product-id').value = '';
            document.getElementById('upload-status').textContent = 'Max 5MB · JPG, PNG, WEBP, GIF · First image is main by default';
            document.getElementById('upload-status').className = 'text-xs text-gray-500 mt-2';
            uploadedImages = [];
            renderImagesGrid();
        }

        function closeModal() {
            document.getElementById('product-modal').classList.add('hidden');
        }

        // Edit product
        async function editProduct(id) {
            try {
                const response = await fetch(`../backend/api/products.php?id=${id}`);
                const result = await response.json();
                
                if (result.success) {
                    const p = result.data;
                    document.getElementById('product-id').value = p.id;
                    document.getElementById('product-name').value = p.name;
                    document.getElementById('product-sku').value = p.sku || '';
                    document.getElementById('product-category').value = p.category_id;
                    document.getElementById('product-stock').value = p.stock_quantity;
                    document.getElementById('product-price').value = p.price;
                    document.getElementById('product-discount').value = p.discount_price || '';
                    document.getElementById('product-short-desc').value = p.short_description || '';
                    document.getElementById('product-description').value = p.description || '';

                    // Load existing images
                    if (p.images && p.images.length > 0) {
                        uploadedImages = p.images.map(img => ({ url: img.image_url, isMain: !!img.is_primary }));
                    } else if (p.main_image) {
                        uploadedImages = [{ url: p.main_image, isMain: true }];
                    } else {
                        uploadedImages = [];
                    }
                    if (uploadedImages.length > 0 && !uploadedImages.some(img => img.isMain)) {
                        uploadedImages[0].isMain = true;
                    }
                    renderImagesGrid();
                    document.getElementById('upload-status').textContent = 'Max 5MB · JPG, PNG, WEBP, GIF · First image is main by default';
                    document.getElementById('upload-status').className = 'text-xs text-gray-500 mt-2';
                    document.getElementById('product-featured').checked = p.is_featured;
                    document.getElementById('product-active').checked = p.is_active;
                    
                    document.getElementById('modal-title').textContent = 'Edit Product';
                    document.getElementById('product-modal').classList.remove('hidden');
                }
            } catch (error) {
                console.error('Error loading product:', error);
            }
        }

        // Save product
        async function saveProduct(event) {
            event.preventDefault();
            
            const id = document.getElementById('product-id').value;
            const mainImg = uploadedImages.find(img => img.isMain) || uploadedImages[0] || null;
            const data = {
                name: document.getElementById('product-name').value,
                sku: document.getElementById('product-sku').value,
                category_id: document.getElementById('product-category').value,
                stock_quantity: parseInt(document.getElementById('product-stock').value),
                price: parseFloat(document.getElementById('product-price').value),
                discount_price: document.getElementById('product-discount').value || null,
                short_description: document.getElementById('product-short-desc').value,
                description: document.getElementById('product-description').value,
                main_image: mainImg ? mainImg.url : null,
                images: uploadedImages.map(img => ({ url: img.url, isMain: img.isMain })),
                is_featured: document.getElementById('product-featured').checked,
                is_active: document.getElementById('product-active').checked
            };

            try {
                const method = id ? 'PUT' : 'POST';
                if (id) data.id = parseInt(id);
                
                const response = await fetch('../backend/api/products.php', {
                    method: method,
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(data)
                });

                const result = await response.json();
                if (result.success) {
                    closeModal();
                    loadProducts();
                    alert(id ? 'Product updated successfully' : 'Product created successfully');
                } else {
                    alert(result.error || 'Failed to save product');
                }
            } catch (error) {
                console.error('Error saving product:', error);
                alert('Failed to save product');
            }
        }

        // Delete product
        async function deleteProduct(id) {
            if (!confirm('Are you sure you want to delete this product?')) return;
            
            try {
                const response = await fetch(`../backend/api/products.php?id=${id}`, {
                    method: 'DELETE'
                });

                const result = await response.json();
                if (result.success) {
                    loadProducts();
                    alert('Product deleted successfully');
                } else {
                    alert(result.error || 'Failed to delete product');
                }
            } catch (error) {
                console.error('Error deleting product:', error);
                alert('Failed to delete product');
            }
        }

        // Debounce search
        let searchTimeout;
        function debounceSearch() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                currentPage = 1;
                loadProducts();
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
            loadCategories();
            loadProducts();
        });
    </script>
</body>
</html>