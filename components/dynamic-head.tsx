"use client"

import { useEffect } from "react"
import { useTheme } from "./theme-provider"

export function DynamicHead() {
  const { config } = useTheme()

  useEffect(() => {
    if (config) {
      // Actualizar título
      document.title = config.siteName || "Portal Inmobiliario"

      // Actualizar favicon
      if (config.favicon) {
        let link = document.querySelector("link[rel*='icon']") as HTMLLinkElement
        if (!link) {
          link = document.createElement("link")
          link.rel = "icon"
          document.getElementsByTagName("head")[0].appendChild(link)
        }
        link.href = config.favicon
      }

      // Actualizar meta description
      let metaDescription = document.querySelector('meta[name="description"]') as HTMLMetaElement
      if (!metaDescription) {
        metaDescription = document.createElement("meta")
        metaDescription.name = "description"
        document.getElementsByTagName("head")[0].appendChild(metaDescription)
      }
      metaDescription.content = config.siteDescription || "Encuentra tu próximo hogar"
    }
  }, [config])

  return null
}
