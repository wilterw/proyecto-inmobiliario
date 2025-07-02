<?php
declare(strict_types=1);

namespace App\Models;

use App\Database\Database;

class SiteConfig
{
    private static array $cache = [];

    public static function get(string $key, $default = null)
    {
        if (isset(self::$cache[$key])) {
            return self::$cache[$key];
        }

        $sql = "SELECT config_value, config_type FROM site_config WHERE config_key = :key";
        $result = Database::fetch($sql, ['key' => $key]);

        if (!$result) {
            return $default;
        }

        $value = self::castValue($result['config_value'], $result['config_type']);
        self::$cache[$key] = $value;

        return $value;
    }

    public static function set(string $key, $value, string $type = 'string'): bool
    {
        $stringValue = self::valueToString($value, $type);

        // Verificar si ya existe
        $exists = Database::fetch("SELECT id FROM site_config WHERE config_key = :key", ['key' => $key]);

        if ($exists) {
            $updated = Database::update('site_config', [
                'config_value' => $stringValue,
                'config_type' => $type
            ], ['config_key' => $key]);
            $success = $updated > 0;
        } else {
            $id = Database::insert('site_config', [
                'config_key' => $key,
                'config_value' => $stringValue,
                'config_type' => $type
            ]);
            $success = $id > 0;
        }

        if ($success) {
            self::$cache[$key] = $value;
        }

        return $success;
    }

    public static function getAll(): array
    {
        $sql = "SELECT config_key, config_value, config_type, description FROM site_config ORDER BY config_key";
        $results = Database::fetchAll($sql);

        $config = [];
        foreach ($results as $row) {
            $config[$row['config_key']] = [
                'value' => self::castValue($row['config_value'], $row['config_type']),
                'type' => $row['config_type'],
                'description' => $row['description']
            ];
        }

        return $config;
    }

    public static function updateMultiple(array $config): bool
    {
        return Database::transaction(function() use ($config) {
            foreach ($config as $key => $data) {
                if (is_array($data) && isset($data['value'], $data['type'])) {
                    self::set($key, $data['value'], $data['type']);
                } else {
                    self::set($key, $data);
                }
            }
            return true;
        });
    }

    public static function delete(string $key): bool
    {
        $deleted = Database::delete('site_config', ['config_key' => $key]);
        if ($deleted > 0) {
            unset(self::$cache[$key]);
        }
        return $deleted > 0;
    }

    private static function castValue(string $value, string $type)
    {
        switch ($type) {
            case 'boolean':
                return filter_var($value, FILTER_VALIDATE_BOOLEAN);
            case 'number':
                return is_numeric($value) ? (float)$value : $value;
            case 'json':
                $decoded = json_decode($value, true);
                return $decoded !== null ? $decoded : $value;
            default:
                return $value;
        }
    }

    private static function valueToString($value, string $type): string
    {
        switch ($type) {
            case 'boolean':
                return $value ? 'true' : 'false';
            case 'number':
                return (string)$value;
            case 'json':
                return json_encode($value);
            default:
                return (string)$value;
        }
    }

    // Métodos de conveniencia para configuraciones comunes
    public static function getSiteName(): string
    {
        return self::get('site_name', 'InmobiliariaApp');
    }

    public static function getSiteDescription(): string
    {
        return self::get('site_description', 'Tu inmobiliaria de confianza');
    }

    public static function getSiteLogo(): string
    {
        return self::get('site_logo', '/assets/images/logo.png');
    }

    public static function getContactEmail(): string
    {
        return self::get('contact_email', 'contacto@inmobiliaria.com');
    }

    public static function getContactPhone(): string
    {
        return self::get('contact_phone', '+57 300 123 4567');
    }

    public static function getWhatsAppNumber(): string
    {
        return self::get('whatsapp_number', '+573001234567');
    }

    public static function getGoogleMapsApiKey(): string
    {
        return self::get('google_maps_api_key', '');
    }

    public static function getDefaultMoreInfoUrl(): string
    {
        return self::get('default_more_info_url', 'https://wa.me/573001234567');
    }

    public static function getDefaultContactUrl(): string
    {
        return self::get('default_contact_url', 'mailto:contacto@inmobiliaria.com');
    }

    public static function getPropertiesPerPage(): int
    {
        return (int)self::get('properties_per_page', 12);
    }

    public static function getCurrencySymbol(): string
    {
        return self::get('currency_symbol', '$');
    }

    public static function is3DEffectsEnabled(): bool
    {
        return self::get('enable_3d_effects', true);
    }
}