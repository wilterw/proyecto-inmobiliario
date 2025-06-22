-- Portal Inmobiliario - Esquema Actualizado con Sistema de Usuarios
CREATE DATABASE IF NOT EXISTS real_estate_portal;
USE real_estate_portal;

-- Tabla de usuarios actualizada
DROP TABLE IF EXISTS users;
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('SUPER_ADMIN', 'ADMIN', 'PENDING') DEFAULT 'PENDING',
    is_active BOOLEAN DEFAULT TRUE,
    last_login TIMESTAMP NULL,
    created_by INT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
);

-- Tabla de propiedades (sin cambios)
CREATE TABLE properties (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    address VARCHAR(255) NOT NULL,
    city VARCHAR(100) NOT NULL,
    state VARCHAR(100) NOT NULL,
    zip_code VARCHAR(20),
    country VARCHAR(100) DEFAULT 'Colombia',
    price DECIMAL(15,2) NOT NULL,
    status ENUM('En Venta', 'En Arriendo', 'Vendido', 'Arrendado') DEFAULT 'En Venta',
    property_type ENUM('Casa', 'Apartamento', 'Oficina', 'Local', 'Terreno', 'Finca') DEFAULT 'Casa',
    bedrooms INT NOT NULL,
    bathrooms INT NOT NULL,
    square_feet INT NOT NULL,
    year_built INT,
    parking INT DEFAULT 0,
    images JSON,
    features JSON,
    latitude DECIMAL(10, 8),
    longitude DECIMAL(11, 8),
    contact_name VARCHAR(255),
    contact_email VARCHAR(255),
    contact_phone VARCHAR(50),
    contact_link TEXT,
    is_active BOOLEAN DEFAULT TRUE,
    is_featured BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabla de configuración del sitio (sin cambios)
CREATE TABLE site_config (
    id VARCHAR(50) PRIMARY KEY DEFAULT 'main',
    site_name VARCHAR(255) DEFAULT 'Portal Inmobiliario',
    site_description TEXT DEFAULT 'Encuentra tu próximo hogar',
    logo_url TEXT,
    favicon_url TEXT,
    primary_color VARCHAR(7) DEFAULT '#2563eb',
    secondary_color VARCHAR(7) DEFAULT '#64748b',
    accent_color VARCHAR(7) DEFAULT '#10b981',
    background_color VARCHAR(7) DEFAULT '#ffffff',
    text_color VARCHAR(7) DEFAULT '#1f2937',
    theme VARCHAR(50) DEFAULT 'blue',
    contact_email VARCHAR(255),
    contact_phone VARCHAR(50),
    address TEXT,
    google_maps_key TEXT,
    whatsapp_number VARCHAR(50),
    facebook_url TEXT,
    instagram_url TEXT,
    youtube_url TEXT,
    sell_button_text VARCHAR(100) DEFAULT '¿Quieres Vender?',
    sell_button_link TEXT,
    sell_button_enabled BOOLEAN DEFAULT TRUE,
    rent_button_text VARCHAR(100) DEFAULT '¿Quieres Arrendar?',
    rent_button_link TEXT,
    rent_button_enabled BOOLEAN DEFAULT TRUE,
    hero_title VARCHAR(255) DEFAULT 'Encuentra tu hogar ideal',
    hero_subtitle TEXT DEFAULT 'Descubre las mejores propiedades en las ubicaciones más exclusivas',
    footer_text TEXT DEFAULT 'Tu hogar perfecto te está esperando',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabla de temas (sin cambios)
CREATE TABLE themes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50) UNIQUE NOT NULL,
    display_name VARCHAR(100) NOT NULL,
    colors JSON NOT NULL,
    is_active BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabla de consultas (sin cambios)
CREATE TABLE inquiries (
    id INT PRIMARY KEY AUTO_INCREMENT,
    property_id INT,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(50),
    message TEXT,
    status ENUM('PENDING', 'CONTACTED', 'CLOSED') DEFAULT 'PENDING',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (property_id) REFERENCES properties(id) ON DELETE CASCADE
);

-- Tabla de logs de actividad
CREATE TABLE activity_logs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    action VARCHAR(100) NOT NULL,
    description TEXT,
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

-- Insertar Super Usuario por defecto
INSERT INTO users (name, email, password, role, is_active) VALUES 
('Super Administrador', 'superadmin@inmobiliaria.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'SUPER_ADMIN', TRUE);
-- Contraseña: password123

-- Insertar configuración inicial
INSERT INTO site_config (
    site_name, 
    site_description, 
    primary_color, 
    secondary_color, 
    accent_color,
    hero_title,
    hero_subtitle,
    sell_button_text,
    sell_button_link,
    rent_button_text,
    rent_button_link,
    contact_email,
    contact_phone
) VALUES (
    'Portal Inmobiliario Premium',
    'Encuentra tu próximo hogar en las mejores ubicaciones de Colombia',
    '#2563eb',
    '#64748b', 
    '#10b981',
    'Encuentra tu hogar ideal',
    'Descubre las mejores propiedades en las ubicaciones más exclusivas de Colombia',
    '¿Quieres Vender tu Propiedad?',
    'https://wa.me/573001234567?text=Hola,%20quiero%20vender%20mi%20propiedad',
    '¿Quieres Arrendar tu Propiedad?',
    'https://wa.me/573001234567?text=Hola,%20quiero%20arrendar%20mi%20propiedad',
    'contacto@inmobiliaria.com',
    '+57 300 123 4567'
);

-- Insertar temas predefinidos
INSERT INTO themes (name, display_name, colors, is_active) VALUES 
('blue', 'Azul Profesional', '{"primary": "#2563eb", "secondary": "#64748b", "accent": "#10b981", "background": "#ffffff", "surface": "#f8fafc"}', TRUE),
('emerald', 'Verde Esmeralda', '{"primary": "#059669", "secondary": "#6b7280", "accent": "#f59e0b", "background": "#ffffff", "surface": "#f0fdf4"}', FALSE),
('purple', 'Púrpura Elegante', '{"primary": "#7c3aed", "secondary": "#6b7280", "accent": "#ec4899", "background": "#ffffff", "surface": "#faf5ff"}', FALSE),
('orange', 'Naranja Vibrante', '{"primary": "#ea580c", "secondary": "#6b7280", "accent": "#0891b2", "background": "#ffffff", "surface": "#fff7ed"}', FALSE),
('dark', 'Modo Oscuro', '{"primary": "#3b82f6", "secondary": "#9ca3af", "accent": "#10b981", "background": "#111827", "surface": "#1f2937"}', FALSE);

-- Insertar propiedades de ejemplo
INSERT INTO properties (
    title, description, address, city, state, price, status, property_type,
    bedrooms, bathrooms, square_feet, year_built, parking, images, features,
    latitude, longitude, contact_name, contact_email, contact_phone, contact_link, is_featured
) VALUES 
(
    'Casa Moderna en Zona Norte',
    'Hermosa casa de 3 habitaciones con jardín privado y garaje doble. Ubicada en sector exclusivo con excelente valorización.',
    'Carrera 15 #85-42',
    'Bogotá',
    'Cundinamarca',
    450000000,
    'En Venta',
    'Casa',
    3, 2, 120, 2020, 2,
    '["https://images.unsplash.com/photo-1568605114967-8130f3a36994?w=800", "https://images.unsplash.com/photo-1570129477492-45c003edd2be?w=800"]',
    '["Jardín privado", "Garaje doble", "Cocina integral", "Aire acondicionado", "Seguridad 24h"]',
    4.6097, -74.0817,
    'María González',
    'maria@inmobiliaria.com',
    '+57 300 123 4567',
    'https://wa.me/573001234567?text=Hola,%20me%20interesa%20la%20casa%20en%20Zona%20Norte',
    TRUE
),
(
    'Apartamento Moderno en Chapinero',
    'Apartamento de 2 habitaciones con balcón y vista panorámica. Excelente ubicación cerca al transporte público.',
    'Calle 63 #11-50',
    'Bogotá',
    'Cundinamarca',
    2800000,
    'En Arriendo',
    'Apartamento',
    2, 1, 65, 2019, 1,
    '["https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?w=800", "https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?w=800"]',
    '["Balcón", "Vista panorámica", "Cocina integrada", "Portería 24h", "Gimnasio"]',
    4.6486, -74.0639,
    'Carlos Rodríguez',
    'carlos@inmobiliaria.com',
    '+57 300 987 6543',
    'https://wa.me/573009876543?text=Hola,%20me%20interesa%20el%20apartamento%20en%20Chapinero',
    FALSE
);
