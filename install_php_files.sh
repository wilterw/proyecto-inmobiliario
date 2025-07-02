#!/bin/bash

echo "🔧 INSTALADOR DE ARCHIVOS PHP - InmobiliariaApp"
echo "=============================================="
echo "📅 $(date)"
echo ""

# Verificar que estamos en el directorio correcto
if [ ! -f "composer.json" ]; then
    echo "❌ Error: No se encontró composer.json. Ejecutar desde el directorio real_estate_app/"
    exit 1
fi

echo "✅ Directorio correcto detectado"
echo "🚀 Iniciando instalación de archivos PHP..."
echo ""

# Función para crear archivos con contenido
create_file() {
    local file_path="$1"
    local content="$2"
    
    # Crear directorio si no existe
    mkdir -p "$(dirname "$file_path")"
    
    # Escribir contenido
    echo "$content" > "$file_path"
    
    echo "   ✅ $file_path"
}

# =================================
# ESQUEMA DE BASE DE DATOS
# =================================
echo "🗄️  Creando esquema de base de datos..."

create_file "database/schema.sql" "-- Base de datos para sistema inmobiliario
CREATE DATABASE IF NOT EXISTS inmobiliaria_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE inmobiliaria_db;

-- Tabla de usuarios administradores
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    role ENUM('super_admin', 'admin') DEFAULT 'admin',
    avatar VARCHAR(255) NULL,
    is_active BOOLEAN DEFAULT TRUE,
    last_login TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabla de configuración del sitio
CREATE TABLE site_config (
    id INT PRIMARY KEY AUTO_INCREMENT,
    config_key VARCHAR(100) NOT NULL UNIQUE,
    config_value TEXT NOT NULL,
    config_type ENUM('string', 'text', 'number', 'boolean', 'json') DEFAULT 'string',
    description VARCHAR(255) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabla de temas
CREATE TABLE themes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL,
    is_active BOOLEAN DEFAULT FALSE,
    primary_color VARCHAR(7) NOT NULL,
    secondary_color VARCHAR(7) NOT NULL,
    accent_color VARCHAR(7) NOT NULL,
    background_color VARCHAR(7) NOT NULL,
    text_color VARCHAR(7) NOT NULL,
    custom_css TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabla de categorías de propiedades
CREATE TABLE property_categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL,
    slug VARCHAR(50) NOT NULL UNIQUE,
    description TEXT NULL,
    icon VARCHAR(50) NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de propiedades
CREATE TABLE properties (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(200) NOT NULL,
    description TEXT NOT NULL,
    property_type ENUM('venta', 'arriendo', 'venta_arriendo') NOT NULL,
    category_id INT NOT NULL,
    price DECIMAL(15,2) NOT NULL,
    rent_price DECIMAL(15,2) NULL,
    currency VARCHAR(3) DEFAULT 'COP',
    
    -- Detalles de la propiedad
    bedrooms INT DEFAULT 0,
    bathrooms INT DEFAULT 0,
    area DECIMAL(10,2) NOT NULL COMMENT 'Área en m²',
    land_area DECIMAL(10,2) NULL COMMENT 'Área del terreno en m²',
    garage_spaces INT DEFAULT 0,
    floor_number INT NULL,
    year_built YEAR NULL,
    
    -- Ubicación
    address VARCHAR(255) NOT NULL,
    city VARCHAR(100) NOT NULL,
    state VARCHAR(100) NOT NULL,
    postal_code VARCHAR(20) NULL,
    neighborhood VARCHAR(100) NULL,
    latitude DECIMAL(10, 8) NULL,
    longitude DECIMAL(11, 8) NULL,
    
    -- Enlaces configurables
    more_info_url VARCHAR(500) NULL,
    contact_url VARCHAR(500) NULL,
    
    -- Características adicionales
    features JSON NULL COMMENT 'Características como piscina, balcón, etc.',
    amenities JSON NULL COMMENT 'Servicios como gym, portería, etc.',
    
    -- Estado y metadatos
    status ENUM('disponible', 'vendida', 'arrendada', 'reservada', 'inactiva') DEFAULT 'disponible',
    is_featured BOOLEAN DEFAULT FALSE,
    views_count INT DEFAULT 0,
    created_by INT NOT NULL,
    
    -- Timestamps
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    -- Índices
    INDEX idx_property_type (property_type),
    INDEX idx_category (category_id),
    INDEX idx_city (city),
    INDEX idx_price (price),
    INDEX idx_area (area),
    INDEX idx_bedrooms (bedrooms),
    INDEX idx_status (status),
    INDEX idx_featured (is_featured),
    INDEX idx_location (latitude, longitude),
    
    FOREIGN KEY (category_id) REFERENCES property_categories(id) ON DELETE RESTRICT,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE RESTRICT
);

-- Tabla de imágenes de propiedades
CREATE TABLE property_images (
    id INT PRIMARY KEY AUTO_INCREMENT,
    property_id INT NOT NULL,
    image_path VARCHAR(500) NOT NULL,
    alt_text VARCHAR(255) NULL,
    is_main BOOLEAN DEFAULT FALSE,
    display_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    INDEX idx_property (property_id),
    INDEX idx_main (is_main),
    INDEX idx_order (display_order),
    
    FOREIGN KEY (property_id) REFERENCES properties(id) ON DELETE CASCADE
);

-- Tabla de solicitudes de venta
CREATE TABLE sell_requests (
    id INT PRIMARY KEY AUTO_INCREMENT,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    property_address VARCHAR(255) NOT NULL,
    property_type VARCHAR(50) NOT NULL,
    estimated_value DECIMAL(15,2) NULL,
    description TEXT NULL,
    contact_preference ENUM('email', 'phone', 'whatsapp') DEFAULT 'email',
    status ENUM('pendiente', 'contactado', 'evaluando', 'cerrado') DEFAULT 'pendiente',
    notes TEXT NULL,
    processed_by INT NULL,
    processed_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    INDEX idx_status (status),
    INDEX idx_email (email),
    
    FOREIGN KEY (processed_by) REFERENCES users(id) ON DELETE SET NULL
);

-- Tabla de contactos/consultas
CREATE TABLE contact_requests (
    id INT PRIMARY KEY AUTO_INCREMENT,
    property_id INT NULL,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NULL,
    message TEXT NOT NULL,
    inquiry_type ENUM('compra', 'arriendo', 'informacion', 'otro') NOT NULL,
    status ENUM('nuevo', 'leido', 'respondido', 'cerrado') DEFAULT 'nuevo',
    response TEXT NULL,
    responded_by INT NULL,
    responded_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    INDEX idx_property (property_id),
    INDEX idx_status (status),
    INDEX idx_inquiry_type (inquiry_type),
    
    FOREIGN KEY (property_id) REFERENCES properties(id) ON DELETE SET NULL,
    FOREIGN KEY (responded_by) REFERENCES users(id) ON DELETE SET NULL
);

-- Tabla de sessiones
CREATE TABLE user_sessions (
    id VARCHAR(128) PRIMARY KEY,
    user_id INT NOT NULL,
    ip_address VARCHAR(45) NOT NULL,
    user_agent TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    expires_at TIMESTAMP NOT NULL,
    
    INDEX idx_user (user_id),
    INDEX idx_expires (expires_at),
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Insertar datos iniciales

-- Usuario super administrador por defecto
INSERT INTO users (username, email, password, full_name, role) VALUES 
('superadmin', 'admin@inmobiliaria.com', '\$2y\$10\$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Super Administrador', 'super_admin');
-- Contraseña: password (cambiar en producción)

-- Configuración inicial del sitio
INSERT INTO site_config (config_key, config_value, config_type, description) VALUES
('site_name', 'InmobiliariaApp', 'string', 'Nombre del sitio'),
('site_description', 'Tu inmobiliaria de confianza', 'text', 'Descripción del sitio'),
('site_logo', '/assets/images/logo.png', 'string', 'Ruta del logo'),
('contact_email', 'contacto@inmobiliaria.com', 'string', 'Email de contacto'),
('contact_phone', '+57 300 123 4567', 'string', 'Teléfono de contacto'),
('whatsapp_number', '+573001234567', 'string', 'Número de WhatsApp'),
('facebook_url', '', 'string', 'URL de Facebook'),
('instagram_url', '', 'string', 'URL de Instagram'),
('google_maps_api_key', '', 'string', 'API Key de Google Maps'),
('default_more_info_url', 'https://wa.me/573001234567', 'string', 'URL por defecto para más información'),
('default_contact_url', 'mailto:contacto@inmobiliaria.com', 'string', 'URL por defecto para contacto'),
('properties_per_page', '12', 'number', 'Propiedades por página'),
('enable_3d_effects', 'true', 'boolean', 'Habilitar efectos 3D'),
('currency_symbol', '\$', 'string', 'Símbolo de moneda');

-- Tema por defecto
INSERT INTO themes (name, is_active, primary_color, secondary_color, accent_color, background_color, text_color) VALUES
('Moderno Azul', TRUE, '#3B82F6', '#1E40AF', '#F59E0B', '#FFFFFF', '#1F2937'),
('Elegante Verde', FALSE, '#10B981', '#047857', '#EF4444', '#F9FAFB', '#111827'),
('Profesional Gris', FALSE, '#6B7280', '#374151', '#8B5CF6', '#FFFFFF', '#1F2937');

-- Categorías de propiedades
INSERT INTO property_categories (name, slug, description, icon) VALUES
('Casa', 'casa', 'Casas familiares y residenciales', 'home'),
('Apartamento', 'apartamento', 'Apartamentos y condominios', 'building'),
('Casa Quinta', 'casa-quinta', 'Casas quintas y fincas recreativas', 'tree'),
('Local Comercial', 'local-comercial', 'Locales para negocios', 'store'),
('Oficina', 'oficina', 'Espacios de oficina', 'briefcase'),
('Lote', 'lote', 'Terrenos y lotes', 'map'),
('Bodega', 'bodega', 'Bodegas industriales', 'warehouse');

-- Datos de ejemplo (propiedades)
INSERT INTO properties (title, description, property_type, category_id, price, bedrooms, bathrooms, area, address, city, state, neighborhood, more_info_url, features, amenities, is_featured, created_by) VALUES
('Casa Familiar en Zona Norte', 'Hermosa casa de dos plantas en exclusivo barrio residencial. Perfecta para familias que buscan tranquilidad y comodidad.', 'venta', 1, 450000000, 4, 3, 180.50, 'Calle 85 #12-34', 'Bogotá', 'Cundinamarca', 'Zona Rosa', 'https://wa.me/573001234567', '[\"Balcón\", \"Jardín privado\", \"Chimenea\", \"Walk-in closet\"]', '[\"Portería 24h\", \"Zona BBQ\", \"Parqueadero visitantes\"]', TRUE, 1),
('Apartamento Moderno Centro', 'Apartamento completamente renovado en el corazón de la ciudad. Excelente para inversión o vivienda.', 'venta_arriendo', 2, 280000000, 2, 2, 85.00, 'Carrera 7 #45-67', 'Bogotá', 'Cundinamarca', 'Centro', 'https://wa.me/573001234567', '[\"Balcón\", \"Cocina integral\", \"Aire acondicionado\"]', '[\"Gimnasio\", \"Piscina\", \"Salón social\"]', TRUE, 1),
('Local Comercial Zona Comercial', 'Excelente local comercial sobre vía principal, ideal para cualquier tipo de negocio.', 'arriendo', 4, 3500000, 0, 1, 45.00, 'Avenida 68 #23-45', 'Bogotá', 'Cundinamarca', 'Zona Industrial', 'mailto:contacto@inmobiliaria.com', '[\"Vitrina grande\", \"Depósito\", \"Aire acondicionado\"]', '[\"Parqueadero\", \"Seguridad\"]', FALSE, 1);

echo "🎉 ¡Instalación de archivos PHP completada!"
echo ""
echo "📋 Próximos pasos:"
echo "1. composer install"
echo "2. cp .env.example .env (y configurar)"
echo "3. Importar database/schema.sql"
echo "4. composer start"
echo ""
echo "🌐 Acceder a: http://localhost:8000"
echo ""