<?php
/**
 * Primex Mattress & Beddings - Admin Messages
 */

require_once __DIR__ . '/../backend/includes/auth.php';
$auth->requireAdmin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages - Primex Admin</title>
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
                        primary:   { 50: '#eff6ff', 100: '#dbeafe', 500: '#3b82f6', 600: '#2563eb', 700: '#1d4ed8' },
                        secondary: { 50: '#faf5ff', 100: '#f3e8ff', 500: '#a855f7', 600: '#9333ea', 700: '#7c3aed' },
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
        html.dark input::placeholder,html.dark textarea::placeholder{color:#64748b!important}
        html.dark .hover\:bg-gray-50:hover{background-color:#162032!important}
        html.dark .hover\:bg-gray-100:hover{background-color:#334155!important}
        html.dark .hover\:bg-red-50:hover{background-color:#2d1a1a!important}
        html.dark .bg-red-50{background-color:#2d1a1a!important}
        html.dark .shadow-sm{box-shadow:0 1px 3px rgba(0,0,0,.5)!important}
        html.dark .shadow-lg,html.dark .shadow-2xl{box-shadow:0 4px 24px rgba(0,0,0,.6)!important}
        .msg-unread { border-left: 3px solid #2563eb; }
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
            <a href="reviews.php"    class="sidebar-link flex items-center space-x-3 px-4 py-3 text-gray-600 hover:text-primary-600 rounded-lg"><i class="fas fa-star w-5"></i><span class="font-medium">Reviews</span></a>
            <a href="messages.php"   class="sidebar-link active flex items-center space-x-3 px-4 py-3 text-primary-600 rounded-lg">
                <i class="fas fa-envelope w-5"></i>
                <span class="font-medium">Messages</span>
                <span id="unread-badge" class="ml-auto hidden bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded-full"></span>
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
                <h1 class="text-2xl font-bold text-gray-900">Messages</h1>
                <button onclick="toggleDarkMode()" id="dark-toggle" title="Toggle dark mode" class="p-2 text-gray-400 hover:text-gray-600 focus:outline-none">
                    <i id="dark-icon" class="fas fa-moon text-xl"></i>
                </button>
            </div>
        </header>

        <div class="p-8">

            <!-- Summary Cards -->
            <div class="grid grid-cols-3 gap-6 mb-8">
                <div class="bg-white rounded-xl shadow-sm p-6 flex items-center space-x-4">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-envelope text-blue-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm">Total Messages</p>
                        <p class="text-2xl font-bold text-gray-900" id="count-total">-</p>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-6 flex items-center space-x-4 cursor-pointer hover:shadow-md transition-shadow" onclick="setFilter('unread')">
                    <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-envelope text-red-500 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm">Unread</p>
                        <p class="text-2xl font-bold text-gray-900" id="count-unread">-</p>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-6 flex items-center space-x-4 cursor-pointer hover:shadow-md transition-shadow" onclick="setFilter('read')">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-envelope-open text-green-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm">Read</p>
                        <p class="text-2xl font-bold text-gray-900" id="count-read">-</p>
                    </div>
                </div>
            </div>

            <!-- Filters & Search -->
            <div class="bg-white rounded-xl shadow-sm p-5 mb-6 flex flex-wrap items-center gap-4">
                <div class="flex space-x-2">
                    <button onclick="setFilter('all')"    id="filter-all"    class="px-4 py-2 rounded-lg text-sm font-medium transition-colors bg-primary-600 text-white">All</button>
                    <button onclick="setFilter('unread')" id="filter-unread" class="px-4 py-2 rounded-lg text-sm font-medium transition-colors bg-gray-100 text-gray-600 hover:bg-gray-200">Unread</button>
                    <button onclick="setFilter('read')"   id="filter-read"   class="px-4 py-2 rounded-lg text-sm font-medium transition-colors bg-gray-100 text-gray-600 hover:bg-gray-200">Read</button>
                </div>
                <div class="flex-1 min-w-[200px]">
                    <div class="relative">
                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                        <input type="text" id="search-input" placeholder="Search by name, email, subject..."
                            class="w-full pl-9 pr-4 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500"
                            oninput="debouncedSearch()">
                    </div>
                </div>
            </div>

            <!-- Messages List -->
            <div id="messages-container" class="space-y-4">
                <div class="text-center py-12 text-gray-400">
                    <i class="fas fa-spinner fa-spin text-2xl mb-3"></i>
                    <p>Loading messages...</p>
                </div>
            </div>

            <!-- Pagination -->
            <div id="pagination" class="mt-6 flex justify-center space-x-2 hidden"></div>
        </div>
    </main>
</div>

<!-- Toast -->
<div id="toast" class="fixed bottom-6 right-6 bg-gray-900 text-white px-5 py-3 rounded-xl shadow-xl transition-all duration-300 translate-y-20 opacity-0 z-50">
    <span id="toast-message"></span>
</div>

<!-- Delete Confirm Modal -->
<div id="delete-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm p-6">
        <div class="flex items-center space-x-4 mb-4">
            <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center flex-shrink-0">
                <i class="fas fa-trash text-red-600 text-lg"></i>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-gray-900">Delete Message</h3>
                <p class="text-sm text-gray-500">This action cannot be undone.</p>
            </div>
        </div>
        <div class="flex space-x-3 mt-6">
            <button onclick="closeDeleteModal()" class="flex-1 px-4 py-2 border border-gray-200 rounded-lg text-gray-600 hover:bg-gray-50 transition-colors text-sm font-medium">Cancel</button>
            <button onclick="confirmDelete()"    class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors text-sm font-medium">Delete</button>
        </div>
    </div>
</div>

<script>
    let currentFilter  = 'all';
    let currentPage    = 1;
    let deleteTargetId = null;

    let searchTimeout;
    function debouncedSearch() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => { currentPage = 1; loadMessages(); }, 300);
    }

    function setFilter(filter) {
        currentFilter = filter;
        currentPage   = 1;
        ['all','unread','read'].forEach(f => {
            const btn = document.getElementById('filter-' + f);
            btn.className = f === filter
                ? 'px-4 py-2 rounded-lg text-sm font-medium transition-colors bg-primary-600 text-white'
                : 'px-4 py-2 rounded-lg text-sm font-medium transition-colors bg-gray-100 text-gray-600 hover:bg-gray-200';
        });
        loadMessages();
    }

    async function loadMessages() {
        const search = document.getElementById('search-input').value.trim();
        const params = new URLSearchParams({ status: currentFilter, page: currentPage });
        if (search) params.set('search', search);

        try {
            const res    = await fetch(`../backend/api/messages.php?${params}`);
            const result = await res.json();
            if (!result.success) return;

            const { messages, summary, total, page, pages } = result.data;

            document.getElementById('count-total').textContent  = summary.total  || 0;
            document.getElementById('count-unread').textContent = summary.unread || 0;
            document.getElementById('count-read').textContent   = summary.read   || 0;

            // Update sidebar badge
            const badge = document.getElementById('unread-badge');
            if (summary.unread > 0) {
                badge.textContent = summary.unread;
                badge.classList.remove('hidden');
            } else {
                badge.classList.add('hidden');
            }

            const container = document.getElementById('messages-container');
            if (!messages.length) {
                container.innerHTML = `
                    <div class="bg-white rounded-xl shadow-sm p-12 text-center text-gray-400">
                        <i class="fas fa-inbox text-4xl mb-4"></i>
                        <p class="text-lg font-medium">No messages found</p>
                    </div>`;
            } else {
                container.innerHTML = messages.map(m => renderMessage(m)).join('');
            }

            renderPagination(page, pages);
        } catch (e) {
            console.error('Error loading messages:', e);
        }
    }

    function renderMessage(m) {
        const isUnread = m.status === 'unread';
        const date     = new Date(m.created_at).toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' });
        const initials = (m.first_name.charAt(0) + m.last_name.charAt(0)).toUpperCase();

        const statusBadge = isUnread
            ? `<span class="px-2 py-1 bg-blue-100 text-blue-700 text-xs font-medium rounded-full"><i class="fas fa-circle text-[8px] mr-1"></i>Unread</span>`
            : `<span class="px-2 py-1 bg-gray-100 text-gray-500 text-xs font-medium rounded-full"><i class="fas fa-envelope-open text-[10px] mr-1"></i>Read</span>`;

        const toggleBtn = isUnread
            ? `<button onclick="updateMessage(${m.id},'read')" class="px-3 py-1.5 text-xs font-medium border border-green-300 text-green-700 rounded-lg hover:bg-green-50 transition-colors"><i class="fas fa-check mr-1"></i>Mark Read</button>`
            : `<button onclick="updateMessage(${m.id},'unread')" class="px-3 py-1.5 text-xs font-medium border border-blue-300 text-blue-700 rounded-lg hover:bg-blue-50 transition-colors"><i class="fas fa-envelope mr-1"></i>Mark Unread</button>`;

        return `
        <div class="bg-white rounded-xl shadow-sm p-6 ${isUnread ? 'msg-unread' : ''}" id="msg-${m.id}">
            <div class="flex items-start justify-between gap-4">
                <div class="flex items-start space-x-4 flex-1 min-w-0">
                    <div class="w-11 h-11 bg-gradient-to-br from-primary-500 to-secondary-500 rounded-full flex items-center justify-center flex-shrink-0 text-white font-semibold text-sm">
                        ${escHtml(initials)}
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex flex-wrap items-center gap-2 mb-1">
                            <span class="font-semibold text-gray-900">${escHtml(m.first_name + ' ' + m.last_name)}</span>
                            <span class="text-gray-400 text-sm">&bull;</span>
                            <a href="mailto:${escHtml(m.email)}" class="text-primary-600 hover:underline text-sm">${escHtml(m.email)}</a>
                            ${m.phone ? `<span class="text-gray-400 text-sm">&bull;</span><span class="text-gray-500 text-sm"><i class="fas fa-phone text-xs mr-1"></i>${escHtml(m.phone)}</span>` : ''}
                            <span class="text-gray-400 text-sm">&bull;</span>
                            <span class="text-gray-400 text-xs">${date}</span>
                        </div>
                        <div class="flex items-center gap-2 mb-3">
                            <span class="text-xs font-semibold uppercase tracking-wide text-gray-500 bg-gray-100 px-2 py-0.5 rounded">${escHtml(m.subject)}</span>
                            ${statusBadge}
                        </div>
                        <p class="text-gray-700 text-sm leading-relaxed">${escHtml(m.message)}</p>
                    </div>
                </div>
                <div class="flex items-center space-x-2 flex-shrink-0">
                    ${toggleBtn}
                    <button onclick="openDeleteModal(${m.id})" class="px-3 py-1.5 text-xs font-medium border border-red-200 text-red-600 rounded-lg hover:bg-red-50 transition-colors">
                        <i class="fas fa-trash mr-1"></i>Delete
                    </button>
                </div>
            </div>
        </div>`;
    }

    function renderPagination(page, pages) {
        const el = document.getElementById('pagination');
        if (pages <= 1) { el.classList.add('hidden'); return; }
        el.classList.remove('hidden');
        let html = '';
        for (let i = 1; i <= pages; i++) {
            html += `<button onclick="goToPage(${i})"
                class="w-9 h-9 rounded-lg text-sm font-medium transition-colors ${i === page ? 'bg-primary-600 text-white' : 'bg-white text-gray-600 hover:bg-gray-50 shadow-sm'}">${i}</button>`;
        }
        el.innerHTML = html;
    }

    function goToPage(p) {
        currentPage = p;
        loadMessages();
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    async function updateMessage(id, action) {
        try {
            const res    = await fetch('../backend/api/messages.php', {
                method:  'PUT',
                headers: { 'Content-Type': 'application/json' },
                body:    JSON.stringify({ id, action })
            });
            const result = await res.json();
            if (result.success) {
                showToast(action === 'read' ? 'Marked as read' : 'Marked as unread');
                loadMessages();
            } else {
                showToast(result.error || 'Failed to update', 'error');
            }
        } catch (e) {
            showToast('An error occurred', 'error');
        }
    }

    function openDeleteModal(id) {
        deleteTargetId = id;
        document.getElementById('delete-modal').classList.remove('hidden');
    }

    function closeDeleteModal() {
        deleteTargetId = null;
        document.getElementById('delete-modal').classList.add('hidden');
    }

    async function confirmDelete() {
        if (!deleteTargetId) return;
        closeDeleteModal();
        try {
            const res    = await fetch(`../backend/api/messages.php?id=${deleteTargetId}`, { method: 'DELETE' });
            const result = await res.json();
            if (result.success) {
                showToast('Message deleted');
                loadMessages();
            } else {
                showToast(result.error || 'Failed to delete', 'error');
            }
        } catch (e) {
            showToast('An error occurred', 'error');
        }
    }

    function showToast(message) {
        const toast = document.getElementById('toast');
        document.getElementById('toast-message').textContent = message;
        toast.classList.remove('translate-y-20', 'opacity-0');
        setTimeout(() => toast.classList.add('translate-y-20', 'opacity-0'), 3000);
    }

    function escHtml(str) {
        return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }

    function toggleDarkMode() {
        const isDark = document.documentElement.classList.toggle('dark');
        localStorage.setItem('adminDark', isDark ? '1' : '0');
        document.getElementById('dark-icon').className = isDark ? 'fas fa-sun text-xl' : 'fas fa-moon text-xl';
    }

    async function logout() {
        await fetch('../backend/api/admin-auth.php', {
            method:  'POST',
            headers: { 'Content-Type': 'application/json' },
            body:    JSON.stringify({ action: 'logout' })
        });
        window.location.href = 'login.php';
    }

    document.addEventListener('DOMContentLoaded', () => {
        if (document.documentElement.classList.contains('dark')) {
            document.getElementById('dark-icon').className = 'fas fa-sun text-xl';
        }
        loadMessages();
    });
</script>
</body>
</html>
