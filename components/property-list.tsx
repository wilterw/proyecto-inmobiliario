"use client"

import { motion } from "framer-motion"
import PropertyCard from "./property-card"
import type { Property } from "@/app/page"

interface PropertyListProps {
  properties: Property[]
  loading?: boolean
  onPropertyClick?: (property: Property) => void
}

export default function PropertyList({ properties, loading, onPropertyClick }: PropertyListProps) {
  if (loading) {
    return (
      <div className="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-8">
        {Array.from({ length: 6 }).map((_, index) => (
          <div key={index} className="bg-white rounded-xl shadow-lg overflow-hidden animate-pulse">
            <div className="aspect-video bg-gray-200" />
            <div className="p-6 space-y-4">
              <div className="h-4 bg-gray-200 rounded w-3/4" />
              <div className="h-3 bg-gray-200 rounded w-1/2" />
              <div className="h-6 bg-gray-200 rounded w-1/3" />
            </div>
          </div>
        ))}
      </div>
    )
  }

  if (properties.length === 0) {
    return (
      <motion.div
        className="text-center py-12"
        initial={{ opacity: 0 }}
        animate={{ opacity: 1 }}
        transition={{ duration: 0.5 }}
      >
        <div className="text-gray-500 text-lg">No se encontraron propiedades que coincidan con tus filtros.</div>
        <p className="text-gray-400 mt-2">Intenta ajustar los criterios de búsqueda.</p>
      </motion.div>
    )
  }

  return (
    <div className="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-8">
      {properties.map((property, index) => (
        <PropertyCard key={property.id} property={property} index={index} onClick={() => onPropertyClick?.(property)} />
      ))}
    </div>
  )
}
