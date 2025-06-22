"use client"

import { useState, useEffect } from "react"

interface SiteConfig {
  siteName: string
  siteDescription: string
  logo?: string
  favicon?: string
  primaryColor: string
  secondaryColor: string
  accentColor: string
  theme: string
  contactEmail?: string
  contactPhone?: string
  address?: string
  googleMapsKey?: string
}

interface Theme {
  id: string
  name: string
  displayName: string
  colors: string
  isActive: boolean
}

export function useSiteConfig() {
  const [config, setConfig] = useState<SiteConfig | null>(null)
  const [themes, setThemes] = useState<Theme[]>([])
  const [loading, setLoading] = useState(true)

  const fetchConfig = async () => {
    try {
      const response = await fetch("/api/config")
      const data = await response.json()
      setConfig(data.config)
      setThemes(data.themes)
    } catch (error) {
      console.error("Error fetching config:", error)
    } finally {
      setLoading(false)
    }
  }

  const updateConfig = async (newConfig: Partial<SiteConfig>) => {
    try {
      const response = await fetch("/api/config", {
        method: "PUT",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(newConfig),
      })
      const updatedConfig = await response.json()
      setConfig(updatedConfig)
      return updatedConfig
    } catch (error) {
      console.error("Error updating config:", error)
      throw error
    }
  }

  const activateTheme = async (themeId: string) => {
    try {
      const response = await fetch(`/api/themes/${themeId}/activate`, {
        method: "POST",
      })
      const theme = await response.json()
      await fetchConfig() // Refetch to get updated config
      return theme
    } catch (error) {
      console.error("Error activating theme:", error)
      throw error
    }
  }

  useEffect(() => {
    fetchConfig()
  }, [])

  // Apply theme colors to CSS variables
  useEffect(() => {
    if (config) {
      const root = document.documentElement
      root.style.setProperty("--color-primary", config.primaryColor)
      root.style.setProperty("--color-secondary", config.secondaryColor)
      root.style.setProperty("--color-accent", config.accentColor)
    }
  }, [config])

  return {
    config,
    themes,
    loading,
    updateConfig,
    activateTheme,
    refetch: fetchConfig,
  }
}
