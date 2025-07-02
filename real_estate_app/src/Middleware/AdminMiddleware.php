<?php
declare(strict_types=1);

namespace App\Middleware;

use App\Core\Request;
use App\Core\Response;
use App\Services\AuthService;

class AdminMiddleware
{
    public function handle(Request $request, Response $response): bool
    {
        $authService = new AuthService();
        $user = $authService->getCurrentUser();
        
        if (!$user || $user['role'] !== 'super_admin') {
            if ($request->isAjax()) {
                $response->status(403)->json(['error' => 'Acceso denegado']);
                return false;
            }
            
            $response->redirect('/admin/dashboard');
            return false;
        }
        
        return true;
    }
}