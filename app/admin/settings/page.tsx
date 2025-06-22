"use client"

import type React from "react"

import { useState, useEffect } from "react"
import AdminLayout from "@/components/admin/admin-layout"
import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card"
import { Button } from "@/components/ui/button"
import { Input } from "@/components/ui/input"
import { Label } from "@/components/ui/label"
import { Textarea } from "@/components/ui/textarea"
import { Save } from "lucide-react"
import { useTheme } from "@/components/theme-provider"

export default function SettingsPage() {
  const { config, updateConfig } = useTheme()
  const [formData, setFormData] = useState({
    siteName: "",
    siteDescription: "",
    favicon: "",
    contactEmail: "",
    contactPhone: "",
    address: "",
    googleMapsKey: "",
  })
  const [loading, setLoading] = useState(false)
  const [success, setSuccess] = useState(false)

  useEffect(() => {
    if (config) {
      setFormData({
        siteName: config.siteName || "",
        siteDescription: config.siteDescription || "",
        favicon: config.favicon || "",
        contactEmail: config.contactEmail || "",
        contactPhone: config.contactPhone || "",
        address: config.address || "",
        googleMapsKey: config.googleMapsKey || "",
      })
    }
  }, [config])

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault()
    setLoading(true)
    setSuccess(false)

    try {
      await updateConfig(formData)
      setSuccess(true)
      setTimeout(() => setSuccess(false), 3000)
    } catch (error) {
      console.error("Error updating config:", error)
    } finally {
      setLoading(false)
    }
  }

  const handleInputChange = (e: React.ChangeEvent<HTMLInputElement | HTMLTextAreaElement>) => {
    setFormData({
      ...formData,
      [e.target.name]: e.target.value,
    })
  }

  return (
    <AdminLayout>
      <div className="space-y-6">
        <div>
          <h1 className="text-3xl font-bold text-gray-900">Configuración del Sitio</h1>
          <p className="text-gray-600">Personaliza la información y configuración de tu portal</p>
        </div>

        <form onSubmit={handleSubmit} className="space-y-6">
          <Card>
            <CardHeader>
              <CardTitle>Información General</CardTitle>
            </CardHeader>
            <CardContent className="space-y-4">
              <div>
                <Label htmlFor="siteName">Nombre del Sitio</Label>
                <Input
                  id="siteName"
                  name="siteName"
                  value={formData.siteName}
                  onChange={handleInputChange}
                  placeholder="Portal Inmobiliario"
                />
                <p className="text-sm text-gray-500 mt-1">Este será el título que aparece en el navegador</p>
              </div>

              <div>
                <Label htmlFor="siteDescription">Descripción</Label>
                <Textarea
                  id="siteDescription"
                  name="siteDescription"
                  value={formData.siteDescription}
                  onChange={handleInputChange}
                  placeholder="Encuentra tu próximo hogar..."
                  rows={3}
                />
              </div>

              <div>
                <Label htmlFor="favicon">URL del Favicon</Label>
                <Input
                  id="favicon"
                  name="favicon"
                  value={formData.favicon}
                  onChange={handleInputChange}
                  placeholder="https://ejemplo.com/favicon.ico"
                />
                <p className="text-sm text-gray-500 mt-1">
                  URL del icono que aparece en la pestaña del navegador (16x16 o 32x32 píxeles)
                </p>
              </div>
            </CardContent>
          </Card>

          <Card>
            <CardHeader>
              <CardTitle>Información de Contacto</CardTitle>
            </CardHeader>
            <CardContent className="space-y-4">
              <div>
                <Label htmlFor="contactEmail">Email de Contacto</Label>
                <Input
                  id="contactEmail"
                  name="contactEmail"
                  type="email"
                  value={formData.contactEmail}
                  onChange={handleInputChange}
                  placeholder="contacto@inmobiliaria.com"
                />
              </div>

              <div>
                <Label htmlFor="contactPhone">Teléfono</Label>
                <Input
                  id="contactPhone"
                  name="contactPhone"
                  value={formData.contactPhone}
                  onChange={handleInputChange}
                  placeholder="+54 11 1234-5678"
                />
              </div>

              <div>
                <Label htmlFor="address">Dirección</Label>
                <Textarea
                  id="address"
                  name="address"
                  value={formData.address}
                  onChange={handleInputChange}
                  placeholder="Av. Corrientes 1234, Buenos Aires"
                  rows={2}
                />
              </div>
            </CardContent>
          </Card>

          <Card>
            <CardHeader>
              <CardTitle>Configuración de Google Maps</CardTitle>
            </CardHeader>
            <CardContent>
              <div>
                <Label htmlFor="googleMapsKey">API Key de Google Maps</Label>
                <Input
                  id="googleMapsKey"
                  name="googleMapsKey"
                  value={formData.googleMapsKey}
                  onChange={handleInputChange}
                  placeholder="AIzaSyC4R6AN7SmxjPUIGKdyBLT7CovTuIgYUnE"
                  type="password"
                />
                <p className="text-sm text-gray-500 mt-1">
                  Necesario para mostrar mapas en las propiedades.
                  <a
                    href="https://developers.google.com/maps/documentation/javascript/get-api-key"
                    target="_blank"
                    className="text-blue-600 hover:underline ml-1"
                    rel="noreferrer"
                  >
                    Obtener API Key
                  </a>
                </p>
              </div>
            </CardContent>
          </Card>

          <div className="flex justify-end space-x-4">
            {success && <div className="text-green-600 flex items-center">✓ Configuración guardada exitosamente</div>}
            <Button type="submit" disabled={loading} className="bg-blue-600 hover:bg-blue-700">
              {loading ? (
                <>Guardando...</>
              ) : (
                <>
                  <Save className="h-4 w-4 mr-2" />
                  Guardar Cambios
                </>
              )}
            </Button>
          </div>
        </form>
      </div>
    </AdminLayout>
  )
}
