<?php
declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use App\Core\Application;
use App\Core\Router;
use App\Controllers\HomeController;
use App\Controllers\PropertyController;
use App\Controllers\Admin\AdminController;
use App\Controllers\Admin\UserController;
use App\Controllers\Admin\PropertyAdminController;
use App\Controllers\Admin\ConfigController;
use App\Controllers\API\PropertyApiController;
use App\Middleware\AuthMiddleware;
use App\Middleware\AdminMiddleware;
use App\Middleware\CorsMiddleware;

// Cargar variables de entorno
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

// Inicializar aplicación
$app = new Application();

// Middleware global
$app->use(new CorsMiddleware());

// Rutas públicas del frontend
$app->get('/', [HomeController::class, 'index']);
$app->get('/propiedades', [PropertyController::class, 'index']);
$app->get('/propiedad/{id}', [PropertyController::class, 'show']);
$app->get('/vender', [PropertyController::class, 'sell']);
$app->post('/vender', [PropertyController::class, 'storeSellRequest']);
$app->get('/filtrar', [PropertyController::class, 'filter']);

// API Routes
$app->group('/api', function($router) {
    $router->get('/properties', [PropertyApiController::class, 'index']);
    $router->get('/properties/{id}', [PropertyApiController::class, 'show']);
    $router->get('/properties/search', [PropertyApiController::class, 'search']);
    $router->post('/contact', [PropertyApiController::class, 'contact']);
});

// Rutas de autenticación
$app->get('/admin/login', [AdminController::class, 'showLogin']);
$app->post('/admin/login', [AdminController::class, 'login']);
$app->post('/admin/logout', [AdminController::class, 'logout']);

// Rutas del panel de administración (requieren autenticación)
$app->group('/admin', function($router) {
    $router->get('/', [AdminController::class, 'dashboard']);
    $router->get('/dashboard', [AdminController::class, 'dashboard']);
    
    // Gestión de propiedades
    $router->get('/propiedades', [PropertyAdminController::class, 'index']);
    $router->get('/propiedades/crear', [PropertyAdminController::class, 'create']);
    $router->post('/propiedades', [PropertyAdminController::class, 'store']);
    $router->get('/propiedades/{id}/editar', [PropertyAdminController::class, 'edit']);
    $router->put('/propiedades/{id}', [PropertyAdminController::class, 'update']);
    $router->delete('/propiedades/{id}', [PropertyAdminController::class, 'delete']);
    
    // Configuración del sitio
    $router->get('/configuracion', [ConfigController::class, 'index']);
    $router->post('/configuracion', [ConfigController::class, 'update']);
    $router->get('/temas', [ConfigController::class, 'themes']);
    $router->post('/temas', [ConfigController::class, 'updateTheme']);
    
}, [new AuthMiddleware()]);

// Rutas de super administrador (gestión de usuarios)
$app->group('/admin/usuarios', function($router) {
    $router->get('/', [UserController::class, 'index']);
    $router->get('/crear', [UserController::class, 'create']);
    $router->post('/', [UserController::class, 'store']);
    $router->get('/{id}/editar', [UserController::class, 'edit']);
    $router->put('/{id}', [UserController::class, 'update']);
    $router->delete('/{id}', [UserController::class, 'delete']);
}, [new AuthMiddleware(), new AdminMiddleware()]);

// Ejecutar aplicación
$app->run();