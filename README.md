# HGW Landing Page - Sistema de Marketing Replicable

Un sistema completo de página de aterrizaje para marketing multinivel con animaciones GSAP avanzadas y sistema de afiliados replicable.

## 🚀 Características Principales

- **Sistema de Afiliados Replicable**: Cada afiliado puede personalizar la página con sus propios parámetros
- **Animaciones GSAP Avanzadas**: Scroll horizontal, infografías animadas, y transiciones suaves
- **Formulario con Webhook**: Integración directa con servicios como Zapier o webhooks personalizados
- **Diseño Responsive**: Optimizado para móviles, tablets y escritorio
- **Validación en Tiempo Real**: Feedback inmediato al usuario en los formularios
- **SEO Optimizado**: Estructura semántica y meta tags apropiados

## 📁 Estructura de Archivos

```
hgw-landing/
├── index.html          # Estructura HTML principal
├── style.css           # Estilos CSS con variables personalizables
├── script.js           # JavaScript con animaciones GSAP y lógica de formularios
└── README.md           # Este archivo
```

## 🔧 Instalación y Configuración

### 1. Descarga los Archivos
Descarga todos los archivos en una carpeta local o súbelos a tu servidor web.

### 2. Configuración Básica
No se requiere configuración adicional. Los archivos están listos para usar inmediatamente.

### 3. Personalización de Colores
Edita las variables CSS en `style.css` para cambiar la paleta de colores:

```css
:root {
    --primary-color: #ff6b6b;      /* Color principal */
    --secondary-color: #4ecdc4;    /* Color secundario */
    --text-color: #333333;         /* Color de texto */
    --bg-color: #ffffff;           /* Color de fondo */
}
```

## 🎯 Sistema de Afiliados Replicable

### Parámetros de URL Soportados

La página acepta los siguientes parámetros de URL para personalización:

| Parámetro | Descripción | Ejemplo |
|-----------|-------------|---------|
| `affiliateId` | ID único del afiliado | `?affiliateId=AFF12345` |
| `webhookUrl` | URL del webhook para recibir los datos | `?webhookUrl=https://hooks.zapier.com/hooks/catch/123/abc` |
| `affiliateName` | Nombre del afiliado para personalización | `?affiliateName=Maria` |
| `redirectUrl` | URL de redirección después del envío | `?redirectUrl=https://ejemplo.com/gracias` |

### Ejemplos de URLs Personalizadas

#### URL Básica del Afiliado
```
https://tudominio.com/index.html?affiliateId=MARIA2024&affiliateName=María&webhookUrl=https://hooks.zapier.com/hooks/catch/123/abc
```

#### URL Completa con Todos los Parámetros
```
https://tudominio.com/index.html?affiliateId=CARLOS2024&affiliateName=Carlos&webhookUrl=https://hooks.zapier.com/hooks/catch/456/def&redirectUrl=https://carlos.com/bienvenida
```

## 📊 Estructura del Payload del Webhook

Cuando un usuario envía el formulario, se envía un JSON con la siguiente estructura:

```json
{
  "firstName": "Juan",
  "lastName": "Pérez",
  "email": "juan@email.com",
  "phone": "+34123456789",
  "country": "ES",
  "affiliateId": "MARIA2024",
  "affiliateName": "María",
  "source": "HGW Landing Page",
  "timestamp": "2024-01-15T10:30:00.000Z",
  "userAgent": "Mozilla/5.0...",
  "language": "es-ES",
  "timezone": "Europe/Madrid",
  "referrer": "https://google.com",
  "currentUrl": "https://tudominio.com/index.html?affiliateId=MARIA2024"
}
```

## 🎨 Animaciones GSAP Incluidas

### 1. Hero Section
- Animación de entrada con fade-in y slide-up
- Animación automática al cargar la página

### 2. Problem Cards
- Aparición escalonada (stagger) cuando entran en viewport
- Efectos hover con elevación

### 3. Products Scroll Horizontal
- Scroll horizontal sincronizado con el scroll vertical
- Pinning de la sección durante la animación

### 4. Infografía de Oportunidad
- Animación step-by-step de círculos y líneas
- Controlada por scroll con ScrollTrigger

### 5. Testimoniales
- Entrada escalonada similar a problem cards
- Efectos interactivos en botones de video

## 🔒 Seguridad y Validación

### Sanitización de Datos
- Todos los inputs son sanitizados antes del envío
- Prevención de ataques XSS
- Validación de formato de email y teléfono

### Validación del Formulario
- Validación en tiempo real
- Feedback visual inmediato
- Mensajes de error en español

## 📱 Responsive Design

El diseño es completamente responsive con breakpoints en:
- **Mobile**: < 480px
- **Tablet**: 481px - 768px  
- **Desktop**: > 768px

## 🚀 Despliegue

### Hosting Estático
Cualquier servicio de hosting estático funciona:
- Netlify
- Vercel
- GitHub Pages
- Hosting tradicional

### CDN Requirements
La página utiliza los siguientes CDNs:
- GSAP: `https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js`
- ScrollTrigger: `https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js`
- Google Fonts: Montserrat

## ⚙️ Configuración de Webhooks

### Zapier (Recomendado)
1. Crea un nuevo Zap en Zapier
2. Usa "Webhooks by Zapier" como trigger
3. Selecciona "Catch Hook"
4. Copia la URL del webhook
5. Úsala como parámetro `webhookUrl`

### Webhook Personalizado
Tu endpoint debe:
- Aceptar método POST
- Aceptar Content-Type: application/json
- Responder con status 200 para éxito

## � Personalización Avanzada

### Cambiar Productos
Edita la sección `#products` en `index.html` y modifica:
- URLs de imágenes de fondo
- Títulos de categorías
- Descripciones

### Modificar Testimoniales
Actualiza la sección `#social-proof` con:
- Nuevas imágenes de testimoniantes
- Testimonios personalizados
- Información de ubicación

### Ajustar Animaciones
Modifica los parámetros en `script.js`:
- Duración de animaciones
- Efectos de easing
- Triggers de ScrollTrigger

## � Analytics y Tracking

El script incluye funciones de tracking que puedes conectar a:
- Google Analytics
- Facebook Pixel
- Servicios de analytics personalizados

```javascript
// Ejemplo de integración con Google Analytics
function trackFormInteraction(action, field) {
    gtag('event', action, {
        'custom_parameter': field,
        'affiliate_id': affiliateId
    });
}
```

## 🐛 Troubleshooting

### Problema: Animaciones no funcionan
**Solución**: Verifica que los CDNs de GSAP se estén cargando correctamente.

### Problema: Formulario no envía
**Solución**: Verifica que la URL del webhook sea válida y esté respondiendo.

### Problema: Página no se ve bien en móvil
**Solución**: Asegúrate de que la meta tag viewport esté presente en el HTML.

## 📞 Soporte

Para soporte técnico o preguntas sobre implementación, contacta al desarrollador o revisa la documentación de GSAP para animaciones avanzadas.

## 📄 Licencia

Este proyecto es de uso comercial para el sistema HGW. Los CDNs externos (GSAP, Google Fonts) mantienen sus propias licencias.

---

**Versión**: 1.0  
**Última actualización**: Enero 2024  
**Compatibilidad**: Navegadores modernos (Chrome 60+, Firefox 60+, Safari 12+)
