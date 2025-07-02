<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Request;
use App\Core\Response;
use App\Services\AuthService;
use App\Models\Property;
use App\Models\User;
use App\Models\SiteConfig;
use App\Database\Database;

class AdminController
{
    public function showLogin(Request $request): void
    {
        $response = new Response();
        
        // Si ya está autenticado, redirigir al dashboard
        $authService = new AuthService();
        if ($authService->isAuthenticated()) {
            $response->redirect('/admin/dashboard');
            return;
        }

        $response->view('admin/login', [
            'pageTitle' => 'Iniciar Sesión - Admin',
            'siteName' => SiteConfig::getSiteName()
        ]);
    }

    public function login(Request $request): void
    {
        $response = new Response();
        $authService = new AuthService();

        $username = $request->input('username');
        $password = $request->input('password');

        if (empty($username) || empty($password)) {
            if ($request->isAjax()) {
                $response->status(400)->json(['error' => 'Usuario y contraseña son requeridos']);
                return;
            }

            $response->view('admin/login', [
                'error' => 'Usuario y contraseña son requeridos',
                'pageTitle' => 'Iniciar Sesión - Admin',
                'siteName' => SiteConfig::getSiteName()
            ]);
            return;
        }

        if ($authService->login($username, $password)) {
            if ($request->isAjax()) {
                $response->json(['success' => true, 'redirect' => '/admin/dashboard']);
                return;
            }

            $response->redirect('/admin/dashboard');
            return;
        }

        if ($request->isAjax()) {
            $response->status(401)->json(['error' => 'Credenciales inválidas']);
            return;
        }

        $response->view('admin/login', [
            'error' => 'Credenciales inválidas',
            'username' => $username,
            'pageTitle' => 'Iniciar Sesión - Admin',
            'siteName' => SiteConfig::getSiteName()
        ]);
    }

    public function logout(Request $request): void
    {
        $response = new Response();
        $authService = new AuthService();
        
        $authService->logout();
        
        if ($request->isAjax()) {
            $response->json(['success' => true]);
            return;
        }

        $response->redirect('/admin/login');
    }

    public function dashboard(Request $request): void
    {
        $response = new Response();
        $authService = new AuthService();
        
        $currentUser = $authService->getCurrentUser();

        // Estadísticas para el dashboard
        $stats = [
            'properties' => Property::getStats(),
            'users' => User::getUserStats(),
            'recent_properties' => Property::getRecent(5)
        ];

        // Estadísticas adicionales
        $stats['sell_requests'] = Database::fetch("SELECT COUNT(*) as total FROM sell_requests WHERE status = 'pendiente'")['total'];
        $stats['contact_requests'] = Database::fetch("SELECT COUNT(*) as total FROM contact_requests WHERE status = 'nuevo'")['total'];

        // Solicitudes recientes
        $recentSellRequests = Database::fetchAll("
            SELECT * FROM sell_requests 
            ORDER BY created_at DESC 
            LIMIT 5
        ");

        $recentContactRequests = Database::fetchAll("
            SELECT cr.*, p.title as property_title 
            FROM contact_requests cr
            LEFT JOIN properties p ON cr.property_id = p.id
            ORDER BY cr.created_at DESC 
            LIMIT 5
        ");

        $response->view('admin/dashboard', [
            'currentUser' => $currentUser,
            'stats' => $stats,
            'recentSellRequests' => $recentSellRequests,
            'recentContactRequests' => $recentContactRequests,
            'pageTitle' => 'Dashboard - Admin',
            'siteName' => SiteConfig::getSiteName()
        ]);
    }
}