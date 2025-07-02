<?php
declare(strict_types=1);

namespace App\Middleware;

use App\Core\Request;
use App\Core\Response;
use App\Services\AuthService;

class AuthMiddleware
{
    public function handle(Request $request, Response $response): bool
    {
        $authService = new AuthService();
        
        if (!$authService->isAuthenticated()) {
            if ($request->isAjax()) {
                $response->status(401)->json(['error' => 'No autorizado']);
                return false;
            }
            
            $response->redirect('/admin/login');
            return false;
        }
        
        return true;
    }
}