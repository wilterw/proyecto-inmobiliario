"use client"

import type React from "react"

import { createContext, useContext } from "react"
import { useSiteConfig } from "@/hooks/use-site-config"

interface ThemeContextType {
  config: any
  themes: any[]
  updateConfig: (config: any) => Promise<any>
  activateTheme: (themeId: string) => Promise<any>
}

const ThemeContext = createContext<ThemeContextType | undefined>(undefined)

export function ThemeProvider({ children }: { children: React.ReactNode }) {
  const { config, themes, updateConfig, activateTheme } = useSiteConfig()

  return (
    <ThemeContext.Provider value={{ config, themes, updateConfig, activateTheme }}>{children}</ThemeContext.Provider>
  )
}

export function useTheme() {
  const context = useContext(ThemeContext)
  if (context === undefined) {
    throw new Error("useTheme must be used within a ThemeProvider")
  }
  return context
}
