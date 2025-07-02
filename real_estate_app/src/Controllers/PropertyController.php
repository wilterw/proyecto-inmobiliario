<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Models\Property;
use App\Models\SiteConfig;
use App\Database\Database;

class PropertyController
{
    public function index(Request $request): void
    {
        $response = new Response();
        
        // Obtener filtros de la URL
        $filters = [
            'property_type' => $request->get('type'),
            'category_id' => $request->get('category'),
            'city' => $request->get('city'),
            'min_price' => $request->get('min_price'),
            'max_price' => $request->get('max_price'),
            'bedrooms' => $request->get('bedrooms'),
            'bathrooms' => $request->get('bathrooms'),
            'min_area' => $request->get('min_area'),
            'max_area' => $request->get('max_area')
        ];

        // Remover filtros vacíos
        $filters = array_filter($filters, fn($value) => $value !== null && $value !== '');

        $page = max(1, (int)$request->get('page', 1));
        $perPage = SiteConfig::getPropertiesPerPage();

        // Obtener propiedades con filtros
        $properties = Property::getAll($filters, $page, $perPage);

        // Obtener categorías para filtros
        $categories = Database::fetchAll("SELECT * FROM property_categories WHERE is_active = 1 ORDER BY name");

        // Obtener ciudades disponibles
        $cities = Database::fetchAll("SELECT DISTINCT city FROM properties WHERE status = 'disponible' ORDER BY city");

        $siteConfig = [
            'name' => SiteConfig::getSiteName(),
            'currency_symbol' => SiteConfig::getCurrencySymbol(),
            'enable_3d_effects' => SiteConfig::is3DEffectsEnabled()
        ];

        $response->view('properties/index', [
            'properties' => $properties,
            'filters' => $filters,
            'categories' => $categories,
            'cities' => $cities,
            'currentPage' => $page,
            'siteConfig' => $siteConfig,
            'pageTitle' => 'Propiedades'
        ]);
    }

    public function show(Request $request, string $id): void
    {
        $response = new Response();
        $propertyId = (int)$id;

        $property = Property::getById($propertyId);

        if (!$property) {
            $response->status(404)->view('errors/404', [
                'pageTitle' => 'Propiedad no encontrada'
            ]);
            return;
        }

        // Incrementar contador de vistas
        Property::incrementViews($propertyId);

        // Obtener imágenes de la propiedad
        $images = Property::getImages($propertyId);

        // Decodificar características y servicios JSON
        $property['features'] = json_decode($property['features'] ?? '[]', true);
        $property['amenities'] = json_decode($property['amenities'] ?? '[]', true);

        // Obtener URL de más información o usar la por defecto
        $moreInfoUrl = $property['more_info_url'] ?: SiteConfig::getDefaultMoreInfoUrl();
        $contactUrl = $property['contact_url'] ?: SiteConfig::getDefaultContactUrl();

        $siteConfig = [
            'name' => SiteConfig::getSiteName(),
            'currency_symbol' => SiteConfig::getCurrencySymbol(),
            'google_maps_api_key' => SiteConfig::getGoogleMapsApiKey(),
            'enable_3d_effects' => SiteConfig::is3DEffectsEnabled()
        ];

        $response->view('properties/show', [
            'property' => $property,
            'images' => $images,
            'moreInfoUrl' => $moreInfoUrl,
            'contactUrl' => $contactUrl,
            'siteConfig' => $siteConfig,
            'pageTitle' => $property['title']
        ]);
    }

    public function sell(Request $request): void
    {
        $response = new Response();

        if ($request->getMethod() === 'GET') {
            $siteConfig = [
                'name' => SiteConfig::getSiteName(),
                'contact_email' => SiteConfig::getContactEmail(),
                'contact_phone' => SiteConfig::getContactPhone()
            ];

            $response->view('properties/sell', [
                'siteConfig' => $siteConfig,
                'pageTitle' => 'Vender Propiedad'
            ]);
        }
    }

    public function storeSellRequest(Request $request): void
    {
        $response = new Response();

        $data = [
            'full_name' => $request->input('full_name'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'property_address' => $request->input('property_address'),
            'property_type' => $request->input('property_type'),
            'estimated_value' => $request->input('estimated_value') ? (float)$request->input('estimated_value') : null,
            'description' => $request->input('description'),
            'contact_preference' => $request->input('contact_preference', 'email')
        ];

        // Validación básica
        $errors = [];
        if (empty($data['full_name'])) $errors[] = 'El nombre completo es requerido';
        if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) $errors[] = 'Email válido es requerido';
        if (empty($data['phone'])) $errors[] = 'El teléfono es requerido';
        if (empty($data['property_address'])) $errors[] = 'La dirección de la propiedad es requerida';
        if (empty($data['property_type'])) $errors[] = 'El tipo de propiedad es requerido';

        if (!empty($errors)) {
            if ($request->isAjax()) {
                $response->status(400)->json(['errors' => $errors]);
                return;
            }
            
            $siteConfig = ['name' => SiteConfig::getSiteName()];
            $response->view('properties/sell', [
                'errors' => $errors,
                'data' => $data,
                'siteConfig' => $siteConfig,
                'pageTitle' => 'Vender Propiedad'
            ]);
            return;
        }

        // Guardar solicitud
        $id = Database::insert('sell_requests', $data);

        if ($request->isAjax()) {
            $response->json(['success' => true, 'message' => 'Solicitud enviada correctamente']);
            return;
        }

        $response->redirect('/vender?success=1');
    }

    public function filter(Request $request): void
    {
        $response = new Response();

        if ($request->isAjax()) {
            $query = $request->get('q', '');
            
            if (strlen($query) < 2) {
                $response->json(['properties' => []]);
                return;
            }

            $properties = Property::search($query);
            $response->json(['properties' => $properties]);
            return;
        }

        // Redirigir a la página de propiedades con filtros
        $queryString = http_build_query($request->getQuery());
        $response->redirect('/propiedades?' . $queryString);
    }
}