# Portal Inmobiliario en PHP - Versión Completa

Un sistema completo de portal inmobiliario desarrollado en PHP con panel de administración, configuración avanzada y gestión completa de propiedades.

## 🚀 Características Principales

### ✅ **Portal Público**
- **Diseño responsivo** con Tailwind CSS
- **Filtros avanzados** de búsqueda (ubicación, precio, características)
- **Modal de detalles** con carrusel de imágenes
- **Enlaces de contacto** personalizables por propiedad
- **Botones configurables** para vender/arrendar propiedades
- **Sin registro requerido** para ver detalles

### ✅ **Panel de Administración**
- **Dashboard completo** con estadísticas
- **Gestión de propiedades** (CRUD completo)
- **Configuración del sitio** (colores, textos, logos)
- **Gestión de temas** predefinidos
- **Configuración de botones** de acción
- **Gestión de consultas** de clientes

### ✅ **Configuración Avanzada**
- **Colores personalizables** (primario, secundario, acento)
- **Textos editables** (títulos, descripciones, footer)
- **Logo y favicon** configurables
- **Botones de acción** (vender/arrendar) con enlaces personalizados
- **Redes sociales** y información de contacto
- **Google Maps** integración opcional

### ✅ **Seguridad y Rendimiento**
- **Prepared statements** para prevenir SQL injection
- **Sanitización completa** de datos
- **Validación** en frontend y backend
- **Control de acceso** basado en roles
- **Sessions seguras**

## 🛠️ Tecnologías Utilizadas

- **Backend**: PHP 7.4+, MySQL
- **Frontend**: HTML5, CSS3, JavaScript ES6+, Tailwind CSS
- **Iconos**: Lucide Icons
- **Base de datos**: MySQL con esquema optimizado
- **Arquitectura**: MVC con clases POO

## 📦 Instalación

### **Requisitos del Sistema**
- PHP 7.4 o superior
- MySQL 5.7 o superior
- Servidor web (Apache/Nginx)
- Extensiones PHP: PDO, PDO_MySQL

### **Pasos de Instalación**

1. **Descargar y extraer archivos**
\`\`\`bash
# Extraer en el directorio web del servidor
# Ejemplo: /var/www/html/ o C:\xampp\htdocs\
\`\`\`

2. **Crear base de datos**
\`\`\`sql
CREATE DATABASE real_estate_portal;
\`\`\`

3. **Importar esquema**
\`\`\`bash
mysql -u root -p real_estate_portal < database/schema.sql
\`\`\`

4. **Configurar conexión a BD**
\`\`\`php
// Editar config/database.php
private $host = 'localhost';
private $db_name = 'real_estate_portal';
private $username = 'tu_usuario';
private $password = 'tu_contraseña';
\`\`\`

5. **Configurar permisos**
\`\`\`bash
# En Linux/Mac
chmod 755 -R .
chmod 644 -R *.php
\`\`\`

6. **Acceder al sistema**
- Portal público: `http://tu-dominio.com`
- Panel admin: `http://tu-dominio.com/admin/login.php`

## 🔐 Acceso al Panel de Administración

### **Credenciales de Demo**
- **Email**: Cualquier email terminado en `@admin.com`
- **Contraseña**: Cualquier contraseña (es solo para demo)
- **Ejemplo**: `admin@admin.com` con cualquier contraseña

### **Funcionalidades del Admin**
1. **Dashboard**: Estadísticas y resumen
2. **Propiedades**: Gestión completa (crear, editar, eliminar)
3. **Configuración**: Personalización del sitio
4. **Temas**: Cambio de colores y apariencia
5. **Consultas**: Gestión de leads de clientes

## 🎨 Personalización

### **Configuración del Sitio**
Desde el panel admin puedes configurar:

- ✅ **Información general**: Nombre, descripción, logo, favicon
- ✅ **Colores**: Primario, secundario, acento, fondo, texto
- ✅ **Textos**: Títulos hero, subtítulos, footer
- ✅ **Botones de acción**: Vender/arrendar con enlaces personalizados
- ✅ **Contacto**: Email, teléfono, dirección, WhatsApp
- ✅ **Redes sociales**: Facebook, Instagram, YouTube
- ✅ **Google Maps**: API Key para mostrar mapas

### **Botones Configurables**
Los visitantes pueden:
- **Ver propiedades** sin registrarse
- **Contactar directamente** via enlaces configurados
- **Solicitar vender** su propiedad (botón configurable)
- **Solicitar arrendar** su propiedad (botón configurable)

### **Ejemplos de Enlaces**
\`\`\`
WhatsApp: https://wa.me/573001234567?text=Quiero%20vender%20mi%20propiedad
Email: mailto:ventas@inmobiliaria.com?subject=Quiero%20vender
Formulario: https://forms.google.com/tu-formulario
\`\`\`

## 🗄️ Estructura de Base de Datos

### **Tablas Principales**
- `properties` - Información de propiedades
- `users` - Usuarios del sistema
- `site_config` - Configuración del sitio
- `themes` - Temas predefinidos
- `inquiries` - Consultas de clientes

### **Campos Importantes**
\`\`\`sql
-- Propiedades
title, description, address, city, price, status, property_type
bedrooms, bathrooms, square_feet, images, features
contact_name, contact_email, contact_phone, contact_link

-- Configuración
site_name, logo_url, primary_color, hero_title
sell_button_text, sell_button_link, sell_button_enabled
rent_button_text, rent_button_link, rent_button_enabled
\`\`\`

## 🚀 Despliegue

### **Hosting Compartido**
1. Subir archivos vía FTP
2. Crear base de datos en cPanel
3. Importar `schema.sql`
4. Configurar `database.php`
5. Listo para usar

### **VPS/Servidor Dedicado**
\`\`\`bash
# Instalar LAMP
sudo apt update
sudo apt install apache2 mysql-server php php-mysql

# Configurar virtual host
sudo nano /etc/apache2/sites-available/inmobiliaria.conf

# Habilitar mod_rewrite
sudo a2enmod rewrite
sudo systemctl restart apache2
\`\`\`

### **Docker**
\`\`\`dockerfile
FROM php:7.4-apache
RUN docker-php-ext-install pdo pdo_mysql
COPY . /var/www/html/
RUN chown -R www-data:www-data /var/www/html
\`\`\`

## 📊 API Endpoints

### **Propiedades**
\`\`\`
GET    /api/properties.php           # Listar con filtros
GET    /api/properties.php?id=1      # Obtener una propiedad
POST   /api/properties.php           # Crear propiedad
PUT    /api/properties.php           # Actualizar propiedad
DELETE /api/properties.php           # Eliminar propiedad
\`\`\`

### **Configuración**
\`\`\`
GET /api/config.php                  # Obtener configuración
PUT /api/config.php                  # Actualizar configuración
\`\`\`

### **Consultas**
\`\`\`
GET  /api/inquiries.php              # Listar consultas
POST /api/inquiries.php              # Crear consulta
\`\`\`

## 🔧 Mantenimiento

### **Backup**
\`\`\`bash
# Base de datos
mysqldump -u usuario -p real_estate_portal > backup.sql

# Archivos
tar -czf backup_files.tar.gz /ruta/del/proyecto
\`\`\`

### **Logs**
\`\`\`bash
# Apache
tail -f /var/log/apache2/error.log

# PHP
tail -f /var/log/php_errors.log
\`\`\`

### **Optimización**
- Usar índices en campos de búsqueda
- Optimizar imágenes antes de subir
- Implementar caché para consultas frecuentes
- Usar CDN para recursos estáticos

## 📞 Soporte y Personalización

### **Características Únicas**
- ✅ **Footer personalizado**: "Sistema Desarrollado por el Ing. Wilter Amaro - Social Marketing Latino"
- ✅ **Sin registro requerido** para visitantes
- ✅ **Botones configurables** para captar leads
- ✅ **Enlaces directos** a WhatsApp, formularios, etc.
- ✅ **Configuración completa** desde el admin
- ✅ **Responsive design** para todos los dispositivos

### **Contacto**
Para soporte técnico o personalizaciones adicionales:
- Revisar logs de error del servidor
- Verificar configuración de base de datos
- Confirmar permisos de archivos
- Contactar al desarrollador

## 📄 Licencia

Este proyecto es desarrollado por el **Ing. Wilter Amaro** - **Social Marketing Latino**.
Todos los derechos reservados.

---

**¡Tu portal inmobiliario está listo para funcionar!** 🏠✨

El sistema incluye todo lo necesario para un portal inmobiliario profesional con configuración completa desde el panel de administración.
