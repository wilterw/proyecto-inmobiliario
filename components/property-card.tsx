"use client"

import { motion } from "framer-motion"
import { BedDouble, Bath, Square, MapPin } from "lucide-react"
import GoogleMap from "./google-map"
import type { Property } from "@/app/page"

interface PropertyCardProps {
  property: Property
  index: number
  onClick?: () => void
}

export default function PropertyCard({ property, index, onClick }: PropertyCardProps) {
  const formatPrice = (price: number) => {
    return new Intl.NumberFormat("es-AR", {
      style: "currency",
      currency: "USD",
      minimumFractionDigits: 0,
      maximumFractionDigits: 0,
    }).format(price)
  }

  const getStatusColor = (status: string) => {
    switch (status) {
      case "En Venta":
        return "bg-green-500"
      case "En Arriendo":
        return "bg-yellow-500"
      default:
        return "bg-blue-500"
    }
  }

  return (
    <motion.div
      className="bg-white rounded-xl shadow-lg overflow-hidden flex flex-col h-full cursor-pointer"
      initial={{ opacity: 0, y: 20 }}
      whileInView={{ opacity: 1, y: 0 }}
      viewport={{ once: true }}
      transition={{
        duration: 0.5,
        ease: "easeInOut",
        delay: index * 0.1,
      }}
      whileHover={{
        scale: 1.03,
        y: -5,
        transition: { type: "spring", stiffness: 300 },
      }}
      onClick={onClick}
    >
      {/* Imagen */}
      <div className="relative aspect-video">
        <img
          src={property.images?.[0] || "/placeholder.svg?height=300&width=400"}
          alt={property.title}
          className="object-cover w-full h-full"
        />
        <div
          className={`absolute top-4 right-4 ${getStatusColor(property.status)} text-white px-3 py-1 rounded-full text-sm font-medium`}
        >
          {property.status}
        </div>
      </div>

      {/* Cuerpo de la Tarjeta */}
      <div className="p-6 flex-grow">
        <h4 className="font-bold text-lg text-gray-900 truncate mb-2">{property.title}</h4>

        <div className="flex items-center text-sm text-gray-500 mb-4">
          <MapPin className="h-4 w-4 mr-1" />
          <span className="truncate">
            {property.address}, {property.city}
          </span>
        </div>

        <p className="text-2xl font-bold mb-4" style={{ color: "var(--color-primary)" }}>
          {formatPrice(property.price)}
          {property.status === "En Arriendo" && <span className="text-sm text-gray-500">/mes</span>}
        </p>

        {/* Mapa pequeño si hay coordenadas */}
        {property.latitude && property.longitude && (
          <div className="mb-4">
            <GoogleMap
              latitude={property.latitude}
              longitude={property.longitude}
              address={property.address}
              className="h-32 w-full"
            />
          </div>
        )}
      </div>

      {/* Pie de la Tarjeta */}
      <div className="mt-auto border-t border-gray-200 p-4">
        <div className="flex justify-between items-center mb-4">
          <div className="flex items-center space-x-4 text-sm text-gray-600">
            <div className="flex items-center">
              <BedDouble className="h-4 w-4 mr-1" />
              <span>{property.bedrooms}</span>
            </div>
            <div className="flex items-center">
              <Bath className="h-4 w-4 mr-1" />
              <span>{property.bathrooms}</span>
            </div>
            <div className="flex items-center">
              <Square className="h-4 w-4 mr-1" />
              <span>{property.squareFeet} m²</span>
            </div>
          </div>
        </div>

        <motion.button
          className="w-full border-2 font-medium py-2 rounded-lg transition-colors"
          style={{
            borderColor: "var(--color-primary)",
            color: "var(--color-primary)",
          }}
          whileHover={{
            scale: 1.02,
            backgroundColor: "var(--color-primary)",
            color: "white",
          }}
          whileTap={{ scale: 0.98 }}
          onClick={(e) => {
            e.stopPropagation()
            onClick?.()
          }}
        >
          Ver Detalles
        </motion.button>
      </div>
    </motion.div>
  )
}
