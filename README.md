# Primex Mattress & Beddings - E-Commerce Platform

A modern, full-featured e-commerce website for a mattress and bedding brand built with HTML, JavaScript, TailwindCSS, PHP, and PostgreSQL.

![Primex Logo](frontend/images/logo.png)

## Features

### Customer Features
- **Homepage** - Hero banner, featured products, categories, testimonials, newsletter signup
- **Product Listing** - Grid view with filters (price, category), search functionality
- **Product Details** - Images, price, description, specifications, reviews, add to cart
- **Shopping Cart** - Add/remove items, quantity adjustment, cart total calculation
- **Checkout** - Multi-step checkout with shipping and payment information
- **Contact Page** - Contact form, store information, location map, FAQ section

### Admin Features
- **Dashboard** - Overview with sales charts, order statistics, low stock alerts
- **Product Management** - Add, edit, delete products with image upload
- **Order Management** - View orders, update order status
- **Category Management** - Create, edit, delete product categories
- **Analytics** - Sales charts, order statistics, top products

## Tech Stack

### Frontend
- HTML5
- JavaScript (ES6+)
- TailwindCSS
- Font Awesome Icons
- Chart.js (for admin analytics)

### Backend
- PHP 7.4+
- PostgreSQL Database
- PDO for database connections
- Session-based authentication

## Project Structure

```
primex-mattress/
├── frontend/               # Customer-facing website
│   ├── index.html         # Homepage
│   ├── css/               # Stylesheets
│   ├── js/                # JavaScript files
│   │   └── main.js        # Main JavaScript functionality
│   ├── images/            # Image assets
│   └── pages/             # Additional pages
│       ├── products.html      # Product listing
│       ├── product-detail.html # Product details
│       ├── cart.html          # Shopping cart
│       ├── checkout.html      # Checkout process
│       ├── contact.html       # Contact page
│       └── order-confirmation.html # Order confirmation
│
├── backend/               # Backend API
│   ├── api/               # API endpoints
│   │   ├── products.php       # Products API
│   │   ├── categories.php     # Categories API
│   │   ├── cart.php           # Shopping cart API
│   │   ├── orders.php         # Orders API
│   │   ├── admin-auth.php     # Admin authentication API
│   │   └── dashboard.php      # Dashboard statistics API
│   ├── includes/          # PHP includes
│   │   ├── config.php         # Configuration
│   │   ├── database.php       # Database connection
│   │   └── auth.php           # Authentication handler
│   └── uploads/           # Uploaded files
│
├── admin/                 # Admin dashboard
│   ├── login.php          # Admin login
│   ├── index.php          # Dashboard
│   ├── products.php       # Product management
│   ├── orders.php         # Order management
│   ├── categories.php     # Category management
│   ├── customers.php      # Customer management
│   └── reviews.php        # Reviews management
│
├── database/              # Database files
│   └── schema.sql         # Database schema
│
└── README.md              # This file
```

## Installation

### Prerequisites
- PHP 7.4 or higher
- PostgreSQL 12 or higher
- Web server (Apache/Nginx)

### Step 1: Clone or Download
```bash
cd /var/www/html
git clone <repository-url> primex-mattress
cd primex-mattress
```

### Step 2: Database Setup

1. Create a PostgreSQL database:
```bash
sudo -u postgres psql
CREATE DATABASE primex_mattress;
CREATE USER primex_user WITH PASSWORD 'your_password';
GRANT ALL PRIVILEGES ON DATABASE primex_mattress TO primex_user;
\q
```

2. Import the database schema:
```bash
sudo -u postgres psql primex_mattress < database/schema.sql
```

### Step 3: Configuration

1. Update database credentials in `backend/includes/config.php`:
```php
define('DB_HOST', 'localhost');
define('DB_PORT', '5432');
define('DB_NAME', 'primex_mattress');
define('DB_USER', 'primex_user');
define('DB_PASS', 'your_password');
```

2. Update application URL:
```php
define('APP_URL', 'http://localhost/primex-mattress');
define('ADMIN_URL', 'http://localhost/primex-mattress/admin');
```

### Step 4: File Permissions
```bash
chmod -R 755 /var/www/html/primex-mattress
chmod -R 777 /var/www/html/primex-mattress/backend/uploads
```

### Step 5: Web Server Configuration

#### Apache
Create a virtual host configuration:
```apache
<VirtualHost *:80>
    ServerName primex.local
    DocumentRoot /var/www/html/primex-mattress/frontend
    
    <Directory /var/www/html/primex-mattress>
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

#### Nginx
```nginx
server {
    listen 80;
    server_name primex.local;
    root /var/www/html/primex-mattress/frontend;
    index index.html;
    
    location / {
        try_files $uri $uri/ =404;
    }
    
    location /backend {
        alias /var/www/html/primex-mattress/backend;
        try_files $uri $uri/ =404;
    }
    
    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php7.4-fpm.sock;
    }
}
```

### Step 6: Enable Required PHP Extensions
```bash
sudo apt-get install php-pgsql php-mbstring php-json php-curl
sudo systemctl restart apache2
```

## Default Admin Credentials

- **Username:** admin
- **Password:** admin123

> **Important:** Change the default password immediately after first login!

## Usage

### Customer Website
Access the customer website at: `http://localhost/primex-mattress/frontend/`

### Admin Dashboard
Access the admin panel at: `http://localhost/primex-mattress/admin/login.php`

## API Endpoints

### Products
- `GET /backend/api/products.php` - List all products
- `GET /backend/api/products.php?slug={slug}` - Get single product
- `POST /backend/api/products.php` - Create product (admin)
- `PUT /backend/api/products.php` - Update product (admin)
- `DELETE /backend/api/products.php?id={id}` - Delete product (admin)

### Categories
- `GET /backend/api/categories.php` - List all categories
- `POST /backend/api/categories.php` - Create category (admin)
- `PUT /backend/api/categories.php` - Update category (admin)
- `DELETE /backend/api/categories.php?id={id}` - Delete category (admin)

### Cart
- `GET /backend/api/cart.php` - Get cart contents
- `POST /backend/api/cart.php` - Add item to cart
- `PUT /backend/api/cart.php` - Update cart item
- `DELETE /backend/api/cart.php?item_id={id}` - Remove item from cart

### Orders
- `GET /backend/api/orders.php?admin=1` - List all orders (admin)
- `GET /backend/api/orders.php?id={id}` - Get order details
- `POST /backend/api/orders.php` - Create order
- `PUT /backend/api/orders.php` - Update order status (admin)

### Admin Authentication
- `POST /backend/api/admin-auth.php` - Login/logout/check session

## Theme Colors

- **Primary:** Blue (#2563eb)
- **Secondary:** Purple (#9333ea)
- **Accent:** Yellow (#eab308)

## Customization

### Changing Brand Colors
Edit the Tailwind config in each HTML file:
```javascript
tailwind.config = {
    theme: {
        extend: {
            colors: {
                primary: { /* your colors */ },
                secondary: { /* your colors */ },
                accent: { /* your colors */ }
            }
        }
    }
}
```

### Adding New Products
1. Login to admin panel
2. Navigate to Products
3. Click "Add Product"
4. Fill in product details
5. Save

### Managing Orders
1. Login to admin panel
2. Navigate to Orders
3. Use dropdown to update order status
4. Click eye icon to view order details

## Security Considerations

1. **Change default admin password**
2. **Use HTTPS in production**
3. **Set strong database password**
4. **Keep PHP and PostgreSQL updated**
5. **Validate all user inputs**
6. **Use prepared statements (already implemented)**

## Troubleshooting

### Database Connection Error
- Check PostgreSQL is running: `sudo systemctl status postgresql`
- Verify credentials in `config.php`
- Ensure database exists: `sudo -u postgres psql -l`

### Permission Denied Errors
- Check file permissions: `ls -la`
- Set correct ownership: `sudo chown -R www-data:www-data /var/www/html/primex-mattress`

### 404 Errors
- Check web server configuration
- Ensure .htaccess is working (Apache)
- Verify URL in `config.php`

## License

This project is licensed under the MIT License.

## Support

For support, email support@primex.com or visit our website.

---

Built with by Primex Mattress & Beddings Team