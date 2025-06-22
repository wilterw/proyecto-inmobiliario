# Portal Inmobiliario Completo

Un sistema completo de portal inmobiliario con panel de administración, temas personalizables y gestión completa de propiedades.

## 🚀 Características

### Frontend
- ✅ Portal inmobiliario responsivo
- ✅ Filtros avanzados de búsqueda
- ✅ Modal de detalles de propiedades
- ✅ Enlaces de contacto personalizados
- ✅ Anim aciones con Framer Motion
- ✅ Integración con Google Maps
- ✅ Temas personalizables
- ✅ Favicon y título editables

### Backend y Admin
- ✅ Panel de administración completo
- ✅ Gestión de propiedades (CRUD)
- ✅ Sistema de autenticación
- ✅ Configuración del sitio
- ✅ Gestión de temas
- ✅ Base de datos con Prisma + SQLite

## 🛠️ Tecnologías

- **Frontend**: Next.js 14, React, TypeScript, Tailwind CSS
- **Animaciones**: Framer Motion
- **Base de datos**: Prisma + SQLite
- **Autenticación**: NextAuth.js
- **UI Components**: shadcn/ui
- **Mapas**: Google Maps API

## 📦 Instalación

1. **Clonar e instalar dependencias**:
\`\`\`bash
npm install
\`\`\`

2. **Configurar variables de entorno**:
\`\`\`bash
cp .env.example .env
\`\`\`

3. **Configurar la base de datos**:
\`\`\`bash
npx prisma db push
npm run db:seed
\`\`\`

4. **Iniciar el servidor de desarrollo**:
\`\`\`bash
npm run dev
\`\`\`

El sitio estará disponible en `http://localhost:3001`

## 🔧 Configuración

### Variables de Entorno

- `DATABASE_URL`: URL de la base de datos SQLite
- `NEXTAUTH_URL`: URL base de la aplicación
- `NEXTAUTH_SECRET`: Clave secreta para NextAuth
- `NEXT_PUBLIC_GOOGLE_MAPS_API_KEY`: API Key de Google Maps (opcional)

### Acceso al Panel de Administración

1. Ve a `http://localhost:3001/admin/login`
2. Usa cualquier email que termine en `@admin.com`
3. La contraseña puede ser cualquiera (es solo para demo)

Ejemplo: `admin@admin.com`

## 📱 Uso

### Portal Público
- Navega a la página principal para ver las propiedades
- Usa los filtros para buscar propiedades específicas
- Haz clic en una propiedad para ver detalles completos
- Usa el botón "Contactar" para comunicarte con el agente

### Panel de Administración
- **Dashboard**: Resumen de estadísticas
- **Propiedades**: Gestión completa de propiedades
- **Configuración**: Personalizar información del sitio
- **Temas**: Cambiar la apariencia del portal

## 🎨 Personalización

### Temas Disponibles
- Azul Profesional (por defecto)
- Verde Esmeralda
- Púrpura Elegante
- Naranja Vibrante
- Modo Oscuro

### Configuración del Sitio
- Nombre y descripción del sitio
- Favicon personalizado
- Información de contacto
- API Key de Google Maps

## 🗄️ Base de Datos

El sistema incluye:
- **Propiedades**: Información completa de inmuebles
- **Usuarios**: Sistema de autenticación
- **Configuración**: Ajustes del sitio
- **Temas**: Esquemas de colores
- **Consultas**: Sistema de leads (preparado para futuro)

## 🚀 Despliegue

### Vercel (Recomendado)
1. Conecta tu repositorio a Vercel
2. Configura las variables de entorno
3. Despliega automáticamente

### Otros Proveedores
- Asegúrate de que soporten Node.js 18+
- Configura las variables de entorno
- Ejecuta `npm run build` para construir la aplicación

## 📞 Soporte

Para soporte técnico o personalizaciones adicionales, contacta al desarrollador.

## 📄 Licencia

Este proyecto es de uso privado. Todos los derechos reservados.
