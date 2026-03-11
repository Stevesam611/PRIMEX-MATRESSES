/**
 * Primex Mattress & Beddings - Main JavaScript
 */

// API Base URL
const API_BASE_URL = '/PRIMEX-MATRESSES/backend/api';

// Cart Functions
async function getCart() {
    try {
        const response = await fetch(`${API_BASE_URL}/cart.php`);
        const result = await response.json();
        return result.success ? result.data : { items: [], totals: { item_count: 0 } };
    } catch (error) {
        console.error('Error fetching cart:', error);
        return { items: [], totals: { item_count: 0 } };
    }
}

async function addToCart(productId, quantity = 1) {
    try {
        const response = await fetch(`${API_BASE_URL}/cart.php`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ product_id: productId, quantity: quantity })
        });
        
        const result = await response.json();
        
        if (result.success) {
            showToast('Item added to cart');
            updateCartCount(result.data.totals.item_count);
            return result.data;
        } else {
            showToast(result.error || 'Failed to add item', 'error');
        }
    } catch (error) {
        console.error('Error adding to cart:', error);
        showToast('Failed to add item to cart', 'error');
    }
}

async function updateCartItem(itemId, quantity) {
    try {
        const response = await fetch(`${API_BASE_URL}/cart.php`, {
            method: 'PUT',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ item_id: itemId, quantity: quantity })
        });
        
        const result = await response.json();
        
        if (result.success) {
            updateCartCount(result.data.totals.item_count);
            return result.data;
        }
    } catch (error) {
        console.error('Error updating cart:', error);
    }
}

async function removeFromCart(itemId) {
    try {
        const response = await fetch(`${API_BASE_URL}/cart.php?item_id=${itemId}`, {
            method: 'DELETE'
        });
        
        const result = await response.json();
        
        if (result.success) {
            showToast('Item removed from cart');
            updateCartCount(result.data.totals.item_count);
            return result.data;
        }
    } catch (error) {
        console.error('Error removing from cart:', error);
    }
}

async function updateCartCount(count) {
    const cartCountEl = document.getElementById('cart-count');
    if (cartCountEl) {
        if (count > 0) {
            cartCountEl.textContent = count;
            cartCountEl.classList.remove('hidden');
        } else {
            cartCountEl.classList.add('hidden');
        }
    }
}

// Toast Notification
function showToast(message, type = 'success') {
    const toast = document.getElementById('toast');
    const toastMessage = document.getElementById('toast-message');
    const toastIcon = document.getElementById('toast-icon');

    if (toast && toastMessage) {
        toastMessage.textContent = message;
        if (toastIcon) {
            toastIcon.className = type === 'error'
                ? 'fas fa-exclamation-circle text-red-400 text-lg'
                : 'fas fa-check-circle text-green-400 text-lg';
        }
        toast.className = toast.className.replace(/bg-\w+-\d+/g, '');
        toast.classList.add(type === 'error' ? 'bg-red-900' : 'bg-gray-900');
        toast.classList.remove('translate-y-20', 'opacity-0');

        clearTimeout(window._toastTimer);
        window._toastTimer = setTimeout(() => {
            toast.classList.add('translate-y-20', 'opacity-0');
        }, 3500);
    }
}

// Navigation Functions
function toggleSearch() {
    const searchBar = document.getElementById('search-bar');
    if (searchBar) {
        searchBar.classList.toggle('hidden');
        if (!searchBar.classList.contains('hidden')) {
            document.getElementById('search-input')?.focus();
        }
    }
}

function toggleMobileMenu() {
    const mobileMenu = document.getElementById('mobile-menu');
    if (mobileMenu) {
        mobileMenu.classList.toggle('hidden');
    }
}

function performSearch() {
    const searchInput = document.getElementById('search-input');
    if (searchInput && searchInput.value.trim()) {
        window.location.href = `/PRIMEX-MATRESSES/frontend/pages/products.html?search=${encodeURIComponent(searchInput.value.trim())}`;
    }
}

// Newsletter Subscription
function subscribeNewsletter(event) {
    event.preventDefault();
    const email = event.target.querySelector('input[type="email"]').value;
    showToast('Thank you for subscribing! Check your email for the discount code.');
    event.target.reset();
}

// Scroll Animations
function initScrollAnimations() {
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
            }
        });
    }, observerOptions);

    document.querySelectorAll('.section-fade').forEach(el => {
        observer.observe(el);
    });
}

// Navbar Scroll Effect
function initNavbarScroll() {
    const navbar = document.getElementById('navbar');
    if (navbar) {
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                navbar.classList.add('shadow-md');
            } else {
                navbar.classList.remove('shadow-md');
            }
        });
    }
}

// Format Price
function formatPrice(price) {
    return 'KSh ' + parseFloat(price).toFixed(2);
}

// Get URL Parameters
function getUrlParam(param) {
    const urlParams = new URLSearchParams(window.location.search);
    return urlParams.get(param);
}

// Debounce Function
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Scroll to Top
function initScrollToTop() {
    const btn = document.getElementById('scroll-top-btn');
    if (!btn) return;
    window.addEventListener('scroll', () => {
        if (window.scrollY > 400) {
            btn.classList.remove('opacity-0', 'pointer-events-none');
            btn.classList.add('opacity-100');
        } else {
            btn.classList.add('opacity-0', 'pointer-events-none');
            btn.classList.remove('opacity-100');
        }
    });
    btn.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));
}

// Dismiss announcement bar
function dismissPromo() {
    const bar = document.getElementById('promo-bar');
    if (!bar) return;
    bar.style.transition = 'max-height 0.3s ease, padding 0.3s ease, opacity 0.3s ease';
    bar.style.maxHeight = bar.scrollHeight + 'px';
    bar.style.overflow = 'hidden';
    requestAnimationFrame(() => {
        bar.style.maxHeight = '0';
        bar.style.paddingTop = '0';
        bar.style.paddingBottom = '0';
        bar.style.opacity = '0';
    });
    sessionStorage.setItem('promoDismissed', '1');
}

// Initialize on DOM Ready
document.addEventListener('DOMContentLoaded', () => {
    initScrollAnimations();
    initNavbarScroll();
    initScrollToTop();

    // Restore promo bar dismissal
    if (sessionStorage.getItem('promoDismissed')) {
        const bar = document.getElementById('promo-bar');
        if (bar) bar.remove();
    }
    
    // Search input enter key
    const searchInput = document.getElementById('search-input');
    if (searchInput) {
        searchInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                performSearch();
            }
        });
    }
});

// ── User Account (shared across all pages) ───────────────────────────────
const USER_AUTH_API = '/PRIMEX-MATRESSES/backend/api/user-auth.php';

async function initUserAccount() {
    const dropdown = document.getElementById('account-dropdown');
    const label    = document.getElementById('account-label');
    if (!dropdown) return;

    try {
        const res    = await fetch(`${USER_AUTH_API}?action=check`);
        const result = await res.json();

        if (result.success && result.data.logged_in) {
            const user = result.data;
            const name = user.first_name || user.email;
            if (label) label.textContent = name;

            dropdown.innerHTML = `
                <div class="px-4 py-2.5 border-b border-gray-100">
                    <p class="text-xs text-gray-400">Signed in as</p>
                    <p class="text-sm font-semibold text-gray-800 truncate">${escHtml(user.email)}</p>
                </div>
                <a href="my-orders.html" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                    <i class="fas fa-box w-4 text-primary-600"></i> My Orders
                </a>
                <a href="change-password.html" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                    <i class="fas fa-key w-4 text-primary-600"></i> Change Password
                </a>
                <div class="border-t border-gray-100 my-1"></div>
                <button onclick="userSignOut()" class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-colors">
                    <i class="fas fa-sign-out-alt w-4"></i> Sign Out
                </button>`;
        } else {
            dropdown.innerHTML = `
                <a href="login.html" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                    <i class="fas fa-sign-in-alt w-4 text-primary-600"></i> Sign In
                </a>
                <a href="login.html?tab=register" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                    <i class="fas fa-user-plus w-4 text-primary-600"></i> Create Account
                </a>`;
        }
    } catch(e) { console.error('Account check failed:', e); }
}

function toggleAccountMenu() {
    document.getElementById('account-dropdown')?.classList.toggle('hidden');
}

async function userSignOut() {
    await fetch(USER_AUTH_API, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ action: 'logout' })
    });
    window.location.href = 'products.html';
}

function escHtml(str) {
    return String(str || '').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

// Close account dropdown when clicking outside
document.addEventListener('click', e => {
    const wrap = document.getElementById('account-menu-wrap');
    if (wrap && !wrap.contains(e.target)) {
        document.getElementById('account-dropdown')?.classList.add('hidden');
    }
});

// Export functions for use in other scripts
window.Primex = {
    addToCart,
    updateCartItem,
    removeFromCart,
    getCart,
    showToast,
    formatPrice,
    getUrlParam,
    debounce
};