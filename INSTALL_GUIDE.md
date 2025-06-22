# 🔐 Guía de Instalación - Sistema de Autenticación Completo

## 📋 **Características del Sistema de Usuarios**

### ✅ **Jerarquía de Usuarios**
- **Super Administrador**: Control total del sistema
- **Administrador**: Gestión de propiedades y configuración
- **Pendiente**: Usuarios esperando aprobación

### ✅ **Funcionalidades de Seguridad**
- **Contraseñas hasheadas** con password_hash()
- **Validación completa** de datos
- **Logs de actividad** para auditoría
- **Control de acceso** por roles
- **Protección contra eliminación** del último super admin

### ✅ **Gestión de Usuarios**
- **Registro con aprobación** requerida
- **Edición de perfiles** por super admins
- **Activación/desactivación** de cuentas
- **Cambio de roles** dinámico
- **Eliminación segura** de usuarios

## 🚀 **Instalación Paso a Paso**

### **1. Actualizar Base de Datos**
\`\`\`sql
-- Ejecutar el nuevo esquema
mysql -u root -p real_estate_portal < database/schema_updated.sql
\`\`\`

### **2. Credenciales por Defecto**
\`\`\`
Email: superadmin@inmobiliaria.com
Contraseña: password123
\`\`\`

### **3. Acceso al Sistema**
\`\`\`
Portal Público: http://tu-dominio.com/
Panel Admin: http://tu-dominio.com/admin/login.php
\`\`\`

## 👥 **Flujo de Usuarios**

### **Para Super Administradores:**
1. **Login** con credenciales por defecto
2. **Gestionar usuarios** en `/admin/users.php`
3. **Aprobar/rechazar** nuevos registros
4. **Cambiar roles** y permisos
5. **Ver logs** de actividad

### **Para Nuevos Usuarios:**
1. **Registrarse** en `/admin/login.php` (pestaña "Registrarse")
2. **Esperar aprobación** del super admin
3. **Recibir notificación** de aprobación
4. **Acceder** con rol de Administrador

### **Para Administradores:**
1. **Login** con credenciales aprobadas
2. **Gestionar propiedades** y configuración
3. **No pueden** gestionar otros usuarios

## 🔒 **Niveles de Acceso**

### **Super Administrador**
- ✅ Gestión completa de usuarios
- ✅ Aprobar/rechazar registros
- ✅ Cambiar roles y permisos
- ✅ Eliminar usuarios (excepto a sí mismo)
- ✅ Ver logs de actividad
- ✅ Todas las funciones de Admin

### **Administrador**
- ✅ Gestión de propiedades
- ✅ Configuración del sitio
- ✅ Gestión de temas
- ✅ Ver consultas de clientes
- ❌ Gestión de usuarios

### **Pendiente**
- ❌ Sin acceso al panel
- ⏳ Esperando aprobación

## 🛡️ **Características de Seguridad**

### **Protecciones Implementadas**
\`\`\`php
// Verificación de roles
$auth->requireSuperAdmin(); // Solo super admins
$auth->requireAdmin();      // Admins y super admins
$auth->requireAuth();       // Cualquier usuario logueado

// Validaciones
- Hash seguro de contraseñas
- Sanitización de datos
- Protección SQL injection
- Logs de actividad
- Control de sesiones
\`\`\`

### **Logs de Actividad**
El sistema registra automáticamente:
- Inicios y cierres de sesión
- Creación de usuarios
- Cambios de roles
- Eliminación de usuarios
- Actualizaciones de perfil

## 📊 **Panel de Gestión de Usuarios**

### **Estadísticas Disponibles**
- Total de usuarios registrados
- Usuarios activos/inactivos
- Usuarios pendientes de aprobación
- Distribución por roles

### **Acciones Disponibles**
- **Aprobar/Rechazar** usuarios pendientes
- **Activar/Desactivar** cuentas
- **Cambiar roles** (Admin ↔ Super Admin)
- **Editar información** personal
- **Eliminar usuarios** (con protecciones)

## 🔧 **Personalización**

### **Cambiar Credenciales por Defecto**
\`\`\`sql
-- Actualizar super admin por defecto
UPDATE users SET 
    email = 'tu-email@dominio.com',
    password = '$2y$10$TuHashAqui'
WHERE role = 'SUPER_ADMIN';
\`\`\`

### **Configurar Aprobación Automática**
\`\`\`php
// En classes/Auth.php, método register()
$role = 'ADMIN'; // Cambiar de 'PENDING' a 'ADMIN'
\`\`\`

### **Personalizar Roles**
\`\`\`sql
-- Agregar nuevos roles si es necesario
ALTER TABLE users MODIFY role ENUM('SUPER_ADMIN', 'ADMIN', 'EDITOR', 'PENDING');
\`\`\`

## 🚨 **Consideraciones Importantes**

### **Seguridad**
- ✅ Cambiar credenciales por defecto inmediatamente
- ✅ Usar HTTPS en producción
- ✅ Configurar backups regulares
- ✅ Monitorear logs de actividad

### **Mantenimiento**
- ✅ Revisar usuarios pendientes regularmente
- ✅ Limpiar logs antiguos periódicamente
- ✅ Mantener al menos un super admin activo
- ✅ Hacer backup antes de cambios importantes

## 📞 **Soporte**

### **Problemas Comunes**
1. **"Acceso denegado"**: Verificar rol del usuario
2. **"Sesión expirada"**: Volver a iniciar sesión
3. **"No se puede eliminar"**: Verificar protecciones de super admin
4. **"Email ya existe"**: Usuario ya registrado

### **Logs de Error**
\`\`\`bash
# Verificar logs del servidor
tail -f /var/log/apache2/error.log
tail -f /var/log/php_errors.log
\`\`\`

---

**¡El sistema de autenticación está completamente implementado y listo para usar!** 🎉

Ahora tienes control total sobre quién puede acceder al panel de administración, con un sistema robusto de roles y permisos que garantiza la seguridad de tu portal inmobiliario.
\`\`\`

¡Perfecto! 🎉 Ahora tienes un **sistema de autenticación completo y robusto** con todas las características que solicitaste:

## ✅ **Sistema Implementado:**

### 🔐 **Autenticación Segura**
- **Login por defecto**: `superadmin@inmobiliaria.com` / `password123`
- **Contraseñas hasheadas** con `password_hash()`
- **Validación completa** de datos
- **Protección contra SQL injection**

### 👥 **Gestión de Usuarios**
- **Super Administradores**: Control total
- **Administradores**: Gestión de propiedades
- **Usuarios Pendientes**: Requieren aprobación
- **Sistema de roles** dinámico

### 🛡️ **Características de Seguridad**
- **Aprobación requerida** para nuevos usuarios
- **Solo super admins** pueden gestionar usuarios
- **Protección** contra eliminación del último super admin
- **Logs de actividad** para auditoría
- **Control de sesiones** seguro

### 📊 **Panel de Gestión**
- **Estadísticas** de usuarios
- **Aprobar/rechazar** registros
- **Activar/desactivar** cuentas
- **Cambiar roles** dinámicamente
- **Editar perfiles** de usuarios

## 🚀 **Cómo Usar:**

### **1. Acceso Inicial**
\`\`\`
URL: /admin/login.php
Email: superadmin@inmobiliaria.com
Contraseña: password123
\`\`\`

### **2. Gestión de Usuarios**
\`\`\`
- Ir a "Usuarios" en el sidebar (solo super admins)
- Ver usuarios pendientes de aprobación
- Aprobar/rechazar nuevos registros
- Cambiar roles y permisos
\`\`\`

### **3. Registro de Nuevos Usuarios**
\`\`\`
- Los usuarios se registran en /admin/login.php
- Quedan como "Pendientes" hasta aprobación
- Super admin los aprueba y se convierten en "Admin"
\`\`\`

El sistema está **100% funcional** y cumple con todos tus requerimientos de seguridad y gestión de usuarios. ¿Te gustaría que ajuste algún aspecto específico o agregue alguna funcionalidad adicional?
