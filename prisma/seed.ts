import { PrismaClient } from "@prisma/client"

const prisma = new PrismaClient()

async function main() {
  // Crear usuario administrador
  const admin = await prisma.user.upsert({
    where: { email: "admin@inmobiliaria.com" },
    update: {},
    create: {
      email: "admin@inmobiliaria.com",
      name: "Administrador",
      role: "ADMIN",
    },
  })

  // Crear configuración del sitio
  const siteConfig = await prisma.siteConfig.upsert({
    where: { id: "main" },
    update: {},
    create: {
      id: "main",
      siteName: "Portal Inmobiliario Premium",
      siteDescription: "Encuentra tu próximo hogar en las mejores ubicaciones",
      primaryColor: "#2563eb",
      secondaryColor: "#64748b",
      accentColor: "#10b981",
      theme: "blue",
      contactEmail: "contacto@inmobiliaria.com",
      contactPhone: "+54 11 1234-5678",
      address: "Av. Corrientes 1234, Buenos Aires, Argentina",
    },
  })

  // Crear temas predefinidos
  const themes = [
    {
      name: "blue",
      displayName: "Azul Profesional",
      colors: JSON.stringify({
        primary: "#2563eb",
        secondary: "#64748b",
        accent: "#10b981",
        background: "#ffffff",
        surface: "#f8fafc",
      }),
      isActive: true,
    },
    {
      name: "emerald",
      displayName: "Verde Esmeralda",
      colors: JSON.stringify({
        primary: "#059669",
        secondary: "#6b7280",
        accent: "#f59e0b",
        background: "#ffffff",
        surface: "#f0fdf4",
      }),
    },
    {
      name: "purple",
      displayName: "Púrpura Elegante",
      colors: JSON.stringify({
        primary: "#7c3aed",
        secondary: "#6b7280",
        accent: "#ec4899",
        background: "#ffffff",
        surface: "#faf5ff",
      }),
    },
    {
      name: "orange",
      displayName: "Naranja Vibrante",
      colors: JSON.stringify({
        primary: "#ea580c",
        secondary: "#6b7280",
        accent: "#0891b2",
        background: "#ffffff",
        surface: "#fff7ed",
      }),
    },
    {
      name: "dark",
      displayName: "Modo Oscuro",
      colors: JSON.stringify({
        primary: "#3b82f6",
        secondary: "#9ca3af",
        accent: "#10b981",
        background: "#111827",
        surface: "#1f2937",
      }),
    },
  ]

  for (const theme of themes) {
    await prisma.theme.upsert({
      where: { name: theme.name },
      update: {},
      create: theme,
    })
  }

  // Crear propiedades de ejemplo
  const properties = [
    {
      title: "Casa Moderna en Belgrano",
      description: "Hermosa casa de 3 dormitorios con jardín y garage. Ubicada en una zona tranquila y segura.",
      address: "Av. Cabildo 2500",
      city: "Buenos Aires",
      state: "CABA",
      zipCode: "1428",
      price: 450000,
      status: "En Venta",
      propertyType: "Casa",
      bedrooms: 3,
      bathrooms: 2,
      squareFeet: 120,
      yearBuilt: 2018,
      parking: 2,
      images: ["/placeholder.svg?height=400&width=600"],
      features: ["Jardín", "Garage", "Cocina equipada", "Aire acondicionado"],
      latitude: -34.5631,
      longitude: -58.4544,
      contactName: "María González",
      contactEmail: "maria@inmobiliaria.com",
      contactPhone: "+54 11 1234-5678",
      contactLink: "https://wa.me/5491123456789?text=Hola,%20me%20interesa%20la%20casa%20en%20Belgrano",
      isFeatured: true,
    },
    {
      title: "Departamento Luminoso en Palermo",
      description: "Moderno departamento de 2 ambientes con balcón y vista panorámica.",
      address: "Av. Santa Fe 3200",
      city: "Buenos Aires",
      state: "CABA",
      zipCode: "1425",
      price: 2800,
      status: "En Arriendo",
      propertyType: "Departamento",
      bedrooms: 2,
      bathrooms: 1,
      squareFeet: 65,
      yearBuilt: 2020,
      parking: 1,
      images: ["/placeholder.svg?height=400&width=600"],
      features: ["Balcón", "Vista panorámica", "Cocina integrada", "Portero 24hs"],
      latitude: -34.5875,
      longitude: -58.3974,
      contactName: "Carlos Rodríguez",
      contactEmail: "carlos@inmobiliaria.com",
      contactPhone: "+54 11 2345-6789",
      contactLink: "https://wa.me/5491123456789?text=Hola,%20me%20interesa%20el%20departamento%20en%20Palermo",
    },
  ]

  for (const property of properties) {
    await prisma.property.create({
      data: property,
    })
  }

  console.log("Base de datos inicializada con datos de ejemplo")
}

main()
  .then(async () => {
    await prisma.$disconnect()
  })
  .catch(async (e) => {
    console.error(e)
    await prisma.$disconnect()
    process.exit(1)
  })
