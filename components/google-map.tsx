"use client"

import { useEffect, useRef } from "react"
import { useTheme } from "./theme-provider"

interface GoogleMapProps {
  latitude: number
  longitude: number
  address: string
  className?: string
}

declare global {
  interface Window {
    google: any
    initMap: () => void
  }
}

export default function GoogleMap({ latitude, longitude, address, className = "" }: GoogleMapProps) {
  const mapRef = useRef<HTMLDivElement>(null)
  const { config } = useTheme()

  useEffect(() => {
    if (!config?.googleMapsKey || !latitude || !longitude) return

    const loadGoogleMaps = () => {
      if (window.google) {
        initializeMap()
        return
      }

      const script = document.createElement("script")
      script.src = `https://maps.googleapis.com/maps/api/js?key=${config.googleMapsKey}&callback=initMap`
      script.async = true
      script.defer = true

      window.initMap = initializeMap
      document.head.appendChild(script)
    }

    const initializeMap = () => {
      if (!mapRef.current || !window.google) return

      const map = new window.google.maps.Map(mapRef.current, {
        center: { lat: latitude, lng: longitude },
        zoom: 15,
        styles: [
          {
            featureType: "poi",
            elementType: "labels",
            stylers: [{ visibility: "off" }],
          },
        ],
      })

      new window.google.maps.Marker({
        position: { lat: latitude, lng: longitude },
        map: map,
        title: address,
        icon: {
          url:
            "data:image/svg+xml;charset=UTF-8," +
            encodeURIComponent(`
            <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M16 2C11.6 2 8 5.6 8 10C8 16 16 30 16 30S24 16 24 10C24 5.6 20.4 2 16 2ZM16 13C14.3 13 13 11.7 13 10S14.3 7 16 7S19 8.3 19 10S17.7 13 16 13Z" fill="${config.primaryColor || "#2563eb"}"/>
            </svg>
          `),
          scaledSize: new window.google.maps.Size(32, 32),
        },
      })
    }

    loadGoogleMaps()
  }, [latitude, longitude, address, config])

  if (!config?.googleMapsKey) {
    return (
      <div className={`bg-gray-200 rounded-lg flex items-center justify-center ${className}`}>
        <div className="text-center text-gray-500">
          <p className="text-sm">Mapa no disponible</p>
          <p className="text-xs">Configure Google Maps API Key</p>
        </div>
      </div>
    )
  }

  return <div ref={mapRef} className={`rounded-lg ${className}`} />
}
