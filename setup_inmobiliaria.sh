#!/bin/bash

echo "🏠 INSTALADOR AUTOMÁTICO - InmobiliariaApp"
echo "=========================================="
echo "📅 $(date)"
echo ""

# Colores para output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

print_step() {
    echo -e "${BLUE}🔹 $1${NC}"
}

print_success() {
    echo -e "${GREEN}✅ $1${NC}"
}

print_warning() {
    echo -e "${YELLOW}⚠️  $1${NC}"
}

print_error() {
    echo -e "${RED}❌ $1${NC}"
}

# Verificar que estamos en el directorio correcto
print_step "Verificando entorno..."

# Crear directorio principal
if [ ! -d "real_estate_app" ]; then
    mkdir real_estate_app
    print_success "Directorio real_estate_app creado"
fi

cd real_estate_app

# Crear estructura completa de directorios
print_step "Creando estructura de directorios..."

mkdir -p src/{Core,Database,Models,Services,Middleware,Controllers/{Admin,API}}
mkdir -p views/{home,properties,admin,errors}
mkdir -p public/{assets/{css,js,images},uploads}
mkdir -p database
mkdir -p logs

print_success "Estructura de directorios creada"

# Crear archivos de configuración
print_step "Creando archivos de configuración..."

# composer.json
cat > composer.json << 'EOF'
{
    "name": "real-estate/inmobiliaria-app",
    "description": "Sistema completo de inmobiliaria para compra, venta y arrendamiento",
    "type": "project",
    "license": "MIT",
    "require": {
        "php": ">=8.3",
        "vlucas/phpdotenv": "^5.5",
        "firebase/php-jwt": "^6.8",
        "monolog/monolog": "^3.4",
        "guzzlehttp/guzzle": "^7.8",
        "intervention/image": "^2.7",
        "league/flysystem": "^3.21",
        "respect/validation": "^2.2",
        "twig/twig": "^3.7"
    },
    "require-dev": {
        "phpunit/phpunit": "^10.3",
        "squizlabs/php_codesniffer": "^3.7"
    },
    "autoload": {
        "psr-4": {
            "App\\": "src/",
            "App\\Controllers\\": "src/Controllers/",
            "App\\Models\\": "src/Models/",
            "App\\Services\\": "src/Services/",
            "App\\Middleware\\": "src/Middleware/",
            "App\\Database\\": "src/Database/"
        }
    },
    "autoload-dev": {
        "psr-4": {
            "Tests\\": "tests/"
        }
    },
    "scripts": {
        "start": "php -S localhost:8000 -t public",
        "test": "phpunit",
        "cs-fix": "phpcbf --standard=PSR12 src/",
        "cs-check": "phpcs --standard=PSR12 src/"
    },
    "config": {
        "sort-packages": true
    }
}
EOF

# .env.example
cat > .env.example << 'EOF'
# Base de datos
DB_HOST=localhost
DB_NAME=inmobiliaria_db
DB_USER=root
DB_PASS=

# JWT
JWT_SECRET=tu_clave_secreta_jwt_aqui_cambiar_en_produccion
JWT_EXPIRE=86400

# Configuración de la aplicación
APP_ENV=development
APP_DEBUG=true
APP_URL=http://localhost:8000
APP_NAME="InmobiliariaApp"

# Upload de archivos
UPLOAD_MAX_SIZE=10485760
UPLOAD_PATH=uploads/

# Google Maps API
GOOGLE_MAPS_API_KEY=tu_api_key_de_google_maps

# Email configuración (opcional)
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USER=
MAIL_PASS=
MAIL_FROM=noreply@inmobiliaria.com

# Configuración de seguridad
CSRF_SECRET=tu_clave_csrf_secreta
SESSION_NAME=inmobiliaria_session
EOF

# .htaccess principal
cat > .htaccess << 'EOF'
RewriteEngine On

# Redirect to public directory
RewriteCond %{REQUEST_URI} !^/public/
RewriteRule ^(.*)$ /public/$1 [L]

# Security headers
<IfModule mod_headers.c>
    Header always set X-Content-Type-Options nosniff
    Header always set X-Frame-Options DENY
    Header always set X-XSS-Protection "1; mode=block"
    Header always set Referrer-Policy "strict-origin-when-cross-origin"
    Header always set Permissions-Policy "geolocation=(), microphone=(), camera=()"
</IfModule>

# Hide sensitive files
<Files ".env">
    Order Allow,Deny
    Deny from all
</Files>

<Files "composer.*">
    Order Allow,Deny
    Deny from all
</Files>

<Files "*.md">
    Order Allow,Deny
    Deny from all
</Files>

# Prevent access to src directory
RedirectMatch 403 ^/src/.*
EOF

# .htaccess para public
cat > public/.htaccess << 'EOF'
RewriteEngine On

# Handle routing - send all requests to index.php except for existing files
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php [QSA,L]

# Security headers
<IfModule mod_headers.c>
    Header always set X-Content-Type-Options nosniff
    Header always set X-Frame-Options SAMEORIGIN
    Header always set X-XSS-Protection "1; mode=block"
    Header always set Strict-Transport-Security "max-age=31536000; includeSubDomains"
</IfModule>

# Cache static files
<IfModule mod_expires.c>
    ExpiresActive On
    ExpiresByType text/css "access plus 1 year"
    ExpiresByType application/javascript "access plus 1 year"
    ExpiresByType image/jpg "access plus 1 year"
    ExpiresByType image/jpeg "access plus 1 year"
    ExpiresByType image/gif "access plus 1 year"
    ExpiresByType image/png "access plus 1 year"
    ExpiresByType image/svg+xml "access plus 1 year"
</IfModule>
EOF

print_success "Archivos de configuración creados"

# Crear archivo inicial index.php
print_step "Creando punto de entrada..."

cat > public/index.php << 'EOF'
<?php
declare(strict_types=1);

// Mostrar errores en desarrollo
error_reporting(E_ALL);
ini_set('display_errors', '1');

echo "🏠 InmobiliariaApp - Punto de Entrada Creado<br>";
echo "📅 " . date('Y-m-d H:i:s') . "<br><br>";

echo "<h2>✅ Estructura Básica Lista</h2>";
echo "<p>Los siguientes pasos son:</p>";
echo "<ol>";
echo "<li>📦 <strong>composer install</strong> - Instalar dependencias</li>";
echo "<li>⚙️ <strong>cp .env.example .env</strong> - Configurar entorno</li>";
echo "<li>🗄️ Importar <strong>database/schema.sql</strong></li>";
echo "<li>📁 Crear archivos PHP de la aplicación</li>";
echo "<li>🚀 <strong>composer start</strong> - Iniciar servidor</li>";
echo "</ol>";

echo "<hr>";
echo "<h3>🔧 Diagnóstico del Sistema:</h3>";
echo "<ul>";
echo "<li>PHP Version: " . phpversion() . "</li>";
echo "<li>Directorio actual: " . __DIR__ . "</li>";
echo "<li>Autoloader: " . (file_exists(__DIR__ . '/../vendor/autoload.php') ? '✅ Disponible' : '❌ Ejecutar composer install') . "</li>";
echo "</ul>";

// Verificar estructura de directorios
$dirs = ['../src', '../views', '../database', 'assets', 'uploads'];
echo "<h3>📁 Estructura de Directorios:</h3><ul>";
foreach ($dirs as $dir) {
    echo "<li>" . $dir . ": " . (is_dir($dir) ? '✅' : '❌') . "</li>";
}
echo "</ul>";

if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    echo "<br><a href='/admin/login' style='background: #3B82F6; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>🔐 Ir al Panel de Admin</a>";
}
EOF

print_success "Punto de entrada creado"

# Crear README detallado
print_step "Creando documentación..."

cat > README.md << 'EOF'
# 🏠 InmobiliariaApp - Sistema Completo de Inmobiliaria

![PHP](https://img.shields.io/badge/PHP-8.3+-777BB4?style=flat&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8.0+-4479A1?style=flat&logo=mysql&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-ES6+-F7DF1E?style=flat&logo=javascript&logoColor=black)

## 📋 Descripción

Sistema completo desarrollado en PHP 8.3 para gestión de propiedades inmobiliarias con:
- ✅ Frontend moderno con efectos 3D
- ✅ Panel de administración robusto
- ✅ API REST para integraciones
- ✅ Sin registro requerido para visitantes
- ✅ URLs configurables (WhatsApp, email)

## 🚀 Instalación Rápida

### 1. Instalar Dependencias
```bash
composer install
```

### 2. Configurar Entorno
```bash
cp .env.example .env
# Editar .env con tu configuración de base de datos
```

### 3. Crear Base de Datos
```sql
mysql -u tu_usuario -p
CREATE DATABASE inmobiliaria_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

```bash
mysql -u tu_usuario -p inmobiliaria_db < database/schema.sql
```

### 4. Configurar Permisos
```bash
chmod -R 755 public/
chmod -R 777 public/uploads
```

### 5. Iniciar Servidor
```bash
composer start
```

## 👤 Acceso Administrativo

- **URL**: http://localhost:8000/admin/login
- **Usuario**: `superadmin`
- **Email**: `admin@inmobiliaria.com`
- **Contraseña**: `password`

> ⚠️ **Cambiar contraseña inmediatamente después del primer login**

## 📱 Características Principales

### Frontend Público
- 🏠 Catálogo de propiedades con filtros avanzados
- 🔍 Búsqueda en tiempo real
- 📱 Diseño 100% responsive
- 🎨 Efectos 3D en tarjetas de propiedades
- 🗺️ Integración Google Maps
- 📝 Formulario de venta sin registro

### Panel de Administración
- 📊 Dashboard con estadísticas
- 🏘️ Gestión completa de propiedades (CRUD)
- 👥 Sistema de usuarios con roles
- ⚙️ Configuración de sitio (logos, colores, contacto)
- 🎨 Sistema de temas personalizables
- 📋 Gestión de solicitudes

### Características Técnicas
- 🔒 Autenticación segura con roles
- 🛡️ Middleware de seguridad (CSRF, XSS)
- 📡 API REST completa
- 🔗 URLs configurables por propiedad
- 📤 Sistema de carga de imágenes
- 💾 Base de datos optimizada

## 🏗️ Estructura del Proyecto

```
real_estate_app/
├── 🔧 src/                    # Backend PHP 8.3
│   ├── Core/                 # Framework personalizado
│   ├── Controllers/          # Controladores MVC
│   ├── Models/              # Modelos de datos
│   ├── Services/            # Servicios (Auth, etc.)
│   ├── Middleware/          # Middleware de seguridad
│   └── Database/            # Conexión y operaciones BD
├── 🎨 views/                  # Templates PHP
│   ├── home/                # Página principal
│   ├── properties/          # Páginas de propiedades
│   ├── admin/               # Panel administrativo
│   └── layout.php           # Layout principal
├── 🌐 public/                 # Assets públicos
│   ├── assets/              # CSS, JS, imágenes
│   ├── uploads/             # Archivos subidos
│   └── index.php            # Punto de entrada
├── 🗄️ database/               # Scripts SQL
│   └── schema.sql           # Esquema completo
└── 📋 composer.json          # Dependencias y scripts
```

## 🔧 API Endpoints

### Públicos
- `GET /api/properties` - Listar propiedades
- `GET /api/properties/{id}` - Detalles de propiedad
- `GET /api/properties/search?q=` - Buscar propiedades
- `POST /api/contact` - Formulario de contacto

### Administración
- `POST /admin/login` - Autenticación
- `GET /admin/dashboard` - Dashboard con estadísticas
- `GET /admin/propiedades` - Gestión de propiedades

## 🎨 Personalización

### Temas Incluidos
1. **Moderno Azul** (por defecto)
2. **Elegante Verde**
3. **Profesional Gris**

### Configuración
- Colores primarios y secundarios
- Logos y branding
- Información de contacto
- URLs de redirección (WhatsApp, email)
- Integración Google Maps

## 🔒 Seguridad

- ✅ Autenticación basada en sesiones
- ✅ Protección CSRF y XSS
- ✅ Validación de datos frontend/backend
- ✅ SQL injection protegido (PDO)
- ✅ Subida segura de archivos
- ✅ Headers de seguridad HTTP

## 🚀 Comandos Útiles

```bash
# Desarrollo
composer start              # Servidor de desarrollo
composer install           # Instalar dependencias
composer update            # Actualizar dependencias

# Calidad de código
composer cs-check          # Verificar estilo de código
composer cs-fix            # Corregir estilo de código
composer test              # Ejecutar tests
```

## 📞 Soporte

- 📧 Email: desarrollo@inmobiliaria.com
- 🐛 Issues: GitHub Issues
- 📖 Docs: Wiki del proyecto

---

⭐ **Desarrollado con PHP 8.3 + MySQL + HTML5 + CSS3 + JavaScript ES6+** ⭐
EOF

print_success "Documentación creada"

# Establecer permisos
print_step "Configurando permisos..."
chmod -R 755 .
chmod -R 777 public/uploads
chmod -R 777 logs

print_success "Permisos configurados"

# Mensaje final
echo ""
echo "🎉 ¡INSTALACIÓN BÁSICA COMPLETADA!"
echo "================================="
echo ""
print_warning "PRÓXIMOS PASOS REQUERIDOS:"
echo ""
echo "1. 📁 Navegar al directorio:"
echo "   cd real_estate_app"
echo ""
echo "2. 📦 Instalar dependencias PHP:"
echo "   composer install"
echo ""
echo "3. ⚙️ Configurar variables de entorno:"
echo "   cp .env.example .env"
echo "   # Editar .env con tu configuración de BD"
echo ""
echo "4. 🗄️ Crear e importar base de datos:"
echo "   mysql -u tu_usuario -p"
echo "   CREATE DATABASE inmobiliaria_db;"
echo "   exit"
echo "   mysql -u tu_usuario -p inmobiliaria_db < database/schema.sql"
echo ""
echo "5. 🚀 Iniciar servidor de desarrollo:"
echo "   composer start"
echo ""
echo "6. 🌐 Abrir en navegador:"
echo "   http://localhost:8000"
echo ""
print_success "Estructura completa creada en: $(pwd)"
print_warning "⚠️  Aún faltan los archivos PHP principales - Se crearán en el siguiente paso"
echo ""
EOF

chmod +x setup_inmobiliaria.sh