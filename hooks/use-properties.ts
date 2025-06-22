"use client"

import { useState } from "react"
import type { Property, Filters } from "@/app/page"

export function useProperties() {
  const [properties, setProperties] = useState<Property[]>([])
  const [loading, setLoading] = useState(true)
  const [error, setError] = useState<string | null>(null)

  const fetchProperties = async (filters?: Filters) => {
    try {
      setLoading(true)
      setError(null)

      const queryParams = new URLSearchParams()
      if (filters?.location) queryParams.append("location", filters.location)
      if (filters?.priceMin) queryParams.append("priceMin", filters.priceMin)
      if (filters?.priceMax) queryParams.append("priceMax", filters.priceMax)
      if (filters?.bedrooms) queryParams.append("bedrooms", filters.bedrooms.toString())
      if (filters?.bathrooms) queryParams.append("bathrooms", filters.bathrooms.toString())
      if (filters?.activeTab !== "Compra") queryParams.append("status", filters.activeTab)

      const response = await fetch(`/api/properties?${queryParams}`)

      if (!response.ok) {
        throw new Error("Error al cargar las propiedades")
      }

      const data = await response.json()
      setProperties(data)
    } catch (err) {
      setError(err instanceof Error ? err.message : "Error desconocido")
    } finally {
      setLoading(false)
    }
  }

  return {
    properties,
    loading,
    error,
    fetchProperties,
    refetch: () => fetchProperties(),
  }
}
