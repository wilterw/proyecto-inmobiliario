"use client"

import { useState } from "react"
import AdminLayout from "@/components/admin/admin-layout"
import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card"
import { Button } from "@/components/ui/button"
import { Check, Palette } from "lucide-react"
import { useTheme } from "@/components/theme-provider"
import { motion } from "framer-motion"

export default function ThemesPage() {
  const { themes, activateTheme, config } = useTheme()
  const [activating, setActivating] = useState<string | null>(null)

  const handleActivateTheme = async (themeId: string) => {
    setActivating(themeId)
    try {
      await activateTheme(themeId)
    } catch (error) {
      console.error("Error activating theme:", error)
    } finally {
      setActivating(null)
    }
  }

  const getColorPreview = (colorsJson: string) => {
    try {
      const colors = JSON.parse(colorsJson)
      return (
        <div className="flex space-x-1">
          <div className="w-4 h-4 rounded-full border border-gray-200" style={{ backgroundColor: colors.primary }} />
          <div className="w-4 h-4 rounded-full border border-gray-200" style={{ backgroundColor: colors.secondary }} />
          <div className="w-4 h-4 rounded-full border border-gray-200" style={{ backgroundColor: colors.accent }} />
        </div>
      )
    } catch {
      return null
    }
  }

  return (
    <AdminLayout>
      <div className="space-y-6">
        <div>
          <h1 className="text-3xl font-bold text-gray-900">Temas</h1>
          <p className="text-gray-600">Personaliza la apariencia de tu portal inmobiliario</p>
        </div>

        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          {themes.map((theme, index) => (
            <motion.div
              key={theme.id}
              initial={{ opacity: 0, y: 20 }}
              animate={{ opacity: 1, y: 0 }}
              transition={{ delay: index * 0.1 }}
            >
              <Card className={`relative ${theme.isActive ? "ring-2 ring-blue-500" : ""}`}>
                <CardHeader>
                  <div className="flex items-center justify-between">
                    <CardTitle className="text-lg">{theme.displayName}</CardTitle>
                    {theme.isActive && (
                      <div className="flex items-center text-blue-600">
                        <Check className="h-4 w-4 mr-1" />
                        <span className="text-sm">Activo</span>
                      </div>
                    )}
                  </div>
                </CardHeader>
                <CardContent>
                  <div className="space-y-4">
                    <div className="flex items-center justify-between">
                      <span className="text-sm text-gray-600">Colores:</span>
                      {getColorPreview(theme.colors)}
                    </div>

                    <div className="bg-gray-50 p-4 rounded-lg">
                      <div className="text-xs text-gray-500 mb-2">Vista previa:</div>
                      <div className="space-y-2">
                        <div
                          className="h-2 rounded"
                          style={{
                            backgroundColor: JSON.parse(theme.colors).primary,
                          }}
                        />
                        <div
                          className="h-1 rounded w-3/4"
                          style={{
                            backgroundColor: JSON.parse(theme.colors).secondary,
                          }}
                        />
                        <div
                          className="h-1 rounded w-1/2"
                          style={{
                            backgroundColor: JSON.parse(theme.colors).accent,
                          }}
                        />
                      </div>
                    </div>

                    <Button
                      onClick={() => handleActivateTheme(theme.id)}
                      disabled={theme.isActive || activating === theme.id}
                      className={`w-full ${
                        theme.isActive ? "bg-gray-400 cursor-not-allowed" : "bg-blue-600 hover:bg-blue-700"
                      }`}
                    >
                      {activating === theme.id ? "Activando..." : theme.isActive ? "Tema Activo" : "Activar Tema"}
                    </Button>
                  </div>
                </CardContent>
              </Card>
            </motion.div>
          ))}
        </div>

        <Card>
          <CardHeader>
            <CardTitle className="flex items-center">
              <Palette className="h-5 w-5 mr-2" />
              Crear Tema Personalizado
            </CardTitle>
          </CardHeader>
          <CardContent>
            <p className="text-gray-600 mb-4">
              ¿Necesitas un tema específico para tu marca? Contacta con soporte para crear un tema personalizado.
            </p>
            <Button variant="outline">Solicitar Tema Personalizado</Button>
          </CardContent>
        </Card>
      </div>
    </AdminLayout>
  )
}
