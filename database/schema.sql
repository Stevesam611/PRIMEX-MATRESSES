-- Primex Mattress & Beddings - Database Schema
-- PostgreSQL Database

-- Drop tables if they exist (for clean setup)
DROP TABLE IF EXISTS order_items CASCADE;
DROP TABLE IF EXISTS orders CASCADE;
DROP TABLE IF EXISTS cart_items CASCADE;
DROP TABLE IF EXISTS shopping_cart CASCADE;
DROP TABLE IF EXISTS product_reviews CASCADE;
DROP TABLE IF EXISTS product_images CASCADE;
DROP TABLE IF EXISTS products CASCADE;
DROP TABLE IF EXISTS categories CASCADE;
DROP TABLE IF EXISTS admins CASCADE;
DROP TABLE IF EXISTS customers CASCADE;

-- Create Customers Table
CREATE TABLE customers (
    id SERIAL PRIMARY KEY,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    phone VARCHAR(20),
    password_hash VARCHAR(255),
    address TEXT,
    city VARCHAR(100),
    state VARCHAR(100),
    zip_code VARCHAR(20),
    country VARCHAR(100) DEFAULT 'USA',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create Admins Table
CREATE TABLE admins (
    id SERIAL PRIMARY KEY,
    username VARCHAR(100) UNIQUE NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    full_name VARCHAR(200),
    role VARCHAR(50) DEFAULT 'admin',
    is_active BOOLEAN DEFAULT TRUE,
    last_login TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create Categories Table
CREATE TABLE categories (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) UNIQUE NOT NULL,
    description TEXT,
    image_url VARCHAR(500),
    parent_id INTEGER REFERENCES categories(id) ON DELETE SET NULL,
    sort_order INTEGER DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create Products Table
CREATE TABLE products (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    description TEXT,
    short_description VARCHAR(500),
    price DECIMAL(10, 2) NOT NULL,
    discount_price DECIMAL(10, 2),
    sku VARCHAR(100) UNIQUE,
    stock_quantity INTEGER DEFAULT 0,
    category_id INTEGER REFERENCES categories(id) ON DELETE SET NULL,
    main_image VARCHAR(500),
    specifications JSONB,
    features JSONB,
    is_featured BOOLEAN DEFAULT FALSE,
    is_active BOOLEAN DEFAULT TRUE,
    weight DECIMAL(8, 2),
    dimensions VARCHAR(100),
    warranty VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create Product Images Table
CREATE TABLE product_images (
    id SERIAL PRIMARY KEY,
    product_id INTEGER REFERENCES products(id) ON DELETE CASCADE,
    image_url VARCHAR(500) NOT NULL,
    alt_text VARCHAR(255),
    sort_order INTEGER DEFAULT 0,
    is_primary BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create Product Reviews Table
CREATE TABLE product_reviews (
    id SERIAL PRIMARY KEY,
    product_id INTEGER REFERENCES products(id) ON DELETE CASCADE,
    customer_name VARCHAR(200),
    customer_email VARCHAR(255),
    rating INTEGER CHECK (rating >= 1 AND rating <= 5),
    title VARCHAR(255),
    review TEXT,
    is_approved BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create Shopping Cart Table
CREATE TABLE shopping_cart (
    id SERIAL PRIMARY KEY,
    customer_id INTEGER REFERENCES customers(id) ON DELETE CASCADE,
    session_id VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create Cart Items Table
CREATE TABLE cart_items (
    id SERIAL PRIMARY KEY,
    cart_id INTEGER REFERENCES shopping_cart(id) ON DELETE CASCADE,
    product_id INTEGER REFERENCES products(id) ON DELETE CASCADE,
    quantity INTEGER NOT NULL DEFAULT 1,
    price_at_time DECIMAL(10, 2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create Orders Table
CREATE TABLE orders (
    id SERIAL PRIMARY KEY,
    order_number VARCHAR(50) UNIQUE NOT NULL,
    customer_id INTEGER REFERENCES customers(id) ON DELETE SET NULL,
    customer_email VARCHAR(255),
    customer_phone VARCHAR(20),
    shipping_first_name VARCHAR(100),
    shipping_last_name VARCHAR(100),
    shipping_address TEXT,
    shipping_city VARCHAR(100),
    shipping_state VARCHAR(100),
    shipping_zip VARCHAR(20),
    shipping_country VARCHAR(100) DEFAULT 'USA',
    billing_first_name VARCHAR(100),
    billing_last_name VARCHAR(100),
    billing_address TEXT,
    billing_city VARCHAR(100),
    billing_state VARCHAR(100),
    billing_zip VARCHAR(20),
    billing_country VARCHAR(100) DEFAULT 'USA',
    subtotal DECIMAL(10, 2) NOT NULL,
    shipping_cost DECIMAL(10, 2) DEFAULT 0,
    tax_amount DECIMAL(10, 2) DEFAULT 0,
    discount_amount DECIMAL(10, 2) DEFAULT 0,
    total_amount DECIMAL(10, 2) NOT NULL,
    status VARCHAR(50) DEFAULT 'pending',
    payment_status VARCHAR(50) DEFAULT 'pending',
    payment_method VARCHAR(100),
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create Order Items Table
CREATE TABLE order_items (
    id SERIAL PRIMARY KEY,
    order_id INTEGER REFERENCES orders(id) ON DELETE CASCADE,
    product_id INTEGER REFERENCES products(id) ON DELETE SET NULL,
    product_name VARCHAR(255),
    product_sku VARCHAR(100),
    quantity INTEGER NOT NULL,
    unit_price DECIMAL(10, 2) NOT NULL,
    total_price DECIMAL(10, 2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create indexes for better performance
CREATE INDEX idx_products_category ON products(category_id);
CREATE INDEX idx_products_featured ON products(is_featured) WHERE is_featured = TRUE;
CREATE INDEX idx_products_active ON products(is_active) WHERE is_active = TRUE;
CREATE INDEX idx_products_slug ON products(slug);
CREATE INDEX idx_categories_slug ON categories(slug);
CREATE INDEX idx_orders_customer ON orders(customer_id);
CREATE INDEX idx_orders_status ON orders(status);
CREATE INDEX idx_order_items_order ON order_items(order_id);
CREATE INDEX idx_cart_items_cart ON cart_items(cart_id);
CREATE INDEX idx_product_reviews_product ON product_reviews(product_id);
CREATE INDEX idx_product_images_product ON product_images(product_id);

-- Insert default admin user (password: admin123 - change in production)
INSERT INTO admins (username, email, password_hash, full_name, role)
VALUES ('admin', 'admin@primex.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrator', 'superadmin');

-- Insert default categories
INSERT INTO categories (name, slug, description, image_url, sort_order) VALUES
('Mattresses', 'mattresses', 'Premium quality mattresses for perfect sleep', 'images/categories/mattresses.jpg', 1),
('Pillows', 'pillows', 'Comfortable pillows for all sleep positions', 'images/categories/pillows.jpg', 2),
('Bedding Sets', 'bedding-sets', 'Complete bedding sets for your bedroom', 'images/categories/bedding.jpg', 3),
('Bed Frames', 'bed-frames', 'Stylish and durable bed frames', 'images/categories/bedframes.jpg', 4),
('Protectors', 'protectors', 'Mattress and pillow protectors', 'images/categories/protectors.jpg', 5);

-- Insert sample products
INSERT INTO products (name, slug, description, short_description, price, discount_price, sku, stock_quantity, category_id, main_image, specifications, features, is_featured, is_active, weight, dimensions, warranty) VALUES
('Primex Memory Foam Mattress', 'primex-memory-foam-mattress', 'Experience the ultimate comfort with our premium memory foam mattress. Designed to contour to your body shape, providing excellent support and pressure relief for a restful night sleep.', 'Premium memory foam mattress with body-contouring support', 899.99, 799.99, 'PMX-MEM-001', 50, 1, 'images/products/memory-foam-mattress.jpg', '{"firmness": "Medium", "thickness": "10 inches", "material": "Memory Foam", "cover": "Breathable Knit"}'::jsonb, '["Pressure Relief", "Motion Isolation", "Cooling Technology", "Hypoallergenic"]'::jsonb, TRUE, TRUE, 65.5, '76x80x10 inches', '10 Years'),

('Primex Hybrid Mattress', 'primex-hybrid-mattress', 'The perfect combination of innerspring support and foam comfort. Our hybrid mattress offers the best of both worlds with excellent breathability and support.', 'Best-selling hybrid mattress with spring and foam technology', 1199.99, 999.99, 'PMX-HYB-001', 35, 1, 'images/products/hybrid-mattress.jpg', '{"firmness": "Medium-Firm", "thickness": "12 inches", "material": "Hybrid", "cover": "Quilted Fabric"}'::jsonb, '["Edge Support", "Temperature Regulation", "Individual Coils", "Euro Top"]'::jsonb, TRUE, TRUE, 85.0, '76x80x12 inches', '15 Years'),

('Primex Latex Pillow', 'primex-latex-pillow', 'Natural latex pillow that provides excellent neck support and maintains its shape throughout the night. Hypoallergenic and resistant to dust mites.', 'Natural latex pillow for perfect neck alignment', 89.99, 79.99, 'PMX-PIL-001', 100, 2, 'images/products/latex-pillow.jpg', '{"fill": "Natural Latex", "cover": "Cotton", "size": "Standard", "loft": "Medium"}'::jsonb, '["Hypoallergenic", "Dust Mite Resistant", "Shape Retention", "Breathable"]'::jsonb, TRUE, TRUE, 3.5, '20x26 inches', '3 Years'),

('Primex Cooling Gel Pillow', 'primex-cooling-gel-pillow', 'Stay cool all night with our cooling gel-infused memory foam pillow. Perfect for hot sleepers who need temperature regulation.', 'Cooling gel pillow for temperature regulation', 69.99, NULL, 'PMX-PIL-002', 75, 2, 'images/products/cooling-pillow.jpg', '{"fill": "Gel Memory Foam", "cover": "Cooling Fabric", "size": "Queen", "loft": "Medium"}'::jsonb, '["Cooling Technology", "Removable Cover", "Washable", "Odor Resistant"]'::jsonb, FALSE, TRUE, 4.0, '20x30 inches', '2 Years'),

('Primex Luxury Bedding Set', 'primex-luxury-bedding-set', 'Complete luxury bedding set including fitted sheet, flat sheet, and two pillowcases. Made from 100% Egyptian cotton with 800 thread count.', '800-thread count Egyptian cotton bedding set', 249.99, 199.99, 'PMX-BED-001', 40, 3, 'images/products/bedding-set.jpg', '{"material": "Egyptian Cotton", "thread_count": "800", "pieces": "4", "size": "Queen"}'::jsonb, '["Wrinkle Resistant", "Deep Pockets", "Breathable", "Soft Finish"]'::jsonb, TRUE, TRUE, 5.0, 'Queen Size', '1 Year'),

('Primex Platform Bed Frame', 'primex-platform-bed-frame', 'Modern platform bed frame with solid wood construction. No box spring needed. Easy assembly with all tools included.', 'Solid wood platform bed frame - no box spring needed', 499.99, 449.99, 'PMX-FRM-001', 20, 4, 'images/products/bed-frame.jpg', '{"material": "Solid Wood", "style": "Platform", "assembly": "Required", "weight_capacity": "800 lbs"}'::jsonb, '["No Box Spring Needed", "Under Bed Storage", "Easy Assembly", "Non-Slip Surface"]'::jsonb, TRUE, TRUE, 120.0, '80x60x14 inches', '5 Years'),

('Primex Mattress Protector', 'primex-mattress-protector', 'Waterproof mattress protector that keeps your mattress clean and fresh. Breathable fabric that does not change the feel of your mattress.', 'Waterproof and breathable mattress protector', 49.99, 39.99, 'PMX-PRO-001', 150, 5, 'images/products/mattress-protector.jpg', '{"material": "Polyester", "waterproof": "Yes", "fit": "Deep Pocket", "warranty": "10 Years"}'::jsonb, '["Waterproof", "Hypoallergenic", "Machine Washable", "Noiseless"]'::jsonb, FALSE, TRUE, 2.0, 'Queen Size', '10 Years'),

('Primex Orthopedic Mattress', 'primex-orthopedic-mattress', 'Specially designed orthopedic mattress for back pain relief. Extra firm support with targeted lumbar support zone.', 'Orthopedic mattress for back pain relief', 1099.99, 949.99, 'PMX-ORT-001', 25, 1, 'images/products/orthopedic-mattress.jpg', '{"firmness": "Extra Firm", "thickness": "11 inches", "material": "High-Density Foam", "support": "Lumbar Zone"}'::jsonb, '["Back Pain Relief", "Spinal Alignment", "Pressure Distribution", "Durable"]'::jsonb, TRUE, TRUE, 78.0, '76x80x11 inches', '15 Years');

-- Insert sample reviews
INSERT INTO product_reviews (product_id, customer_name, customer_email, rating, title, review, is_approved) VALUES
(1, 'Sarah Johnson', 'sarah@email.com', 5, 'Best mattress ever!', 'This mattress changed my sleep completely. I wake up refreshed every morning. Highly recommend!', TRUE),
(1, 'Michael Chen', 'mike@email.com', 5, 'Excellent quality', 'Great value for money. The memory foam contours perfectly to my body.', TRUE),
(2, 'Emily Davis', 'emily@email.com', 4, 'Very comfortable', 'Love the hybrid design. Perfect balance of support and comfort.', TRUE),
(3, 'Robert Wilson', 'rob@email.com', 5, 'Perfect pillow', 'Finally found a pillow that supports my neck properly. The latex is amazing.', TRUE);

-- Create function to update timestamp
CREATE OR REPLACE FUNCTION update_updated_at_column()
RETURNS TRIGGER AS $$
BEGIN
    NEW.updated_at = CURRENT_TIMESTAMP;
    RETURN NEW;
END;
$$ language 'plpgsql';

-- Create triggers for updated_at
CREATE TRIGGER update_products_updated_at BEFORE UPDATE ON products
    FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();

CREATE TRIGGER update_customers_updated_at BEFORE UPDATE ON customers
    FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();

CREATE TRIGGER update_orders_updated_at BEFORE UPDATE ON orders
    FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();

CREATE TRIGGER update_cart_updated_at BEFORE UPDATE ON shopping_cart
    FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();

CREATE TRIGGER update_cart_items_updated_at BEFORE UPDATE ON cart_items
    FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();