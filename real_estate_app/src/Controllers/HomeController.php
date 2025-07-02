<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Models\Property;
use App\Models\SiteConfig;

class HomeController
{
    public function index(Request $request): void
    {
        $response = new Response();
        
        // Obtener propiedades destacadas
        $featuredProperties = Property::getFeatured(6);
        
        // Obtener propiedades recientes
        $recentProperties = Property::getRecent(8);
        
        // Obtener estadísticas
        $stats = Property::getStats();
        
        // Configuración del sitio
        $siteConfig = [
            'name' => SiteConfig::getSiteName(),
            'description' => SiteConfig::getSiteDescription(),
            'logo' => SiteConfig::getSiteLogo(),
            'contact_email' => SiteConfig::getContactEmail(),
            'contact_phone' => SiteConfig::getContactPhone(),
            'whatsapp_number' => SiteConfig::getWhatsAppNumber(),
            'currency_symbol' => SiteConfig::getCurrencySymbol(),
            'enable_3d_effects' => SiteConfig::is3DEffectsEnabled()
        ];
        
        $response->view('home/index', [
            'featuredProperties' => $featuredProperties,
            'recentProperties' => $recentProperties,
            'stats' => $stats,
            'siteConfig' => $siteConfig,
            'pageTitle' => 'Inicio'
        ]);
    }
}