"use client"

import type React from "react"

import { motion, AnimatePresence } from "framer-motion"
import { X, BedDouble, Bath, Square, MapPin, Home, ExternalLink, Phone, Mail } from "lucide-react"
import type { Property } from "@/app/page"
import ImageCarousel from "./image-carousel"
import GoogleMap from "./google-map"

interface PropertyDetailModalProps {
  property: Property | null
  isOpen: boolean
  onClose: () => void
}

export default function PropertyDetailModal({ property, isOpen, onClose }: PropertyDetailModalProps) {
  if (!property) return null

  const formatPrice = (price: number) => {
    return new Intl.NumberFormat("es-AR", {
      style: "currency",
      currency: "USD",
      minimumFractionDigits: 0,
      maximumFractionDigits: 0,
    }).format(price)
  }

  const handleContactClick = () => {
    if (property.contactLink) {
      window.open(property.contactLink, "_blank")
    }
  }

  return (
    <AnimatePresence>
      {isOpen && (
        <motion.div
          className="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4"
          initial={{ opacity: 0 }}
          animate={{ opacity: 1 }}
          exit={{ opacity: 0 }}
          onClick={onClose}
        >
          <motion.div
            className="bg-white rounded-xl max-w-6xl w-full max-h-[90vh] overflow-y-auto"
            initial={{ scale: 0.9, opacity: 0 }}
            animate={{ scale: 1, opacity: 1 }}
            exit={{ scale: 0.9, opacity: 0 }}
            onClick={(e) => e.stopPropagation()}
          >
            {/* Header */}
            <div className="flex justify-between items-center p-6 border-b sticky top-0 bg-white z-10">
              <h2 className="text-2xl font-bold text-gray-900">{property.title}</h2>
              <button onClick={onClose} className="p-2 hover:bg-gray-100 rounded-full transition-colors">
                <X className="h-6 w-6" />
              </button>
            </div>

            {/* Content */}
            <div className="p-6">
              {/* Image Carousel */}
              <div className="mb-8">
                <ImageCarousel imageUrls={property.images || ["/placeholder.svg?height=400&width=600"]} />
              </div>

              {/* Property Info Grid */}
              <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
                {/* Main Info */}
                <div className="lg:col-span-2 space-y-6">
                  {/* Location and Price */}
                  <div>
                    <div className="flex items-center text-gray-600 mb-4">
                      <MapPin className="h-5 w-5 mr-2" />
                      <span className="text-lg">
                        {property.address}, {property.city}, {property.state}
                      </span>
                    </div>

                    <div className="flex items-center justify-between mb-6">
                      <div className="text-4xl font-bold" style={{ color: "var(--color-primary)" }}>
                        {formatPrice(property.price)}
                        {property.status === "En Arriendo" && <span className="text-xl text-gray-500">/mes</span>}
                      </div>
                      <div className="text-right">
                        <span
                          className={`px-4 py-2 rounded-full text-sm font-medium ${
                            property.status === "En Venta"
                              ? "bg-green-100 text-green-800"
                              : property.status === "En Arriendo"
                                ? "bg-blue-100 text-blue-800"
                                : "bg-gray-100 text-gray-800"
                          }`}
                        >
                          {property.status}
                        </span>
                      </div>
                    </div>
                  </div>

                  {/* Property Stats */}
                  <div className="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div className="text-center p-4 bg-gray-50 rounded-lg">
                      <BedDouble className="h-8 w-8 mx-auto mb-2" style={{ color: "var(--color-primary)" }} />
                      <div className="font-semibold text-lg">{property.bedrooms}</div>
                      <div className="text-sm text-gray-600">Dormitorios</div>
                    </div>
                    <div className="text-center p-4 bg-gray-50 rounded-lg">
                      <Bath className="h-8 w-8 mx-auto mb-2" style={{ color: "var(--color-primary)" }} />
                      <div className="font-semibold text-lg">{property.bathrooms}</div>
                      <div className="text-sm text-gray-600">Baños</div>
                    </div>
                    <div className="text-center p-4 bg-gray-50 rounded-lg">
                      <Square className="h-8 w-8 mx-auto mb-2" style={{ color: "var(--color-primary)" }} />
                      <div className="font-semibold text-lg">{property.squareFeet}</div>
                      <div className="text-sm text-gray-600">m²</div>
                    </div>
                    <div className="text-center p-4 bg-gray-50 rounded-lg">
                      <Home className="h-8 w-8 mx-auto mb-2" style={{ color: "var(--color-primary)" }} />
                      <div className="font-semibold text-lg">{property.parking}</div>
                      <div className="text-sm text-gray-600">Estacionamientos</div>
                    </div>
                  </div>

                  {/* Description */}
                  {property.description && (
                    <div>
                      <h3 className="text-xl font-semibold mb-3">Descripción</h3>
                      <p className="text-gray-600 leading-relaxed">{property.description}</p>
                    </div>
                  )}

                  {/* Features */}
                  {property.features && property.features.length > 0 && (
                    <div>
                      <h3 className="text-xl font-semibold mb-3">Características</h3>
                      <div className="grid grid-cols-2 gap-2">
                        {property.features.map((feature, index) => (
                          <div key={index} className="flex items-center text-gray-600">
                            <div
                              className="w-2 h-2 rounded-full mr-3"
                              style={{ backgroundColor: "var(--color-primary)" }}
                            />
                            {feature}
                          </div>
                        ))}
                      </div>
                    </div>
                  )}

                  {/* Map */}
                  {property.latitude && property.longitude && (
                    <div>
                      <h3 className="text-xl font-semibold mb-3">Ubicación</h3>
                      <GoogleMap
                        latitude={property.latitude}
                        longitude={property.longitude}
                        address={property.address}
                        className="h-64 w-full"
                      />
                    </div>
                  )}
                </div>

                {/* Contact Sidebar */}
                <div className="space-y-6">
                  {/* Contact Agent */}
                  <div className="bg-gray-50 rounded-lg p-6">
                    <h3 className="text-lg font-semibold mb-4">Información de Contacto</h3>

                    {property.contactName && (
                      <div className="mb-3">
                        <div className="font-medium text-gray-900">{property.contactName}</div>
                        <div className="text-sm text-gray-600">Agente Inmobiliario</div>
                      </div>
                    )}

                    <div className="space-y-3 mb-6">
                      {property.contactPhone && (
                        <div className="flex items-center text-gray-600">
                          <Phone className="h-4 w-4 mr-2" />
                          <span className="text-sm">{property.contactPhone}</span>
                        </div>
                      )}
                      {property.contactEmail && (
                        <div className="flex items-center text-gray-600">
                          <Mail className="h-4 w-4 mr-2" />
                          <span className="text-sm">{property.contactEmail}</span>
                        </div>
                      )}
                    </div>

                    {property.contactLink && (
                      <motion.button
                        onClick={handleContactClick}
                        className="w-full text-white font-bold py-3 px-4 rounded-lg transition-colors flex items-center justify-center"
                        style={{ backgroundColor: "var(--color-primary)" }}
                        whileHover={{ scale: 1.02 }}
                        whileTap={{ scale: 0.98 }}
                      >
                        <ExternalLink className="h-4 w-4 mr-2" />
                        Contactar Ahora
                      </motion.button>
                    )}
                  </div>

                  {/* Property Details */}
                  <div className="bg-blue-50 rounded-lg p-6">
                    <h3 className="text-lg font-semibold mb-3 text-blue-900">Detalles de la Propiedad</h3>
                    <div className="space-y-2 text-blue-800 text-sm">
                      <div className="flex justify-between">
                        <span>Tipo:</span>
                        <span className="font-medium">{property.propertyType}</span>
                      </div>
                      {property.yearBuilt && (
                        <div className="flex justify-between">
                          <span>Año de construcción:</span>
                          <span className="font-medium">{property.yearBuilt}</span>
                        </div>
                      )}
                      <div className="flex justify-between">
                        <span>Estado:</span>
                        <span className="font-medium">{property.status}</span>
                      </div>
                    </div>
                  </div>

                  {/* Quick Contact Form */}
                  <div className="bg-white border rounded-lg p-6">
                    <h3 className="text-lg font-semibold mb-4">Consulta Rápida</h3>
                    <div className="space-y-3">
                      <input
                        type="text"
                        placeholder="Tu nombre"
                        className="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:border-transparent text-sm"
                        style={{ "--tw-ring-color": "var(--color-primary)" } as React.CSSProperties}
                      />
                      <input
                        type="email"
                        placeholder="Tu email"
                        className="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:border-transparent text-sm"
                        style={{ "--tw-ring-color": "var(--color-primary)" } as React.CSSProperties}
                      />
                      <textarea
                        placeholder="Tu mensaje..."
                        rows={3}
                        className="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:border-transparent text-sm"
                        style={{ "--tw-ring-color": "var(--color-primary)" } as React.CSSProperties}
                      />
                      <button
                        className="w-full text-white font-medium py-2 px-4 rounded-lg transition-colors text-sm"
                        style={{ backgroundColor: "var(--color-secondary)" }}
                      >
                        Enviar Consulta
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </motion.div>
        </motion.div>
      )}
    </AnimatePresence>
  )
}
