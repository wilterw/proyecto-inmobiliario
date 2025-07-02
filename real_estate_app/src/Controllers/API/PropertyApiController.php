<?php
declare(strict_types=1);

namespace App\Controllers\API;

use App\Core\Request;
use App\Core\Response;
use App\Models\Property;
use App\Models\SiteConfig;
use App\Database\Database;

class PropertyApiController
{
    public function index(Request $request): void
    {
        $response = new Response();

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

        $filters = array_filter($filters, fn($value) => $value !== null && $value !== '');

        $page = max(1, (int)$request->get('page', 1));
        $perPage = min(50, (int)$request->get('per_page', SiteConfig::getPropertiesPerPage()));

        $properties = Property::getAll($filters, $page, $perPage);

        // Agregar imagen principal a cada propiedad
        foreach ($properties as &$property) {
            if (!$property['main_image']) {
                $images = Property::getImages($property['id']);
                $property['main_image'] = !empty($images) ? $images[0]['image_path'] : null;
            }

            // Decodificar JSON
            $property['features'] = json_decode($property['features'] ?? '[]', true);
            $property['amenities'] = json_decode($property['amenities'] ?? '[]', true);
        }

        $response->json([
            'success' => true,
            'data' => $properties,
            'pagination' => [
                'current_page' => $page,
                'per_page' => $perPage,
                'has_more' => count($properties) === $perPage
            ],
            'filters' => $filters
        ]);
    }

    public function show(Request $request, string $id): void
    {
        $response = new Response();
        $propertyId = (int)$id;

        $property = Property::getById($propertyId);

        if (!$property) {
            $response->status(404)->json([
                'success' => false,
                'error' => 'Propiedad no encontrada'
            ]);
            return;
        }

        // Obtener imágenes
        $images = Property::getImages($propertyId);

        // Decodificar JSON
        $property['features'] = json_decode($property['features'] ?? '[]', true);
        $property['amenities'] = json_decode($property['amenities'] ?? '[]', true);

        $response->json([
            'success' => true,
            'data' => [
                'property' => $property,
                'images' => $images,
                'more_info_url' => $property['more_info_url'] ?: SiteConfig::getDefaultMoreInfoUrl(),
                'contact_url' => $property['contact_url'] ?: SiteConfig::getDefaultContactUrl()
            ]
        ]);
    }

    public function search(Request $request): void
    {
        $response = new Response();

        $query = trim($request->get('q', ''));

        if (strlen($query) < 2) {
            $response->json([
                'success' => true,
                'data' => [],
                'message' => 'Consulta muy corta'
            ]);
            return;
        }

        $properties = Property::search($query);

        $response->json([
            'success' => true,
            'data' => $properties,
            'query' => $query
        ]);
    }

    public function contact(Request $request): void
    {
        $response = new Response();

        $data = [
            'property_id' => $request->input('property_id') ? (int)$request->input('property_id') : null,
            'full_name' => trim($request->input('full_name', '')),
            'email' => trim($request->input('email', '')),
            'phone' => trim($request->input('phone', '')),
            'message' => trim($request->input('message', '')),
            'inquiry_type' => $request->input('inquiry_type', 'informacion')
        ];

        // Validación
        $errors = [];
        if (empty($data['full_name'])) $errors[] = 'El nombre completo es requerido';
        if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Email válido es requerido';
        }
        if (empty($data['message'])) $errors[] = 'El mensaje es requerido';

        if (!empty($errors)) {
            $response->status(400)->json([
                'success' => false,
                'errors' => $errors
            ]);
            return;
        }

        // Guardar consulta
        try {
            $id = Database::insert('contact_requests', $data);

            $response->json([
                'success' => true,
                'message' => 'Consulta enviada correctamente',
                'id' => $id
            ]);
        } catch (\Exception $e) {
            $response->status(500)->json([
                'success' => false,
                'error' => 'Error al enviar la consulta'
            ]);
        }
    }
}