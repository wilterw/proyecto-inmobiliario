"use client"

import type React from "react"

import { motion } from "framer-motion"
import { MapPin } from "lucide-react"
import type { Filters } from "@/app/page"

interface FilterSidebarProps {
  filters: Filters
  setFilters: (filters: Filters) => void
  onApplyFilters: () => void
  loading?: boolean
}

export default function FilterSidebar({ filters, setFilters, onApplyFilters, loading }: FilterSidebarProps) {
  const tabs = ["Compra", "Venta", "Arriendo"] as const
  const bedroomOptions = [1, 2, 3, 4, 5]
  const bathroomOptions = [1, 2, 3, 4]
  const propertyTypes = ["Casa", "Departamento", "Oficina", "Local", "Terreno"]

  return (
    <motion.div
      className="bg-white rounded-xl shadow-md p-6 md:sticky md:top-8"
      initial={{ opacity: 0, x: -20 }}
      animate={{ opacity: 1, x: 0 }}
      transition={{ duration: 0.5 }}
    >
      <h3 className="text-xl font-bold text-gray-800 mb-6">Filtros de Búsqueda</h3>

      {/* Pestañas de Estado */}
      <div className="mb-6">
        <div className="flex space-x-1 bg-gray-100 p-1 rounded-lg">
          {tabs.map((tab) => (
            <button
              key={tab}
              onClick={() => setFilters({ ...filters, activeTab: tab })}
              className={`flex-1 py-2 px-3 rounded-md text-sm font-medium transition-colors ${
                filters.activeTab === tab ? "text-white" : "bg-transparent text-gray-700 hover:bg-gray-200"
              }`}
              style={{
                backgroundColor: filters.activeTab === tab ? "var(--color-primary)" : "transparent",
              }}
            >
              {tab}
            </button>
          ))}
        </div>
      </div>

      {/* Tipo de Propiedad */}
      <div className="mb-6">
        <label className="block text-sm font-medium text-gray-700 mb-2">Tipo de Propiedad</label>
        <select
          value={filters.propertyType}
          onChange={(e) => setFilters({ ...filters, propertyType: e.target.value })}
          className="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:border-transparent"
          style={{ "--tw-ring-color": "var(--color-primary)" } as React.CSSProperties}
        >
          <option value="">Todos los tipos</option>
          {propertyTypes.map((type) => (
            <option key={type} value={type}>
              {type}
            </option>
          ))}
        </select>
      </div>

      {/* Filtro de Ubicación */}
      <div className="mb-6">
        <label className="block text-sm font-medium text-gray-700 mb-2">Ubicación</label>
        <div className="relative">
          <MapPin className="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 h-5 w-5" />
          <input
            type="text"
            placeholder="Ciudad, Barrio o Código Postal"
            value={filters.location}
            onChange={(e) => setFilters({ ...filters, location: e.target.value })}
            className="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:border-transparent"
            style={{ "--tw-ring-color": "var(--color-primary)" } as React.CSSProperties}
          />
        </div>
      </div>

      {/* Filtro de Rango de Precios */}
      <div className="mb-6">
        <label className="block text-sm font-medium text-gray-700 mb-2">Rango de Precios</label>
        <div className="grid grid-cols-2 gap-3">
          <input
            type="number"
            placeholder="Mínimo"
            value={filters.priceMin}
            onChange={(e) => setFilters({ ...filters, priceMin: e.target.value })}
            className="px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:border-transparent"
            style={{ "--tw-ring-color": "var(--color-primary)" } as React.CSSProperties}
          />
          <input
            type="number"
            placeholder="Máximo"
            value={filters.priceMax}
            onChange={(e) => setFilters({ ...filters, priceMax: e.target.value })}
            className="px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:border-transparent"
            style={{ "--tw-ring-color": "var(--color-primary)" } as React.CSSProperties}
          />
        </div>
      </div>

      {/* Filtros de Características */}
      <div className="mb-6">
        <h4 className="text-sm font-medium text-gray-700 mb-4">Características</h4>

        {/* Dormitorios */}
        <div className="mb-4">
          <label className="block text-sm text-gray-600 mb-2">Dormitorios</label>
          <div className="flex flex-wrap gap-2">
            {bedroomOptions.map((num) => (
              <button
                key={num}
                onClick={() =>
                  setFilters({
                    ...filters,
                    bedrooms: filters.bedrooms === num ? null : num,
                  })
                }
                className={`px-4 py-2 rounded-lg text-sm font-medium transition-colors ${
                  filters.bedrooms === num ? "text-white" : "bg-gray-100 text-gray-700 hover:bg-gray-200"
                }`}
                style={{
                  backgroundColor: filters.bedrooms === num ? "var(--color-primary)" : undefined,
                }}
              >
                {num === 5 ? "5+" : num}
              </button>
            ))}
          </div>
        </div>

        {/* Baños */}
        <div className="mb-6">
          <label className="block text-sm text-gray-600 mb-2">Baños</label>
          <div className="flex flex-wrap gap-2">
            {bathroomOptions.map((num) => (
              <button
                key={num}
                onClick={() =>
                  setFilters({
                    ...filters,
                    bathrooms: filters.bathrooms === num ? null : num,
                  })
                }
                className={`px-4 py-2 rounded-lg text-sm font-medium transition-colors ${
                  filters.bathrooms === num ? "text-white" : "bg-gray-100 text-gray-700 hover:bg-gray-200"
                }`}
                style={{
                  backgroundColor: filters.bathrooms === num ? "var(--color-primary)" : undefined,
                }}
              >
                {num === 4 ? "4+" : num}
              </button>
            ))}
          </div>
        </div>
      </div>

      {/* Botón de Aplicar */}
      <motion.button
        onClick={onApplyFilters}
        disabled={loading}
        className="text-white font-bold py-3 rounded-lg w-full transition-colors disabled:opacity-50"
        style={{ backgroundColor: "var(--color-primary)" }}
        whileHover={{ scale: 1.02 }}
        whileTap={{ scale: 0.98 }}
      >
        {loading ? "Aplicando..." : "Aplicar Filtros"}
      </motion.button>
    </motion.div>
  )
}
