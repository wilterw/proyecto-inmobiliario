#!/bin/bash

echo "🏠 Creando InmobiliariaApp - Sistema Completo de Inmobiliaria"
echo "================================================================"

# Crear estructura de directorios
echo "📁 Creando estructura de directorios..."
mkdir -p real_estate_app/{src/{Core,Database,Models,Services,Middleware,Controllers/{Admin,API}},views/{home,properties,admin},public/{assets/{css,js,images},uploads},database}

cd real_estate_app

echo "📝 Creando archivos de configuración..."

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

echo "🎯 Creando archivos principales..."

# README.md
cat > README.md << 'EOF'
# InmobiliariaApp - Sistema Completo de Inmobiliaria

## 🚀 Instalación Rápida

1. **Instalar dependencias:**
```bash
composer install
```

2. **Configurar entorno:**
```bash
cp .env.example .env
# Editar .env con tu configuración
```

3. **Crear base de datos:**
```bash
mysql -u tu_usuario -p inmobiliaria_db < database/schema.sql
```

4. **Iniciar servidor:**
```bash
composer start
```

## 👤 Acceso Admin

- URL: http://localhost:8000/admin/login
- Usuario: superadmin  
- Email: admin@inmobiliaria.com
- Contraseña: password

## 📱 Características

✅ Frontend moderno con efectos 3D
✅ Panel de administración completo  
✅ Sistema de usuarios con roles
✅ Filtros avanzados de propiedades
✅ Integración Google Maps
✅ URLs configurables (WhatsApp, email)
✅ Sin registro para visitantes
✅ Temas personalizables

---
Desarrollado con PHP 8.3 + MySQL + HTML5 + CSS3 + JavaScript ES6+
EOF

echo "✅ Estructura básica creada!"
echo ""
echo "📋 Próximos pasos:"
echo "1. cd real_estate_app"
echo "2. Descargar los archivos de código desde la conversación"
echo "3. composer install"
echo "4. Configurar .env"
echo "5. Importar database/schema.sql"
echo "6. composer start"
echo ""
echo "🎉 ¡Tu aplicación estará lista!"
EOF

chmod +x create_inmobiliaria_app.sh