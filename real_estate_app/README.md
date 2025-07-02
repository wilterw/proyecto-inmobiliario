# InmobiliariaApp - Sistema Completo de Inmobiliaria

## 📋 Descripción

InmobiliariaApp es un sistema completo desarrollado en PHP 8.3 para la gestión de propiedades inmobiliarias. Permite la compra, venta y arrendamiento de inmuebles con un frontend moderno que incluye efectos 3D y un panel de administración robusto.

## 🚀 Características Principales

### Frontend Público
- **Página de inicio moderna** con efectos 3D y animaciones
- **Catálogo de propiedades** con filtros avanzados
- **Búsqueda en tiempo real** con AJAX
- **Formulario de venta** para propietarios
- **Integración con Google Maps** para ubicaciones
- **Diseño responsive** optimizado para móviles
- **Efectos 3D** en tarjetas de propiedades
- **Sin registro requerido** para visitantes

### Panel de Administración
- **Dashboard completo** con estadísticas
- **Gestión de propiedades** (CRUD completo)
- **Sistema de usuarios** con roles (Super Admin / Admin)
- **Configuración del sitio** (colores, logos, datos de contacto)
- **Sistema de temas** personalizables
- **Gestión de solicitudes** de venta y contacto
- **Autenticación segura** con sesiones

### Características Técnicas
- **PHP 8.3** con arquitectura MVC
- **Base de datos MySQL** optimizada
- **API REST** para comunicación frontend-backend
- **Sistema de carga de imágenes** con validación
- **URLs configurables** para redirección (WhatsApp, email, etc.)
- **Middleware de seguridad** y autenticación
- **Manejo de errores** robusto

## 🛠️ Tecnologías Utilizadas

- **Backend**: PHP 8.3, PDO, Composer
- **Base de datos**: MySQL 8.0+
- **Frontend**: HTML5, CSS3, JavaScript ES6+
- **Estilos**: CSS Grid, Flexbox, Variables CSS
- **Iconos**: Font Awesome 6
- **Fuentes**: Google Fonts (Inter)
- **API**: Google Maps JavaScript API

## 📦 Instalación

### Requisitos del Sistema

- PHP 8.3 o superior
- MySQL 8.0 o superior
- Composer
- Servidor web (Apache/Nginx)
- Extensiones PHP: PDO, PDO_MySQL, GD, fileinfo

### Pasos de Instalación

1. **Clonar o descargar el proyecto**
```bash
git clone <repository-url> inmobiliaria-app
cd inmobiliaria-app/real_estate_app
```

2. **Instalar dependencias con Composer**
```bash
composer install
```

3. **Configurar variables de entorno**
```bash
cp .env.example .env
```

Editar el archivo `.env` con tu configuración:
```env
# Base de datos
DB_HOST=localhost
DB_NAME=inmobiliaria_db
DB_USER=tu_usuario
DB_PASS=tu_contraseña

# JWT y seguridad
JWT_SECRET=tu_clave_jwt_secreta_aqui
CSRF_SECRET=tu_clave_csrf_secreta

# Google Maps (opcional)
GOOGLE_MAPS_API_KEY=tu_api_key_de_google_maps

# Email (opcional)
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USER=tu_email@gmail.com
MAIL_PASS=tu_contraseña_de_aplicacion
```

4. **Crear la base de datos**
```sql
mysql -u root -p
CREATE DATABASE inmobiliaria_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

5. **Importar el esquema de la base de datos**
```bash
mysql -u tu_usuario -p inmobiliaria_db < database/schema.sql
```

6. **Configurar permisos de directorios**
```bash
chmod -R 755 public/
mkdir -p public/uploads
chmod -R 777 public/uploads
```

7. **Configurar servidor web**

**Para Apache** (crear `.htaccess` en el directorio `public/`):
```apache
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php [QSA,L]
```

**Para Nginx**:
```nginx
server {
    listen 80;
    server_name tu-dominio.com;
    root /path/to/inmobiliaria-app/real_estate_app/public;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```

8. **Servidor de desarrollo (opcional)**
```bash
composer start
# o manualmente:
php -S localhost:8000 -t public
```

## 🏗️ Estructura del Proyecto

```
real_estate_app/
├── src/                          # Código fuente PHP
│   ├── Controllers/             # Controladores
│   │   ├── Admin/              # Controladores del admin
│   │   └── API/                # Controladores de API
│   ├── Models/                 # Modelos de datos
│   ├── Services/               # Servicios (Auth, Email, etc.)
│   ├── Middleware/             # Middleware de seguridad
│   ├── Core/                   # Clases del framework
│   └── Database/               # Conexión a BD
├── views/                       # Templates PHP
│   ├── home/                   # Página de inicio
│   ├── properties/             # Páginas de propiedades
│   ├── admin/                  # Panel de administración
│   └── layout.php              # Layout principal
├── public/                      # Archivos públicos
│   ├── assets/                 # CSS, JS, imágenes
│   │   ├── css/               # Hojas de estilo
│   │   ├── js/                # JavaScript
│   │   └── images/            # Imágenes
│   ├── uploads/               # Archivos subidos
│   └── index.php              # Punto de entrada
├── database/                    # Scripts de BD
│   └── schema.sql             # Esquema completo
├── composer.json               # Dependencias PHP
└── .env.example               # Variables de entorno
```

## 👤 Usuarios por Defecto

Después de la instalación, puedes acceder al panel de administración con:

- **URL**: `http://tu-sitio.com/admin/login`
- **Usuario**: `superadmin`
- **Email**: `admin@inmobiliaria.com`
- **Contraseña**: `password`

> ⚠️ **Importante**: Cambia la contraseña inmediatamente después del primer login.

## 📱 Uso del Sistema

### Para Visitantes
1. **Explorar propiedades**: Navega por el catálogo con filtros
2. **Buscar propiedades**: Usa la barra de búsqueda o filtros avanzados
3. **Ver detalles**: Haz clic en cualquier propiedad para ver información completa
4. **Contactar**: Usa los botones de "Más información" configurables
5. **Vender propiedad**: Llena el formulario en la sección "Vender"

### Para Administradores
1. **Acceder al panel**: `/admin/login`
2. **Gestionar propiedades**: Crear, editar, eliminar propiedades
3. **Configurar sitio**: Cambiar colores, logos, información de contacto
4. **Gestionar usuarios**: Solo Super Admins pueden crear/eliminar usuarios
5. **Ver estadísticas**: Dashboard con métricas importantes

## 🎨 Personalización

### Cambiar Temas
1. Accede al panel de administración
2. Ve a **Configuración > Temas**
3. Selecciona un tema existente o crea uno nuevo
4. Personaliza colores primarios, secundarios y de acento

### Configurar URLs de Contacto
1. En **Configuración > General**
2. Configura URLs por defecto para:
   - Más información (ej: WhatsApp)
   - Contacto (ej: Email o formulario)
3. También puedes configurar URLs específicas por propiedad

### Agregar Google Maps
1. Obtén una API Key de Google Maps JavaScript API
2. Agrégala en el archivo `.env`
3. Las propiedades con coordenadas mostrarán el mapa automáticamente

## 🔧 API Endpoints

### Públicos
- `GET /api/properties` - Listar propiedades con filtros
- `GET /api/properties/{id}` - Obtener propiedad específica
- `GET /api/properties/search?q=query` - Buscar propiedades
- `POST /api/contact` - Enviar formulario de contacto

### Autenticados (Admin)
- `POST /admin/login` - Iniciar sesión
- `POST /admin/logout` - Cerrar sesión
- `GET /admin/dashboard` - Dashboard con estadísticas

## 🚀 Funcionalidades Avanzadas

### Efectos 3D
Los efectos 3D se pueden habilitar/deshabilitar desde el panel de administración. Incluyen:
- Rotación 3D de tarjetas de propiedades al pasar el mouse
- Animaciones de parallax en el hero
- Transiciones suaves entre páginas

### Sistema de Filtros
- Filtro por tipo de propiedad (venta/arriendo)
- Filtro por categoría (casa, apartamento, etc.)
- Filtro por ubicación (ciudad/barrio)
- Filtro por precio (mín/máx)
- Filtro por características (habitaciones, baños, área)

### Responsive Design
- Optimizado para móviles y tablets
- Menú hamburguesa en dispositivos móviles
- Búsqueda adaptativa
- Grids responsive para propiedades

## 🔒 Seguridad

- Autenticación basada en sesiones seguras
- Middleware de protección CSRF
- Validación de datos en frontend y backend
- Sanitización de entradas de usuario
- Protección contra SQL injection con PDO preparado
- Hashing seguro de contraseñas con PHP password_hash()

## 📊 Rendimiento

- Lazy loading de imágenes
- Compresión CSS y JS
- Consultas SQL optimizadas con índices
- Cache de configuración del sitio
- Paginación de resultados

## 🤝 Contribución

1. Fork del proyecto
2. Crear rama para nueva funcionalidad
3. Commit de cambios
4. Push a la rama
5. Crear Pull Request

## 📄 Licencia

Este proyecto está bajo la Licencia MIT. Ver el archivo `LICENSE` para más detalles.

## 📞 Soporte

Para soporte técnico o consultas:
- Email: desarrollo@inmobiliaria.com
- Issues: GitHub Issues
- Documentación: Wiki del proyecto

---

⭐ **¡Si te gusta este proyecto, no olvides darle una estrella!** ⭐