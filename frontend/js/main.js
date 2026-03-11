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
    
    if (toast && toastMessage) {
        toastMessage.textContent = message;
        toast.classList.remove('translate-y-20', 'opacity-0');
        
        setTimeout(() => {
            toast.classList.add('translate-y-20', 'opacity-0');
        }, 3000);
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
        window.location.href = `pages/products.html?search=${encodeURIComponent(searchInput.value.trim())}`;
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

// Initialize on DOM Ready
document.addEventListener('DOMContentLoaded', () => {
    initScrollAnimations();
    initNavbarScroll();
    
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