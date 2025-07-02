<?php
declare(strict_types=1);

namespace App\Models;

use App\Database\Database;

class Property
{
    public static function getAll(array $filters = [], int $page = 1, int $perPage = 12): array
    {
        $offset = ($page - 1) * $perPage;
        $whereConditions = [];
        $params = [];

        // Filtros disponibles
        if (!empty($filters['property_type'])) {
            $whereConditions[] = "p.property_type = :property_type";
            $params['property_type'] = $filters['property_type'];
        }

        if (!empty($filters['category_id'])) {
            $whereConditions[] = "p.category_id = :category_id";
            $params['category_id'] = $filters['category_id'];
        }

        if (!empty($filters['city'])) {
            $whereConditions[] = "p.city LIKE :city";
            $params['city'] = '%' . $filters['city'] . '%';
        }

        if (!empty($filters['min_price'])) {
            $whereConditions[] = "p.price >= :min_price";
            $params['min_price'] = $filters['min_price'];
        }

        if (!empty($filters['max_price'])) {
            $whereConditions[] = "p.price <= :max_price";
            $params['max_price'] = $filters['max_price'];
        }

        if (!empty($filters['bedrooms'])) {
            $whereConditions[] = "p.bedrooms >= :bedrooms";
            $params['bedrooms'] = $filters['bedrooms'];
        }

        if (!empty($filters['bathrooms'])) {
            $whereConditions[] = "p.bathrooms >= :bathrooms";
            $params['bathrooms'] = $filters['bathrooms'];
        }

        if (!empty($filters['min_area'])) {
            $whereConditions[] = "p.area >= :min_area";
            $params['min_area'] = $filters['min_area'];
        }

        if (!empty($filters['max_area'])) {
            $whereConditions[] = "p.area <= :max_area";
            $params['max_area'] = $filters['max_area'];
        }

        // Solo propiedades activas
        $whereConditions[] = "p.status = 'disponible'";

        $whereClause = !empty($whereConditions) ? 'WHERE ' . implode(' AND ', $whereConditions) : '';

        $sql = "
            SELECT 
                p.*,
                pc.name as category_name,
                pi.image_path as main_image
            FROM properties p
            LEFT JOIN property_categories pc ON p.category_id = pc.id
            LEFT JOIN property_images pi ON p.id = pi.property_id AND pi.is_main = 1
            $whereClause
            ORDER BY p.is_featured DESC, p.created_at DESC
            LIMIT :limit OFFSET :offset
        ";

        $params['limit'] = $perPage;
        $params['offset'] = $offset;

        return Database::fetchAll($sql, $params);
    }

    public static function getById(int $id): ?array
    {
        $sql = "
            SELECT 
                p.*,
                pc.name as category_name,
                pc.slug as category_slug
            FROM properties p
            LEFT JOIN property_categories pc ON p.category_id = pc.id
            WHERE p.id = :id AND p.status = 'disponible'
        ";

        return Database::fetch($sql, ['id' => $id]);
    }

    public static function getImages(int $propertyId): array
    {
        $sql = "
            SELECT * FROM property_images 
            WHERE property_id = :property_id 
            ORDER BY is_main DESC, display_order ASC
        ";

        return Database::fetchAll($sql, ['property_id' => $propertyId]);
    }

    public static function getFeatured(int $limit = 6): array
    {
        $sql = "
            SELECT 
                p.*,
                pc.name as category_name,
                pi.image_path as main_image
            FROM properties p
            LEFT JOIN property_categories pc ON p.category_id = pc.id
            LEFT JOIN property_images pi ON p.id = pi.property_id AND pi.is_main = 1
            WHERE p.is_featured = 1 AND p.status = 'disponible'
            ORDER BY p.created_at DESC
            LIMIT :limit
        ";

        return Database::fetchAll($sql, ['limit' => $limit]);
    }

    public static function getRecent(int $limit = 8): array
    {
        $sql = "
            SELECT 
                p.*,
                pc.name as category_name,
                pi.image_path as main_image
            FROM properties p
            LEFT JOIN property_categories pc ON p.category_id = pc.id
            LEFT JOIN property_images pi ON p.id = pi.property_id AND pi.is_main = 1
            WHERE p.status = 'disponible'
            ORDER BY p.created_at DESC
            LIMIT :limit
        ";

        return Database::fetchAll($sql, ['limit' => $limit]);
    }

    public static function incrementViews(int $id): void
    {
        $sql = "UPDATE properties SET views_count = views_count + 1 WHERE id = :id";
        Database::query($sql, ['id' => $id]);
    }

    public static function create(array $data): int
    {
        return Database::insert('properties', $data);
    }

    public static function update(int $id, array $data): bool
    {
        $updated = Database::update('properties', $data, ['id' => $id]);
        return $updated > 0;
    }

    public static function delete(int $id): bool
    {
        return Database::transaction(function() use ($id) {
            // Eliminar imágenes primero
            Database::delete('property_images', ['property_id' => $id]);
            
            // Luego eliminar la propiedad
            $deleted = Database::delete('properties', ['id' => $id]);
            return $deleted > 0;
        });
    }

    public static function getStats(): array
    {
        $stats = [];

        // Total de propiedades
        $sql = "SELECT COUNT(*) as total FROM properties WHERE status = 'disponible'";
        $stats['total'] = Database::fetch($sql)['total'];

        // Propiedades en venta
        $sql = "SELECT COUNT(*) as total FROM properties WHERE status = 'disponible' AND property_type IN ('venta', 'venta_arriendo')";
        $stats['for_sale'] = Database::fetch($sql)['total'];

        // Propiedades en arriendo
        $sql = "SELECT COUNT(*) as total FROM properties WHERE status = 'disponible' AND property_type IN ('arriendo', 'venta_arriendo')";
        $stats['for_rent'] = Database::fetch($sql)['total'];

        // Ciudades disponibles
        $sql = "SELECT COUNT(DISTINCT city) as total FROM properties WHERE status = 'disponible'";
        $stats['cities'] = Database::fetch($sql)['total'];

        return $stats;
    }

    public static function search(string $query): array
    {
        $sql = "
            SELECT 
                p.*,
                pc.name as category_name,
                pi.image_path as main_image
            FROM properties p
            LEFT JOIN property_categories pc ON p.category_id = pc.id
            LEFT JOIN property_images pi ON p.id = pi.property_id AND pi.is_main = 1
            WHERE p.status = 'disponible' 
            AND (
                p.title LIKE :query 
                OR p.description LIKE :query 
                OR p.address LIKE :query 
                OR p.city LIKE :query 
                OR p.neighborhood LIKE :query
                OR pc.name LIKE :query
            )
            ORDER BY p.is_featured DESC, p.created_at DESC
            LIMIT 20
        ";

        $searchTerm = '%' . $query . '%';
        return Database::fetchAll($sql, ['query' => $searchTerm]);
    }
}